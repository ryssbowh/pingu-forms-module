<?php

namespace Modules\Forms\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Themes;

class FormsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('forms.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'forms'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $themePaths = $this->app->make('view.finder')->getThemesPublishPaths('forms');

        $sourcePath = __DIR__.'/../Resources/views';

        foreach($themePaths as $path => $namespace){
            $this->publishes([
                $sourcePath => $path
            ],$namespace);
        }

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/forms';
        }, \Config::get('view.paths')), [$sourcePath]), 'forms');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/forms');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'forms');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'forms');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
