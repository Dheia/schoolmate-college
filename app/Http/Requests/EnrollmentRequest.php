<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Department;

class EnrollmentRequest extends FormRequest
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
        // return [];
        $rules = [
            'studentnumber'             => 'required|numeric|exists:students,studentnumber',
            'tuition_id'                => 'required|numeric|exists:tuitions,id',
            'school_year_id'            => 'required|numeric|exists:school_years,id',
            'department_id'             => 'required|numeric|exists:departments,id',
            'level_id'                  => 'required|numeric|exists:year_managements,id',
            // 'track_id'                  => 'required|numeric',
            'curriculum_id'             => 'required|numeric|exists:curriculum_managements,id',
            'term_type'                 => 'required',
            // 'section_id'                => 'required|numeric',
            'commitment_payment_id'     => 'required|numeric|exists:commitment_payments,id',
        ];

        // CHECK DEPARTMENT IF TRACK HAS CHECKED
        $departmentWithTrack = Department::where('id', request()->department_id)->first();
        if($departmentWithTrack !== null) {
            $departmentWithTrack->with_track ? $rules['track_id'] = 'required|numeric|exists:track_managements,id' : 'nullable';
        }
        return $rules;
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
            'studentnumber.required' => 'Please select a student',
            'school_year_id.required' => 'Please select a school year',
            'department_id.required' => 'Please select a department',
            'level_id.required'      => 'Please select a level',
            'commitment_payment_id.required'  => 'Please select a commitment payment',
            'tuition_id.required'    => 'Please select a tuition form',
            'track_id.required'      => 'Please select a strand',
            'curriculum_id.required' => 'Please select a curriculum',
        ];
    }
}
