<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeMandatorySSSRequest extends FormRequest
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
            'range_of_compensation_min' => 'required|numeric',
            'range_of_compensation_max' => 'required|numeric',
            'monthly_salary_credit' => 'required|numeric',
            'social_security_er' => 'required|numeric',
            'social_security_ee' => 'required|numeric',
            'ec_er' => 'required|numeric',
            // 'total_contribution_er' => 'required|numeric',
            // 'total_contribution_ee' => 'required|numeric',
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
