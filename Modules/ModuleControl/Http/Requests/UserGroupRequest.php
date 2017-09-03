<?php

namespace Modules\ModuleControl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ModuleControl\Rules\ValidRouteUri;
use Modules\ModuleControl\Rules\ValidModuleName;
use Modules\ModuleControl\Rules\ValidRouteMethod;

class UserGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => sprintf(
                'required|unique:user_groups,title,%s,id',
                isset($this->userGroup->id) ? $this->userGroup->id : 0
            ),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
