<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Helper\ImageHelper;
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
        $products = Product::orderBy('id','desc')->get();
        $categories = Category::orderBy('id','desc')->get();
        $brands = Brand::orderBy('id','desc')->get();
        $tags = Tag::orderBy('id','desc')->get();
        $attributes = Attribute::orderBy('id','desc')->get();
        $shippingClasses = ShippingClass::where('status', 1)->orderBy('name')->get();

        return view('backend.products.create', compact('products', 'categories', 'brands', 'tags', 'attributes', 'shippingClasses'));
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
            $discountData = Arr::only($data, ['discount_type', 'amount', 'start_date', 'end_date']);
            if (!empty($discountData) && Arr::whereNotNull($discountData)) {
                $product->discounts()->create($discountData);
            }

            // 4. Create Shipping
            $shippingData = Arr::only($data, ['weight', 'length', 'width', 'height', 'shipping_cost']);
            if (!empty($shippingData) && Arr::whereNotNull($shippingData)) {
                $product->shipping()->create($shippingData);
            }

            // 5. Create SEO
            if ($request->hasFile('meta_image')) {
                $data['og_image'] = ImageHelper::uploadImage($request->file('meta_image'), 'uploads/seo');
            }
            $seoData = Arr::only($data, ['meta_title', 'meta_description', 'meta_keywords', 'og_image']);
            if (!empty($seoData) && Arr::whereNotNull($seoData)) {
                $product->seo()->create($seoData);
            }
        });

        // Redirect
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();
        $attributes = Attribute::with('items')->where('status', 1)->get();
        $product->load(['attributes', 'variants']);

        return view('backend.products.edit', compact('product', 'categories', 'brands', 'attributes'));
    }

    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            // --- 1. Update Product ---
            $data['slug'] = Str::slug($data['name']);
            $product->update($data);

            // --- 2. Update Attributes ---
            if ($request->has('attribute_id')) {
                ProductAttribute::where('product_id', $product->id)->delete();
                foreach ($request->attribute_id as $attributeId) {
                    ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attributeId,
                    ]);
                }
            }

            // --- 3. Update Variants ---
            if ($request->has('variants') && isset($request->variants['sku'])) {
                ProductVariant::where('product_id', $product->id)->delete();

                foreach ($request->variants['sku'] as $i => $sku) {
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $sku,
                        'price' => $request->variants['price'][$i] ?? 0,
                        'purchase_cost' => $request->variants['purchase_cost'][$i] ?? 0,
                        'quantity' => $request->variants['quantity'][$i] ?? 0,
                    ]);

                    // --- 3.1 Attribute Items for each variant ---
                    if (!empty($request->attribute_items)) {
                        foreach ($request->attribute_items as $attrId => $items) {
                            foreach ($items as $itemId) {
                                $variant->variantItems()->create([
                                    'attribute_id' => $attrId,
                                    'attribute_item_id' => $itemId,
                                    'image' => 'image.png',
                                ]);
                            }
                        }
                    }
                }
            }

            // --- 4. Update Discount ---
            $discountData = Arr::only($data, ['discount_type', 'amount', 'start_date', 'end_date']);
            if (Arr::whereNotNull($discountData)) {
                $product->discounts()->delete();
                $product->discounts()->create($discountData);
            }

            // --- 5. Update Shipping ---
            $shippingData = Arr::only($data, ['weight', 'length', 'width', 'height', 'shipping_cost']);
            if (Arr::whereNotNull($shippingData)) {
                $product->shipping()->delete();
                $product->shipping()->create($shippingData);
            }

            // --- 6. Update SEO ---
            if ($request->hasFile('meta_image')) {
                $data['og_image'] = ImageHelper::uploadImage($request->file('meta_image'), 'uploads/seo');
            }

            $seoData = Arr::only($data, ['meta_title', 'meta_description', 'meta_keywords', 'og_image']);
            if (Arr::whereNotNull($seoData)) {
                $product->seo()->delete();
                $product->seo()->create($seoData);
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
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
