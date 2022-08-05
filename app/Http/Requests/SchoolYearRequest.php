<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class SchoolYearRequest extends FormRequest
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
            'schoolYear' => "required|regex:/^\d+ - \d+$/i|unique:school_years,schoolYear,{$this->id},id,deleted_at,NULL",
            'start_date' => 'required|before:end_date',
            'end_date'   => 'required|after:start_date',
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
            'schoolYear' => '"School Year"',
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
             'schoolYear.regex' => 'The :attribute field format is invalid (The valid format is 2019 - 2020 etc.)',
        ];
    }
}
