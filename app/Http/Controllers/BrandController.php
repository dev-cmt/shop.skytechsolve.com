<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of brands.
     */
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view('backend.brands.index', compact('brands'));
    }

    /**
     * Store a newly created brand.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'sort_order' => 'nullable|integer',
            'status' => 'required|boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = ImageHelper::uploadImage($request->file('logo'), 'uploads/brands');
        }

        Brand::create($validated);

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }

    /**
     * Update an existing brand.
     */
    public function update(Request $request)
    {
        $brand = Brand::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'sort_order' => 'nullable|integer',
            'status' => 'required|boolean',
        ]);

        // Handle logo upload (delete old logo if exists)
        if ($request->hasFile('logo')) {
            $validated['logo'] = ImageHelper::uploadImage($request->file('logo'), 'uploads/brands', $brand->logo);
        }

        $brand->update($validated);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Delete a brand.
     */
    public function destroy(Brand $brand)
    {
        // Delete logo if exists
        if ($brand->logo) {
            ImageHelper::deleteImage($brand->logo);
        }

        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }
}
