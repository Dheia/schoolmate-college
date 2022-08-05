<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookModalRequest extends FormRequest
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
        // dd($this->request->get('book_title'));
        return [
            'modal_accession_number' => 'required|unique:books,accession_number,'.$this->id.',id',
            'code'             => 'nullable',
            'modal_isbn'             => [
                                            'nullable',
                                            Rule::unique('books', 'isbn')->where('title', '!=', $this->request->get('title'))
                                        ],
            'modal_call_number'      => [
                                            'nullable',
                                            // Rule::unique('books', 'call_number')->where('title', '!=', $this->request->get('book_title'))
                                        ]
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
