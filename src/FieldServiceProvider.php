<?php

namespace R64\NovaFields;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use R64\NovaFields\NovaTranslationsLoader\LoadsNovaTranslations;
use R64\NovaFields\Http\Middleware\Authorize;



class FieldServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-fields', __DIR__.'/../dist/js/field.js');
        });

        Nova::router(['nova', Authorize::class], config('nova-filemanager.path', 'filemanager'))
            ->group(__DIR__ . '/../routes/inertia.php');
            
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });

        $this->loadTranslations(__DIR__ . '/../resources/lang', 'nova-fields-multiselect', true);


        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('filemanager.php'),
        ], 'filemanager-config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-fields');

        $this->app->booted(function () {
            $this->routeConfiguration();
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
