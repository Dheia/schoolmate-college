<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ParentUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'name' => 'required|min:5|max:255'
            'firstname'             =>  'required|string',
            'middlename'            =>  'nullable|string',
            'lastname'              =>  'required|string',            
            'photo'                 =>  'nullable',
            'gender'                =>  'required|in:Male,Female',
            'birthdate'             =>  'nullable',
            'citizenship'           =>  'nullable',
            'street_number'         =>  'nullable',
            'barangay'              =>  'nullable',
            'city_municipality'     =>  'nullable',
            'province'              =>  'nullable',
            'country'               =>  'nullable',
            'mobile'                =>  'required|digits:11',
            'telephone'             =>  'nullable|numeric',
            'email'                 =>  'required|unique:parent_users,email,'.$this->id.',id',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
