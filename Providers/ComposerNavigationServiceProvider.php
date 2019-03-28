<?php

namespace App\Modules\Basic\Navigation\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerNavigationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
         view()->composer(
             'partial.back.navigation',
             'App\Modules\Basic\Navigation\Utils\Composers\Back\NavigationComposer'
         );

         view()->composer(
             'partial.back.header',
             'App\Modules\Basic\Navigation\Utils\Composers\Back\HeaderComposer'
         );

         // ------------------------------------------------------------------------------------------------------------

         view()->composer(
             'partial.front.navigation',
             'App\Modules\Basic\Navigation\Utils\Composers\Front\NavigationComposer'
         );

         view()->composer(
             'partial.front.header',
             'App\Modules\Basic\Navigation\Utils\Composers\Front\HeaderComposer'
         );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            //
        ];
    }
}
