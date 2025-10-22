<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->paginate(10);
        return view('backend.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        // Handle logo upload using ImageHelper
        if ($request->hasFile('logo')) {
            $validated['logo'] = ImageHelper::uploadImage($request->file('logo'), 'uploads/brands');
        }

        Brand::create($validated);

        return redirect()->route('brands.index')
            ->with('success', 'Client created successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        $client = Brand::findOrFail($validated['id']);

        // Handle logo upload using ImageHelper
        if ($request->hasFile('logo')) {
            $validated['logo'] = ImageHelper::uploadImage(
                $request->file('logo'),
                'uploads/brands',
                $client->logo // Pass current logo for deletion if exists
            );
        } else {
            // Keep the existing logo if no new file is uploaded
            $validated['logo'] = $client->logo;
        }

        $client->update($validated);

        return redirect()->route('brands.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy($id)
    {
        $client = Brand::findOrFail($id);

        // Delete logo file if exists using ImageHelper logic
        if ($client->logo && file_exists(public_path($client->logo))) {
            unlink(public_path($client->logo));
        }

        $client->delete();

        return redirect()->route('brands.index')
            ->with('success', 'Client deleted successfully.');
    }
}
