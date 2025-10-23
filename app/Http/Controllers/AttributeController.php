<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeItem;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('items')->orderBy('id','desc')->get();
        return view('backend.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'display_type' => 'required|in:text,color,image,dropdown',
            'has_image' => 'nullable|boolean'
        ]);

        $attribute = Attribute::create([
            'name' => $request->name,
            'display_type' => $request->display_type,
            'has_image' => $request->has_image ?? 0
        ]);

        return response()->json(['success' => true, 'attribute' => $attribute]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:attributes,id',
            'name' => 'required|string|max:255',
            'display_type' => 'required|in:text,color,image,dropdown',
            'has_image' => 'nullable|boolean'
        ]);

        $attribute = Attribute::findOrFail($request->id);
        $attribute->update([
            'name' => $request->name,
            'display_type' => $request->display_type,
            'has_image' => $request->has_image ?? 0
        ]);

        return response()->json(['success' => true, 'attribute' => $attribute]);
    }

    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:attributes,id']);
        Attribute::findOrFail($request->id)->delete();
        return response()->json(['success' => true]);
    }

    // ----- Attribute Items ----- //
    public function storeItem(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'name' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer'
        ]);

        $item = AttributeItem::create([
            'attribute_id' => $request->attribute_id,
            'name' => $request->name,
            'value' => $request->value,
            'sort_order' => $request->sort_order ?? 0
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function updateItem(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:attribute_items,id',
            'name' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer'
        ]);

        $item = AttributeItem::findOrFail($request->id);
        $item->update([
            'name' => $request->name,
            'value' => $request->value,
            'sort_order' => $request->sort_order ?? 0
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function destroyItem(Request $request)
    {
        $request->validate(['id' => 'required|exists:attribute_items,id']);
        AttributeItem::findOrFail($request->id)->delete();
        return response()->json(['success' => true]);
    }
}
