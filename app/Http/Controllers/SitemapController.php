<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Serve sitemap.xml
     */
    public function index()
    {
        $data = [
            'pages'     => Page::select('id', 'slug', 'updated_at')->get(),
            'products'  => Product::select('id', 'slug', 'updated_at')->where('status', 1)->get(),
            'blogs'     => BlogPost::select('id', 'slug', 'updated_at')->where('status', 1)->get(),
            'lastmod'   => Carbon::now()->toIso8601String(), // Sitemap last update
        ];

        return response()->view('sitemap.index', $data)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
