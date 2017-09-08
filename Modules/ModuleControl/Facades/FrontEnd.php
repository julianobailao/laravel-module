<?php

namespace Modules\ModuleControl\Facades;

use Illuminate\Support\Facades\Facade;

class FrontEnd extends Facade{
    protected static function getFacadeAccessor()
    {
        return \Modules\ModuleControl\Services\FrontEndService::class;
    }
}
