<?php
/**
 * User: Stefan Riedel <sr@laravel-blog.de>
 * Date: 30.07.15
 * Time: 10:59
 * Project: sandbox
 */

namespace Laravelblog\Sandbox;

use Barryvdh\Debugbar\Facade as DebugbarFacade;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Elasticsearch\Client;
use Illuminate\Support\ServiceProvider;
use Laravelblog\Sandbox\Lib\Helper;
use Laravelblog\Sandbox\Lib\Price;
use Laravelblog\Sandbox\Repositories\UserRepository;
use Menu\MenuServiceProvider;
use Zizaco\Entrust\EntrustFacade;
use Laravelblog\Sandbox\Facades\Helper as HelperFacade;
use Laravelblog\Sandbox\Facades\Price as PriceFacade;
use Zizaco\Entrust\EntrustServiceProvider;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;


class SandboxServiceProvider extends ServiceProvider
{


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->_bindElasticsearchClient();
        $this->_bindEntrust();
        $this->_bindDebugbar();
        $this->_bindHelper();
        $this->_bindPrice();
        $this->_registerMenu();
        $this->_registerIdeHeloer();

    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '../config/sandbox.php' => config_path('sandbox.php'),
        ]);
    }

    protected function _bindElasticsearchClient()
    {
        $this->app->bind(Client::class, function () {
            return new Client(config('sandbox.elasticsearch.config'));
        });
    }

    protected function _bindEntrust()
    {
        $this->app->register(EntrustServiceProvider::class);
        $this->app->booting(function () {
            $oLoader = \Illuminate\Foundation\AliasLoader::getInstance();
            $oLoader->alias('Entrust', EntrustFacade::class);
        });
    }

    protected function _bindDebugbar()
    {
        $this->app->register(DebugbarServiceProvider::class)
        $this->app->booting(function () {
            $oLoader = \Illuminate\Foundation\AliasLoader::getInstance();
            $oLoader->alias('Debugbar', DebugbarFacade::class);
        });
    }

    protected function _bindHelper()
    {
        $this->app['helper'] = $this->app->share(function () {
            return new Helper(\App::make(UserRepository::class));
        });

        $this->app->booting(function () {
            $oLoader = \Illuminate\Foundation\AliasLoader::getInstance();
            $oLoader->alias('Helper', HelperFacade::class);
        });
    }

    protected function _bindPrice()
    {
        $this->app['price'] = $this->app->share(function () {
            return new Price();
        });

        $this->app->booting(function () {
            $oLoader = \Illuminate\Foundation\AliasLoader::getInstance();
            $oLoader->alias('Price', PriceFacade::class);
        });
    }

    protected function _registerMenu()
    {
        $this->app->register(MenuServiceProvider::class);
    }

    protected function _registerIdeHeloer()
    {
        $this->app->register(IdeHelperServiceProvider::class);
    }

}