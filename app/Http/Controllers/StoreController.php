<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper; // Assuming this helper is available
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    /**
     * Display a listing of stores.
     */
    public function index()
    {
        // Paginate stores, ordering by the 'id' descending to show latest first
        $stores = Store::latest()->paginate(10);
        return view('backend.stores.index', compact('stores'));
    }

    /**
     * Store a newly created store.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stores,name',
            'code' => 'required|string|max:50|unique:stores,code', // New field: Store Code
            'phone' => 'nullable|string|max:20',                   // New field: Phone
            'email' => 'nullable|email|max:255',                   // New field: Email
            'address' => 'nullable|string',                        // New field: Address
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'status' => 'required|boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = ImageHelper::uploadImage($request->file('logo'), 'uploads/stores');
        }

        Store::create($validated);

        return redirect()->route('stores.index')->with('success', 'Store created successfully.');
    }

    /**
     * Update an existing store.
     */
    public function update(Request $request)
    {
        $store = Store::findOrFail($request->id);

        $validated = $request->validate([
            'id' => 'required|exists:stores,id',
            'name' => [
                'required',
                'string',
                'max:255',
                // Ensure name is unique, excluding the current store's ID
                Rule::unique('stores', 'name')->ignore($store->id),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                // Ensure code is unique, excluding the current store's ID
                Rule::unique('stores', 'code')->ignore($store->id),
            ],
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'status' => 'required|boolean',
        ]);

        // Handle logo upload (delete old logo if exists)
        if ($request->hasFile('logo')) {
            $validated['logo'] = ImageHelper::uploadImage($request->file('logo'), 'uploads/stores', $store->logo);
        } else {
            // Keep the existing logo path if no new file is uploaded
            unset($validated['logo']);
        }
        
        // Remove 'id' from the validated array before update
        unset($validated['id']);

        $store->update($validated);

        return redirect()->route('stores.index')->with('success', 'Store updated successfully.');
    }

    /**
     * Delete a store.
     */
    public function destroy(Store $store)
    {
        // Delete logo if exists
        if ($store->logo) {
            ImageHelper::deleteImage($store->logo);
        }

        $store->delete();

        return redirect()->route('stores.index')->with('success', 'Store deleted successfully.');
    }
}