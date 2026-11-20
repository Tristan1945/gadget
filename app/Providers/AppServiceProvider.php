<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.topbar', function ($view): void {
            $view->with([
                'lowStockCount' => Product::where('stock', '<=', 5)->count(),
                'topLowStockItems' => Product::with('category')
                    ->where('stock', '<=', 5)
                    ->orderBy('stock')
                    ->take(4)
                    ->get(),
            ]);
        });
    }
}
