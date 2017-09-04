<?php

namespace Modules\ModuleControl\Rules;

use Route;
use Illuminate\Contracts\Validation\Rule;
use Modules\ModuleControl\Facades\Module;

class ValidRouteMethod implements Rule
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
        return in_array($value, [
            'GET', 'POST', 'PUT', 'DELETE'
        ]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute not is a valid route method.';
    }
}
