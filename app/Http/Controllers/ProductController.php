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
    


    public function getItems(Request $request)
    {
        $attributeIds = $request->attribute_ids;
        $attributes = Attribute::with('items')->whereIn('id', $attributeIds)->get();

        return response()->json($attributes);
    }
}
