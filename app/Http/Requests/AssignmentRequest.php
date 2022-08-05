<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AssignmentRequest extends FormRequest
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
        $dateToday = Carbon::today()->toDateString();
        return [
            // 'name' => 'required|min:5|max:255'
            // 'online_class_id'   => 'required',
            // 'type'              => 'required|in(["submission", "essay"])',
            // 'title'             => 'required',
            // 'instructions'      => 'required',
            // 'rubrics'           => 'required',
            // 'due_date'          => 'required|date|after_or_equal:'.$dateToday,
            // 'employee_id'       => 'required'
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
