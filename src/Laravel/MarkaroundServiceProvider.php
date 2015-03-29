<?php
namespace Jamesflight\Markaround\Laravel;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Jamesflight\Markaround\Factory;

class MarkaroundServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('Jamesflight\Markaround\Markaround', function () {
            return Factory::create('../' . Config::get('markaround'));
        });
    }
}