<?php
namespace Jamesflight\Markaround\Laravel;

use Illuminate\Support\Facades\Facade;

class Markaround extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Jamesflight\Markaround\Markaround';
    }
}
