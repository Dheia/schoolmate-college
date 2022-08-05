<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\CurriculumManagement as CM;

class SubjectManagementRequest extends FormRequest
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
            // 'curriculum_id' => 
            // [
            //     'required',
            //     'numeric', 
            //     function ($attribute, $value, $fail) {
            //         // dd($value);
            //         $checkValue = CM::where('id', '=', $value)->first();

            //         if(!$checkValue) {
            //             return $fail($attribute.' value does not exist in database.');
            //         }
            //     }
            // ],
            'subject_title' => 'required',
            'subject_code'  => 'required',
            'subject_description' => 'required',
            'no_unit' => 'required|numeric|min:0|max:50',
            'price'   => 'required|numeric|min:0',
            'percent' => 'required|numeric|min:0|max:100',
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
            'curriculum' => '"curriculum"',
            'subject_code' => '"Subject Code"',
            'subject_description' => '"Subject Description"',
            'no_unit' => '"No Unit"',
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
