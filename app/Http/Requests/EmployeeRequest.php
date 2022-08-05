<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'employee_id' => 'required|numeric|unique:employees,employee_id,'. $this->id,
            // 'employee_id' => 'required|numeric',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'position' => 'required|string',
            // 'date' => 'required|date',
            'date_hired' => 'required|date',
            'address1' => 'required|string',
            // 'address2' => '',
            'city' => 'required|string',
            // 'region' => 'required|string',
            'province' => 'required|string',
            'country' => 'required|string',
            // 'telephone' => 'required',
            // 'domestic_profile' => '',
            // 'age' => 'required|numeric',
            // 'gender' => 'required',
            // 'civil_status' => 'required',
            'date_of_birth' => 'required|date',
            // 'religion' => '',
            // 'primary' => '',
            // 'secondary' => '',
            // 'tertiary' => '',
            // 'post_graduate' => '',
            // 'employment_history' => '',
            // 'salary' => '',
            // 'currently_employed' => 'required',
            // 'time_start' => '',
            // 'referral' => 'required|boolean',
            // 'name_of_referer' => '',
            // 'relationship' => '',
            // 'references' => '',
            // 'medical_condition' => '',
            // 'past_illness' => '',
            // 'present_illness' => '',
            // 'allergies' => '',
            // 'minor_illness' => '',
            // 'family_physician' => '',
            // 'hospital_reference' => '',
            // 'organ_donor' => '',
            // 'blood_type' => '',
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
