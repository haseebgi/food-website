<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
public function register(): void
{
   
}

    /**
     * Bootstrap any application services.
     */
   /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    // Force standard UTF8 to fit older MySQL index limits
    config(['database.connections.mysql.charset' => 'utf8']);
    config(['database.connections.mysql.collation' => 'utf8_unicode_ci']);
    
    Schema::defaultStringLength(191);

    // Bootstrap pagination design ko fix karne ke liye ye line add karein
    \Illuminate\Pagination\Paginator::useBootstrapFive();
}

    
}