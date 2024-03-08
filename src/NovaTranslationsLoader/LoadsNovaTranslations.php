<?php

namespace R64\NovaFields\NovaTranslationsLoader;

use Exception;
use Laravel\Nova\Nova;
use Illuminate\Support\Str;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\File;

trait LoadsNovaTranslations
{
    protected $packageTranslationsDir;
    protected $packageName;
    protected $publishTranslations;

    /**
     * Loads translations into the Nova system.
     *
     * @param string $packageTranslationsDir The directory for the packages' translation files.
     * @param string $packageName The name of the current package (ie 'nova-menu-builder').
     * @param boolean $publishTranslations Whether to also automatically make translations publishable.
     * @return null
     **/
    protected function loadTranslations($packageTranslationsDir, $packageName, $publishTranslations = true)
    {
        $packageTranslationsDir = $packageTranslationsDir ?? __DIR__ . '/../resources/lang';
        $packageTranslationsDir = rtrim($packageTranslationsDir, '/');
        $packageName = trim($packageName);
        $this->translations($packageTranslationsDir, $packageName, $publishTranslations);
    }

    private function translations($pckgTransDir, $pckgName, $publish)
    {
        if (app()->runningInConsole() && $publish) {
            $this->publishes([$pckgTransDir => resource_path("lang/vendor/{$pckgName}")], 'translations');
            return;
        }

        if (!method_exists(Nova::class, 'translations')) throw new Exception('Nova::translations method not found, please ensure you are using the correct version of Nova.');

        Nova::serving(function (ServingNova $event) use ($pckgTransDir, $pckgName) {
            $locale = app()->getLocale();
            $fallbackLocale = config('app.fallback_locale');

            // Load Laravel translations
            $this->loadLaravelTranslations($pckgTransDir, $pckgName);

            // Attempt to load Nova translations
            if ($this->loadNovaTranslations($locale, 'project', $pckgTransDir, $pckgName)) return;
            if ($this->loadNovaTranslations($locale, 'local', $pckgTransDir, $pckgName)) return;
            if ($this->loadNovaTranslations($fallbackLocale, 'project', $pckgTransDir, $pckgName)) return;
            if ($this->loadNovaTranslations($fallbackLocale, 'local', $pckgTransDir, $pckgName)) return;
            $this->loadNovaTranslations('en', 'local', $pckgTransDir, $pckgName);
        });
    }

    private function loadNovaTranslations($locale, $from, $packageTranslationsDir, $packageName)
    {
        $translationsFile = $this->getTranslationsFile($locale, $from, $packageTranslationsDir, $packageName);
        if ($translationsFile) {
            Nova::translations($translationsFile);
            return true;
        }
        return false;
    }

    private function loadLaravelTranslations($pckgTransDir, $pckgName)
    {
        $locale = app()->getLocale();
        $fbLocale = app()->getFallbackLocale();

        $this->loadLaravelTranslationsForLocale($locale, $pckgTransDir, $pckgName);
        $this->loadLaravelTranslationsForLocale($fbLocale, $pckgTransDir, $pckgName);
        $this->loadLaravelTranslationsForLocale('en', $pckgTransDir, $pckgName);
    }

    private function loadLaravelTranslationsForLocale($locale, $pckgTransDir, $pckgName)
    {
        $projectTransFile = $this->getTranslationsFile($locale, 'project', $pckgTransDir, $pckgName);
        $packageTransFile = $this->getTranslationsFile($locale, 'local', $pckgTransDir, $pckgName);
        if (!isset($projectTransFile) && !isset($packageTransFile)) return false;

        $translations = [];
        $projectTranslations = isset($projectTransFile) ? json_decode(file_get_contents($projectTransFile), true) : [];
        $packageTranslations = isset($packageTransFile) ? json_decode(file_get_contents($packageTransFile), true) : [];
        $translations = array_merge($packageTranslations, $projectTranslations);

        $translations = collect($translations)->filter(function ($value, $key) {
            return Str::contains($key, '.');
        })->toArray();

        app('translator')->addLines($translations, $locale);

        return true;
    }

    private function getTranslationsFile($locale, $from, $packageTranslationsDir, $packageName)
    {
        if (empty($locale)) return null;

        $fileDir = $from === 'local'
            ? $packageTranslationsDir
            : resource_path("lang/vendor/{$packageName}");

        $filePath = "$fileDir/{$locale}.json";

        $localeFileExists = File::exists($filePath);
        if (!$localeFileExists) return null;

        // Test if file is valid JSON
        $fileContents = json_decode(file_get_contents($filePath), true);

        return !empty($fileContents) ? $filePath : null;
    }
}
