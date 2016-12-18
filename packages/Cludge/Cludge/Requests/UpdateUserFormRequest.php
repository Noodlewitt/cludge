<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Repositories\OrganisationRepository;
use Input;

class UpdateUserFormRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Sanitize the input values
     *
     * @return array
     */
    public function modifyInput(array $data)
    {
        $user = auth()->user();

        if ($user->can('administer_roles') && ! array_key_exists('roles', $data)) {
            $data['roles'] = [];
        }

        if ($user->can('administer_organisations') && ! array_key_exists('organisations', $data)) {
            $data['organisations'] = [];
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users,email,'.$this->route('users'),
            'password'              => '',
            'password_confirmation' => 'same:password',
        ];

        $user = auth()->user();

        if ($user->can('administer_roles')) {
            $rules['roles'] = 'required|exists:roles,id';
        }

        if ($user->can('administer_organisations')) {
            $rules['organisations'] = 'exists:organisations,id';
        }

        return $rules;
    }
}
