<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeEncodeGradeScheduleRequest extends FormRequest
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
            'employee_id' => 'required',
            'school_year_id' => 'required',
            'department_id' => 'required',
            'term_type' => 'required',
            'level_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
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
            'department_id.required' => 'The department field is required.',
            'level_id.required' => 'The level  field is required.',
            'section_id.required' => 'The section field is required.',
            'subject_id.required' => 'The subject field is required.',
        ];
    }
}
