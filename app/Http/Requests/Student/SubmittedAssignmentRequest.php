<?php

namespace App\Http\Requests\Student;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class SubmittedAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'answer'   => 'required_without:files',
            'files'     => 'required_without:answer',
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
            'answer.required_without'  => 'The answer field is required.',
            'files.required_without'  => 'Please upload a file to submit your assignment.'
        ];
    }
}
