<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Helpers\ImageHelper;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\ProductDiscount;
use App\Models\ProductShipping;
use App\Models\ProductTag;
use App\Models\ShippingClass;
use App\Models\Media;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id','desc')->paginate(2);
        return view('backend.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->where('status', 1)->get();
        $brands = Brand::orderBy('name')->where('status', 1)->get();
        $tags = Tag::orderBy('name')->get();
        $attributes = Attribute::orderBy('name')->where('status', 1)->get();
        $shippingClasses = ShippingClass::orderBy('id','asc')->where('status', 1)->get();

        return view('backend.products.create', compact('categories', 'brands', 'tags', 'attributes', 'shippingClasses'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        DB::transaction(function() use ($data, $request) {
            // 1. Create Product
            $product = Product::create($data);

            // 2. Variants
            if (!empty($data['variants'])) {
                foreach ($data['variants']['sku'] as $i => $sku) {
                    $variant = $product->variants()->create([
                        'sku' => $sku,
                        'price' => $data['variants']['price'][$i] ?? 0,
                        'purchase_cost' => $data['variants']['purchase_cost'][$i] ?? 0,
                        'quantity' => $data['variants']['quantity'][$i] ?? 0
                    ]);

                    // 3. Attribute items for this variant
                    if (!empty($data['attribute_items'])) {
                        foreach ($data['attribute_items'] as $attrId => $items) {
                            foreach ($items as $itemId) {
                                $variant->variantItems()->create([
                                    'attribute_id' => $attrId,
                                    'attribute_item_id' => $itemId,
                                    'image' => 'image.png'
                                ]);
                            }
                        }
                    }
                }
            }

            // 3. Create Discount
            $discountData = array_filter(
                Arr::only($data, ['discount_type','amount','start_date','end_date']) + ['status' => (int)($data['discount_status'] ?? 0)],
                fn($v) => $v !== null // Keep 0
            );
            if ($discountData) {
                $product->discount()->create($discountData);
            }

            // 4. Create Shipping
            $shippingData = array_filter(
                Arr::only($data, ['weight','length','width','height','shipping_cost', 'shipping_class_id', 'inside_city_rate', 'outside_city_rate', 'free_shipping']),
                fn($value) => $value !== null
            );
            if ($shippingData) {
                $product->shipping()->create($shippingData);
            }

            // 5. Create SEO
            if ($request->hasFile('meta_image')) {
                $data['og_image'] = ImageHelper::uploadImage($request->file('meta_image'), 'uploads/seo');
            }
            $seoData = array_filter(Arr::only($data, ['meta_title','meta_description','meta_keywords','og_image']));
            if ($seoData) {
                $product->seo ? $product->seo()->update($seoData) : $product->seo()->create($seoData);
            }

        });

        // Redirect
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->where('status', 1)->get();
        $brands = Brand::orderBy('name')->where('status', 1)->get();
        $tags = Tag::orderBy('name')->get();
        $attributes = Attribute::orderBy('name')->where('status', 1)->get();
        $shippingClasses = ShippingClass::where('status', 1)->orderBy('id', 'asc')->get();

        $product->load([
            'variants.variantItems.attribute',
            'variants.variantItems.attributeItem',
            'shipping',
            'discount',
            'seo'
        ]);

        // ------------------------------------------
        // SELECTED ATTRIBUTE IDS
        // ------------------------------------------
        $selectedAttributeIds = $product->variants
            ->pluck('variantItems.*.attribute_id')
            ->flatten()
            ->unique()
            ->values();

        // ------------------------------------------
        // SELECTED ATTRIBUTE ITEM IDS (group by attribute)
        // ------------------------------------------
        $selectedItems = [];  // <-- needed by your blade

        foreach ($product->variants as $variant) {
            foreach ($variant->variantItems as $item) {
                $selectedItems[$item->attribute_id][] = $item->attribute_item_id;
            }
        }

        // remove duplicates
        foreach ($selectedItems as $attrId => $items) {
            $selectedItems[$attrId] = array_unique($items);
        }

        // ------------------------------------------
        // Existing Attribute Images (if you store them)
        // Structure:  $existingImages[attribute_id][item_id] = "path/to/img.jpg"
        // ------------------------------------------
        $existingImages = [];

        foreach ($product->variants as $variant) {
            foreach ($variant->variantItems as $item) {
                if ($item->image) {
                    $existingImages[$item->attribute_id][$item->attribute_item_id] = $item->image;
                }
            }
        }

        return view('backend.products.edit', compact(
            'product',
            'categories',
            'brands',
            'tags',
            'attributes',
            'shippingClasses',
            'selectedAttributeIds',
            'selectedItems',          // FIXED
            'existingImages'          // FIXED
        ));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->all();

        // -------------------------
        // 1. UPDATE PRODUCT
        // -------------------------
        $product->update($data);

        // -------------------------
        // 2. UPDATE VARIANTS
        // -------------------------
        if ($request->has('variants') && isset($request->variants['sku'])) {

            $incomingSKUs = $request->variants['sku'];
            $updatedVariantIDs = [];

            foreach ($incomingSKUs as $i => $sku) {

                // Update or create variant by SKU
                $variant = $product->variants()->updateOrCreate(
                    ['sku' => $sku],
                    [
                        'price'         => $request->variants['price'][$i] ?? 0,
                        'purchase_cost' => $request->variants['purchase_cost'][$i] ?? 0,
                        'quantity'      => $request->variants['quantity'][$i] ?? 0,
                    ]
                );
                $updatedVariantIDs[] = $variant->id;

                // -----------------------------
                // UPDATE VARIANT ITEMS + IMAGES
                // -----------------------------
                if (!empty($request->attribute_items)) {

                    $incomingPairs = [];

                    foreach ($request->attribute_items as $attrId => $items) {
                        foreach ($items as $itemId) {

                            $incomingPairs[] = $attrId.'-'.$itemId;

                            $oldItem = $variant->variantItems()
                                ->where('attribute_id', $attrId)
                                ->where('attribute_item_id', $itemId)
                                ->first();

                            $newImage = $request->file("attribute_images.$attrId.$itemId");

                            $finalImage = ImageHelper::uploadImage(
                                $newImage,
                                'uploads/variant',
                                $oldItem->image ?? null
                            );

                            $variant->variantItems()->updateOrCreate(
                                ['attribute_id' => $attrId, 'attribute_item_id' => $itemId],
                                ['image' => $finalImage]
                            );
                        }
                    }

                    // Delete old items not in incomingPairs
                    $variant->variantItems()->get()->each(function($item) use ($incomingPairs) {
                        if (!in_array($item->attribute_id.'-'.$item->attribute_item_id, $incomingPairs)) {
                            ImageHelper::deleteImage($item->image);
                            $item->delete();
                        }
                    });
                }
            }

            // -----------------------------
            // DELETE VARIANTS NOT IN REQUEST
            // -----------------------------
            $product->variants()->whereNotIn('id', $updatedVariantIDs)->get()->each(function($variant){
                $variant->variantItems->each(fn($item) => ImageHelper::deleteImage($item->image));
                $variant->delete();
            });
        }

        // -------------------------
        // 3. UPDATE DISCOUNT
        // -------------------------
        $discountData = array_filter(
            Arr::only($data, ['discount_type','amount','start_date','end_date'])
            + ['status' => (int)($data['discount_status'] ?? 0)],
            fn($v) => $v !== null
        );

        if ($discountData) {
            $product->discount
                ? $product->discount()->update($discountData)
                : $product->discount()->create($discountData);
        }

        // -------------------------
        // 4. UPDATE SHIPPING
        // -------------------------
        $shippingData = array_filter(
            Arr::only($data, [
                'weight','length','width','height','shipping_cost',
                'shipping_class_id','inside_city_rate','outside_city_rate','free_shipping'
            ]),
            fn($v) => $v !== null
        );

        if ($shippingData) {
            $product->shipping
                ? $product->shipping()->update($shippingData)
                : $product->shipping()->create($shippingData);
        }

        // -------------------------
        // 5. UPDATE SEO IMAGE
        // -------------------------
        $seoData = Arr::only($data, ['meta_title','meta_description','meta_keywords']);
        $metaImage = $request->file('meta_image');

        $seoData['og_image'] = ImageHelper::uploadImage(
            $metaImage,
            'uploads/seo',
            optional($product->seo)->og_image
        );

        // Handle delete meta image request
        if (!empty($data['delete_meta_image'])) {
            ImageHelper::deleteImage(optional($product->seo)->og_image);
            $seoData['og_image'] = null;
        }

        if ($seoData) {
            $product->seo
                ? $product->seo()->update($seoData)
                : $product->seo()->create($seoData);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * ----------------------------------------------------------------------
     * Get attribute items based on selected attribute IDs
     * ----------------------------------------------------------------------
     */
    // public function getItems(Request $request)
    // {
    //     $attributeIds = $request->get('attribute_ids', []);
    //     if (empty($attributeIds)) {
    //         return '';
    //     }

    //     $attributes = Attribute::with('items')
    //         ->whereIn('id', $attributeIds)
    //         ->get();

    //     return view('backend.products.partials._attribute_items', compact('attributes'))->render();
    // }


    public function getItems(Request $request)
    {
        $attributeIds = $request->get('attribute_ids', []);
        if (empty($attributeIds)) return '';

        $attributes = Attribute::with('items')->whereIn('id', $attributeIds)->get();

        $selectedItems = [];
        $existingImages = [];

        if ($request->has('product_id')) {
            $product = Product::with('variants.variantItems')->find($request->product_id);

            if ($product) {
                foreach ($product->variants as $variant) {
                    foreach ($variant->variantItems as $item) {
                        $selectedItems[$item->attribute_id][] = $item->attribute_item_id;
                        if ($item->image) $existingImages[$item->attribute_id][$item->attribute_item_id] = $item->image;
                    }
                }
                foreach ($selectedItems as $attrId => $items) $selectedItems[$attrId] = array_unique($items);
            }
        }

        // dd($product->variants);

        return view('backend.products.partials._attribute_items', compact('attributes','selectedItems','existingImages'))->render();
    }


    // public function getVariantCombinations(Request $request)
    // {
    //     $skuPrefix = $request->input('sku_prefix', 'SKU');
    //     $sale_price = $request->input('sale_price', 0);
    //     $purchase_price = $request->input('purchase_price', 0);
    //     $attributes = collect($request->input('attributes', []))->filter(fn($a) => !empty($a['items']))->values();

    //     if ($attributes->isEmpty()) {
    //         return '';
    //     }

    //     // Collect items for cartesian
    //     $combos = $this->cartesianProduct($attributes->pluck('items')->toArray());

    //     $variants = collect($combos)->map(function ($combo) use ($skuPrefix, $sale_price, $purchase_price) {
    //         $names = [];
    //         foreach ($combo as $id) {
    //             $item = AttributeItem::find($id);
    //             if ($item) $names[] = $item->name;
    //         }

    //         $sku = $skuPrefix . '-' . strtolower(implode('-', array_map(fn($n) => str_replace(' ', '-', $n), $names)));

    //         return [
    //             'name' => implode(' | ', $names),
    //             'sku' => $sku,
    //             'price' => $sale_price,
    //             'purchase_cost' => $purchase_price > 0 ? $purchase_price : $sale_price * 0.75,
    //             'quantity' => 0,
    //         ];
    //     });

    //     return view('backend.products.partials._variant_table', compact('variants'))->render();
    // }

    public function getVariantCombinations(Request $request)
    {
        $skuPrefix = $request->input('sku_prefix', 'SKU');
        $sale_price = $request->input('sale_price', 0);
        $purchase_price = $request->input('purchase_price', 0);
        $attributes = collect($request->input('attributes', []))
                        ->filter(fn($a) => !empty($a['items']))
                        ->map(function($a) {
                            $a['items'] = array_map('intval', $a['items']);
                            return $a;
                        })->values();

        if ($attributes->isEmpty()) {
            return '';
        }

        // Load existing variants if product_id is provided
        $existingVariants = [];
        $productId = $request->input('product_id');
        if ($productId) {
            $product = Product::with('variants.variantItems')->find($productId);
            if ($product) {
                foreach ($product->variants as $variant) {
                    $existingVariants[] = [
                        'sku' => $variant->sku,
                        'price' => $variant->price,
                        'purchase_cost' => $variant->purchase_cost,
                        'quantity' => $variant->quantity,
                        'items' => collect($variant->variantItems->pluck('attribute_item_id'))->map(fn($id) => (int)$id)->sort()->values()->all()
                    ];
                }
            }
        }

        // Collect items for cartesian product
        $combos = $this->cartesianProduct($attributes->pluck('items')->toArray());

        $variants = collect($combos)->map(function ($combo) use ($skuPrefix, $sale_price, $purchase_price, $existingVariants) {
            $comboItemIds = array_map('intval', $combo);
            sort($comboItemIds);

            // Get attribute item names
            $names = AttributeItem::whereIn('id', $comboItemIds)->pluck('name')->toArray();

            // Generate default SKU for this combination
            $defaultSku = $skuPrefix . '-' . strtolower(implode('-', array_map(fn($n) => str_replace(' ', '-', $n), $names)));

            // Check if this SKU exists in existing variants
            $existing = collect($existingVariants)->first(function ($v) use ($defaultSku) {
                return $v['sku'] === $defaultSku;
            });

            return [
                'name' => implode(' | ', $names),
                'sku' => $existing['sku'] ?? $defaultSku,
                'price' => $existing['price'] ?? $sale_price,
                'purchase_cost' => $existing['purchase_cost'] ?? ($purchase_price > 0 ? $purchase_price : $sale_price * 0.75),
                'quantity' => $existing['quantity'] ?? 0,
                'items' => $comboItemIds
            ];
        });

        return view('backend.products.partials._variant_table', ['variants' => $variants])->render();
    }


    private function cartesianProduct($arrays)
    {
        $result = [[]];
        foreach ($arrays as $propertyValues) {
            $tmp = [];
            foreach ($result as $resultItem) {
                foreach ($propertyValues as $propertyValue) {
                    $tmp[] = array_merge($resultItem, [(int)$propertyValue]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }


}
