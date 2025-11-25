<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\PageBuilder;

class PageController extends Controller
{

    protected $pageBuilder;

    public function __construct(PageBuilder $pageBuilder)
    {
        $this->pageBuilder = $pageBuilder;
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('status', true)->with('activeSections')->firstOrFail();

        return view('page-builder.pages.show', compact('page'));
    }

    /**-----------------------------------------------------------
     * BACKEND
     * -----------------------------------------------------------
     */
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return view('page-builder.admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('page-builder.admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'meta_description' => 'nullable|string',
            'layout' => 'required|string',
        ]);

        $page = Page::create([
            'title' => $request->title,
            'slug' => $request->slug ?? Str::slug($request->title),
            'meta_description' => $request->meta_description,
            'layout' => $request->layout,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.pages.builder', $page->id)
            ->with('success', 'Page created successfully! You can now add sections.');
    }

    public function edit(Page $page)
    {
        return view('page-builder.admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'meta_description' => 'nullable|string',
            'layout' => 'required|string',
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'meta_description' => $request->meta_description,
            'layout' => $request->layout,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully!');
    }

    public function builder(Page $page)
    {
        $sectionTypes = $this->pageBuilder->getSectionTypes();
        return view('page-builder.admin.pages.builder', [
            'page' => $page,
            'sectionTypes' => $sectionTypes,
            'pageBuilder' => $this->pageBuilder
        ]);
    }

    public function publish(Page $page)
    {
        $page->update(['is_published' => true]);
        return back()->with('success', 'Page published successfully!');
    }

    public function unpublish(Page $page)
    {
        $page->update(['is_published' => false]);
        return back()->with('success', 'Page unpublished successfully!');
    }

}
