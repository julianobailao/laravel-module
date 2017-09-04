<?php

namespace Modules\ModuleControl\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\ModuleControl\Facades\Module;

class ValidModuleName implements Rule
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
        return Module::checkModuleExists($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute not is a valida module name.';
    }
}
