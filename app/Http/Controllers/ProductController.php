<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Models\ShippingClass;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id','desc')->get();
        $categories = Category::orderBy('id','desc')->get();
        $brands = Brand::orderBy('id','desc')->get();
        $attributes = Attribute::orderBy('id','desc')->get();
        $attribute_items = AttributeItem::orderBy('id','desc')->get();
        $shippingClasses = ShippingClass::where('status', 1)->orderBy('name')->get();

        return view('backend.products.index', compact('products', 'categories', 'brands', 'attributes', 'attribute_items', 'shippingClasses'));
    }
    


    // public function getItems(Request $request)
    // {
    //     $attributeIds = $request->attribute_ids;
    //     $attributes = Attribute::with('items')->whereIn('id', $attributeIds)->get();

    //     return response()->json($attributes);
    // }


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
        $price = $request->input('price', 0);
        $purchase_price = $request->input('purchase_price', 0);
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
