<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PageSeoController;
use App\Http\Controllers\PricingPlanController;
use App\Http\Controllers\DeveloperApiController;


Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/page/frodly', [HomeController::class, 'pageFrodly'])->name('page.frodly'); // Not used
Route::get('/get/frodly', [HomeController::class, 'getFrodly'])->name('get.frodly');

// Admin dashboard
Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // Tags Routes
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('tags/store', [TagController::class, 'store'])->name('tags.store');
    Route::post('tags/update', [TagController::class, 'update'])->name('tags.update');
    Route::delete('tags/{id}/delete', [TagController::class, 'destroy'])->name('tags.destroy');
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
