<?php

namespace Pingu\Forms\Providers;

use Illuminate\Database\Eloquent\Factory;
use Pingu\Core\Support\ModuleServiceProvider;
use Pingu\Forms\Console\MakeFormCommand;
use Pingu\Forms\Macros;
use Themes;

class FormsServiceProvider extends ModuleServiceProvider
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
        $this->registerFactories();
        $this->registerCommands();
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'forms');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        // $this->registerFormMacros();
        $this->registerRules();        

        \Asset::container('modules')->add('forms-js', 'module-assets/Forms/js/Forms.js');
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
     * Register own Macros class
     */
    public function registerFormMacros()
    {
        $this->app->singleton('form', function ($app) {
            $form = new Macros($app['html'], $app['url'], $app['view'], $app['session.store']->token());
            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * Extends validator with custom rules
     */
    public function registerRules()
    {
        /**
         * url rule that check if the url is an internal get url
         * if not starting with http. route names are also supported
         */
        \Validator::extend('valid_url', function ($attribute, $value, $parameters, $validator) {
            if($value and substr($value, 0, 4) != 'http' and !route_exists($value)) return false;
            return true;
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'forms'
        );
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
     * Registers console commands
     */
    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeFormCommand::class,
            ]);
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
