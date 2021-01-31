<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['cart.top_cart'], function($view){
            $view->with('cart_products', Cart::getContent()->sortByDesc('id'));
        });

        view()->composer('widgets.new_products', function($view){
            $view->with('new_products', Product::all()->sortByDesc('id'));
        });
    }
}
