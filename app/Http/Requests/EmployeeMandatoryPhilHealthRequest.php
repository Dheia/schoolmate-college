<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeMandatoryPhilHealthRequest extends FormRequest
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
            // "monthly_basic_salary_min" => 'required|numeric',
            // "monthly_basic_salary_max" => 'required|numeric',
            // "monthly_premium_min" => 'required|numeric',
            // "monthly_premium_max" => 'required|numeric',
            // "personal_share_min" => 'required|numeric',
            // "personal_share_max" => 'required|numeric',
            // "employer_share_min" => 'required|numeric',
            // "employer_share_max" => 'required|numeric',
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
