<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Attribute;
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
        $products = Product::orderBy('id','desc')->get();
        $categories = Category::orderBy('id','desc')->get();
        $brands = Brand::orderBy('id','desc')->get();
        $tags = Tag::orderBy('id','desc')->get();
        $attributes = Attribute::orderBy('id','desc')->get();
        $shippingClasses = ShippingClass::where('status', 1)->orderBy('name')->get();

        return view('backend.products.index', compact('products', 'categories', 'brands', 'tags', 'attributes', 'shippingClasses'));
    }

    public function store(Request $request)
    {
        // DB::beginTransaction();
        // try {
            $data = $request->all();
            $product = Product::create($data);

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product created successfully.');

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        // }
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
            $data['slug'] = Str::slug($data['name']);

            $product->update($data);

            // Sync attributes
            if ($request->has('attribute_id')) {
                ProductAttribute::where('product_id', $product->id)->delete();
                foreach ($request->attribute_id as $attributeId) {
                    ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attributeId
                    ]);
                }
            }

            // Sync variants
            if ($request->has('variants')) {
                ProductVariant::where('product_id', $product->id)->delete();
                foreach ($request->variants as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variant['sku'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                        'attribute_items' => json_encode($variant['attributes'] ?? [])
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }


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
        $price = $request->input('sale_price', 0);
        $purchase_cost = $request->input('purchase_price', 0);
        $attributes = collect($request->input('attributes', []))->filter(fn($a) => !empty($a['items']))->values();

        if ($attributes->isEmpty()) {
            return '';
        }

        // Collect items for cartesian
        $combos = $this->cartesianProduct($attributes->pluck('items')->toArray());

        $variants = collect($combos)->map(function ($combo) use ($skuPrefix, $price, $purchase_price) {
            $names = [];
            foreach ($combo as $id) {
                $item = AttributeItem::find($id);
                if ($item) $names[] = $item->name;
            }

            $sku = $skuPrefix . '-' . strtolower(implode('-', array_map(fn($n) => str_replace(' ', '-', $n), $names)));

            return [
                'name' => implode(' | ', $names),
                'sku' => $sku,
                'price' => $price,
                'purchase_price' => $purchase_price > 0 ? $purchase_price : $price * 0.75,
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
