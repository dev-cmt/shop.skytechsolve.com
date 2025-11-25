<?php
// app/Providers/PageBuilderServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PageBuilder;
use App\View\Components\SectionComponent;

class PageBuilderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PageBuilder::class, function ($app) {
            return new PageBuilder();
        });
    }

    public function boot()
    {
        // Register section components
        $this->loadViewComponentsAs('page-builder', [
            SectionComponent::class,
        ]);
    }
}
