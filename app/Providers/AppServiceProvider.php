<?php

namespace App\Providers;

use App\Http\Middleware\SanitizeInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
//        Livewire::addPersistentMiddleware([
//            SanitizeInput::class,
//        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Model::preventLazyLoading(! app()->isProduction());
    }
}
