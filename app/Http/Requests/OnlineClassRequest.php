<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class OnlineClassRequest extends FormRequest
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
            // 'level_id'   => 'required',
            // 'section_id'   => 'required',
            // 'subject_id'   => 'required',
            // 'name'          => 'required',
            // 'description'   => 'required',
            'start_time' => 'nullable|before:end_time',
            'end_time'   => 'nullable|after:start_time'
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
            'start_time.before' =>  'The start time must be a time before end time.',
            'end_time.after'    =>  'The end time must be a time after start time.'
        ];
    }
}
