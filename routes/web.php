<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PageSeoController;
use App\Http\Controllers\DeveloperApiController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\SitemapController;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/cc', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    return 'Cleared!';
});

Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/page/frodly', [HomeController::class, 'pageFrodly'])->name('page.frodly'); // Not used
Route::get('/get/frodly', [HomeController::class, 'getFrodly'])->name('get.frodly');



Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/products/update', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{products}/delete', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/attributes/items', [ProductController::class, 'getItems'])->name('attributes.getItems');
    Route::get('/products/variants', [ProductController::class, 'getVariantCombinations'])->name('products.getItemsCombo');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Brands
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::post('/brands/update', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}/delete', [BrandController::class, 'destroy'])->name('brands.destroy');

    // Tags
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('tags/store', [TagController::class, 'store'])->name('tags.store');
    Route::post('tags/update', [TagController::class, 'update'])->name('tags.update');
    Route::delete('tags/{tags}/delete', [TagController::class, 'destroy'])->name('tags.destroy');

    // Attributes
    Route::get('attributes', [AttributeController::class, 'index'])->name('attributes.index');
    Route::post('attributes/store', [AttributeController::class, 'store'])->name('attributes.store');
    Route::post('attributes/update', [AttributeController::class, 'update'])->name('attributes.update');
    Route::post('attributes/destroy', [AttributeController::class, 'destroy'])->name('attributes.destroy');

    Route::post('attribute-items/store', [AttributeController::class, 'storeItem'])->name('attribute-items.store');
    Route::post('attribute-items/update', [AttributeController::class, 'updateItem'])->name('attribute-items.update');
    Route::post('attribute-items/destroy', [AttributeController::class, 'destroyItem'])->name('attribute-items.destroy');




    // Blog Routes
    Route::resource('blogs', BlogController::class);
});


Route::middleware('auth')->group(function () {
    // Developer API
    Route::get('/developer-api', [DeveloperApiController::class, 'index'])->name('developer-api.index');
    Route::post('/developer-api/generate-token', [DeveloperApiController::class, 'generateToken'])->name('developer-api.generate-token');

    /**----------------------------------------------------------------------------------------------
     * ----------------------------------------------------------------------------------------------
     * BACKEND TEMPLATE
     * ----------------------------------------------------------------------------------------------
     * ----------------------------------------------------------------------------------------------
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/change-password', [ProfileController::class, 'editPassword'])->name('password.change');
    Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Role Management
    Route::resource('roles', RoleController::class);

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/settings-update', [SettingController::class, 'update'])->name('setting.update');

    // SEO settings
    Route::get('/seo-pages',[PageSeoController::class,'index'])->name('settings.seo.index');
    Route::post('/seo-pages/{page}',[PageSeoController::class,'update'])->name('settings.seo.update');
});

require __DIR__.'/auth.php';
