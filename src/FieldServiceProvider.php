<?php

namespace R64\NovaFields;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-fields', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-fields', __DIR__.'/../dist/css/field.css');
        });

        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

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
     * Get the Nova route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'namespace' => 'R64\NovaFields\Http\Controllers',
            'domain' => config('nova.domain', null),
            'as' => 'nova.r64.api.',
            'prefix' => 'nova-r64-api',
            'middleware' => 'nova',
        ];
    }
}
