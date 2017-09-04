<?php

namespace Modules\ModuleControl\Facades;

use Illuminate\Support\Facades\Facade;

class ConfigOverride extends Facade{
    protected static function getFacadeAccessor()
    {
        return \Modules\ModuleControl\Services\ConfigOverrideService::class;
    }
}
