<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('sort_order')->paginate(10);
        return view('backEnd.admin.clients.index', compact('clients'));
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
            $validated['logo'] = ImageHelper::uploadImage($request->file('logo'), 'uploads/clients');
        }

        Client::create($validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean'
        ]);

        $client = Client::findOrFail($validated['id']);

        // Handle logo upload using ImageHelper
        if ($request->hasFile('logo')) {
            $validated['logo'] = ImageHelper::uploadImage(
                $request->file('logo'), 
                'uploads/clients', 
                $client->logo // Pass current logo for deletion if exists
            );
        } else {
            // Keep the existing logo if no new file is uploaded
            $validated['logo'] = $client->logo;
        }

        $client->update($validated);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        
        // Delete logo file if exists using ImageHelper logic
        if ($client->logo && file_exists(public_path($client->logo))) {
            unlink(public_path($client->logo));
        }
        
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}