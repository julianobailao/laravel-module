<?php

namespace Modules\ModuleControl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ModuleControl\Rules\ValidRouteUri;
use Modules\ModuleControl\Rules\ValidModuleName;
use Modules\ModuleControl\Rules\ValidRouteMethod;

class ActionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'action_group_id' => 'required|exists:action_groups,id',
            'title' => sprintf(
                'required|unique:actions,title,%s,id',
                isset($this->action->id) ? $this->action->id : 0
            ),
            'rules' => 'required|array|min:1',
            'rules.*.module_name' => [
                'required',
                new ValidModuleName()
            ],
            'rules.*.route_uri' => [
                'required',
                new ValidRouteUri()
            ],
            'rules.*.route_method' => [
                'required',
                new ValidRouteMethod()
            ]
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
