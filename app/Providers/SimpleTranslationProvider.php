<?php namespace App\Providers;

use App\Models\LanguageLine;
use App\Utilities\TranslationUtility;
use Illuminate\Support\Facades\Cache;
use Illuminate\Translation\TranslationServiceProvider;

class SimpleTranslationProvider extends TranslationServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLoader();

        $this->app->singleton('translator', function($app)
        {
            $loader = $app['translation.loader'];

            $locale = $app['config']['app.locale']; // en
            $trans = new TranslationUtility($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);
            return $trans;
        });
    }


    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
         Cache::rememberForever('language', function () {
            return LanguageLine::all();
        });

    }
}
