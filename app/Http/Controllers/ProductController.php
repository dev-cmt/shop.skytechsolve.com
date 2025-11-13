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
        $shippingClasses = ShippingClass::where('status', 1)->orderBy('id','asc')->get();

        $product->load(['variants.variantItems.attribute', 'variants.variantItems.attributeItem', 'shipping', 'discount', 'seo']);

        dd($product->variants->first()->variantItems->attributeItem);


        return view('backend.products.edit', compact('product', 'categories', 'brands', 'tags', 'attributes', 'shippingClasses'));
    }

    public function update(Request $request, Product $product)
    {
        // DB::beginTransaction();

        // try {
            $data = $request->all();
            // dd($data);

            // --- 1. Update Product ---
            $product->update($data);

            // --- 2. Update Attributes ---
            if ($request->has('variants') && isset($request->variants['sku'])) {
                // Remove old variants and related items
                $product->variants()->delete();

                foreach ($request->variants['sku'] as $i => $sku) {
                    $variant = $product->variants()->create([
                        'sku' => $sku,
                        'price' => $request->variants['price'][$i] ?? 0,
                        'purchase_cost' => $request->variants['purchase_cost'][$i] ?? 0,
                        'quantity' => $request->variants['quantity'][$i] ?? 0,
                    ]);

                    // --- Attribute Items for this variant ---
                    if (!empty($request->attribute_items)) {
                        foreach ($request->attribute_items as $attrId => $items) {
                            foreach ($items as $itemId) {
                                $variant->variantItems()->create([
                                    'attribute_id' => $attrId,
                                    'attribute_item_id' => $itemId,
                                    'image' => $request->attribute_images[$attrId][$itemId] ?? null,
                                ]);
                            }
                        }
                    }
                }
            }

            // --- 4. Update Discount ---
            $discountData = array_filter(
                Arr::only($data, ['discount_type','amount','start_date','end_date']) + ['status' => (int)($data['discount_status'] ?? 0)],
                fn($v) => $v !== null
            );
            if ($discountData) {
                $product->discount ? $product->discount()->update($discountData) : $product->discount()->create($discountData);
            }

            // --- 5. Update Shipping ---
            $shippingData = array_filter(
                Arr::only($data, ['weight','length','width','height','shipping_cost', 'shipping_class_id', 'inside_city_rate', 'outside_city_rate', 'free_shipping']),
                fn($value) => $value !== null
            );
            if ($shippingData) {
                $product->shipping ? $product->shipping()->update($shippingData) : $product->shipping()->create($shippingData);
            }

            // --- 6. Update SEO ---
            $seoData = Arr::only($data, ['meta_title', 'meta_description', 'meta_keywords']);

            if ($request->hasFile('meta_image') || !empty($data['delete_meta_image'])) {
                if ($old = optional($product->seo)->og_image) file_exists(public_path($old)) && unlink(public_path($old));
                $seoData['og_image'] = $request->hasFile('meta_image') ? ImageHelper::uploadImage($request->file('meta_image'), 'uploads/seo') : null;
            }
            if ($seoData) {
                $product->seo ? $product->seo()->update($seoData) : $product->seo()->create($seoData);
            }

            // DB::commit();
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        // }
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
    public function getItems(Request $request)
    {
        $attributeIds = $request->get('attribute_ids', []);
        if (empty($attributeIds)) {
            return '';
        }

        $attributes = Attribute::with('items')
            ->whereIn('id', $attributeIds)
            ->get();

        return view('backend.products.partials._attribute_items', compact('attributes'))->render();
    }

    public function getVariantCombinations(Request $request)
    {
        $skuPrefix = $request->input('sku_prefix', 'SKU');
        $sale_price = $request->input('sale_price', 0);
        $purchase_price = $request->input('purchase_price', 0);
        $attributes = collect($request->input('attributes', []))->filter(fn($a) => !empty($a['items']))->values();

        if ($attributes->isEmpty()) {
            return '';
        }

        // Collect items for cartesian
        $combos = $this->cartesianProduct($attributes->pluck('items')->toArray());

        $variants = collect($combos)->map(function ($combo) use ($skuPrefix, $sale_price, $purchase_price) {
            $names = [];
            foreach ($combo as $id) {
                $item = AttributeItem::find($id);
                if ($item) $names[] = $item->name;
            }

            $sku = $skuPrefix . '-' . strtolower(implode('-', array_map(fn($n) => str_replace(' ', '-', $n), $names)));

            return [
                'name' => implode(' | ', $names),
                'sku' => $sku,
                'price' => $sale_price,
                'purchase_cost' => $purchase_price > 0 ? $purchase_price : $sale_price * 0.75,
                'quantity' => 0,
            ];
        });

        return view('backend.products.partials._variant_table', compact('variants'))->render();
    }
    private function cartesianProduct($arrays)
    {
        $result = [[]];
        foreach ($arrays as $propertyValues) {
            $tmp = [];
            foreach ($result as $resultItem) {
                foreach ($propertyValues as $propertyValue) {
                    $tmp[] = array_merge($resultItem, [$propertyValue]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

}
