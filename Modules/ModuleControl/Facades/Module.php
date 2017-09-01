<?php

namespace Modules\ModuleControl\Facades;

use Illuminate\Support\Facades\Facade;

class Module extends Facade{
    protected static function getFacadeAccessor()
    {
        return \Modules\ModuleControl\Services\ModuleService::class;
    }
}
