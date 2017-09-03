<?php

namespace Modules\ModuleControl\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $noRequiredOnUpdate = ! isset($this->user->id) ? 'required|' : null;

        return [
            'name' => 'required',
            'password' => sprintf('%smin:8', $noRequiredOnUpdate),
            'user_group_id' => sprintf('%sexists:user_groups,id', $noRequiredOnUpdate),
            'email' => sprintf(
                'required|email|unique:users,email,%s,id',
                isset($this->user->id) ? $this->user->id : 0
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
