<?php

namespace OptimistDigital\NovaSimpleRepeatable;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use OptimistDigital\NovaTranslationsLoader\LoadsNovaTranslations;

class SimpleRepeatableServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;

    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('simple-repeatable', __DIR__ . '/../dist/js/field.js');
        });

        $this->loadTranslations(__DIR__ . '/../resources/lang', 'nova-simple-repeatable-field');
    }

    public function register()
    {
        //
    }
}
