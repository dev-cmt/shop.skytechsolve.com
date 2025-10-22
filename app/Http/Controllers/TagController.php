<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->paginate(10);
        return view('backend.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tags,name',
        ]);

        Tag::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Tag created successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tags,id',
            'name' => 'required|unique:tags,name,' . $request->id,
        ]);

        $tag = Tag::findOrFail($request->id);
        $tag->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Tag updated successfully.');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->back()->with('success', 'Tag deleted successfully.');
    }
}
