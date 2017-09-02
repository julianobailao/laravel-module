<?php

namespace Modules\ModuleControl\Rules;

use Route;
use Illuminate\Contracts\Validation\Rule;
use Modules\ModuleControl\Facades\Module;

class ValidRouteUri implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $routes = collect(Route::getRoutes())
            ->filter(function ($route) use ($value) {
                return $route->uri == $value;
            });

        return $routes->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute route not exists.';
    }
}
