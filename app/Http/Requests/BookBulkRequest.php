<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookBulkRequest extends FormRequest
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
        // $data       =   json_decode(request('data'));
        // $quantity   =   $data->quantity;
        // $title      =   $data->title;
        // $asd        =   [];
        // $string = [];
        // for ($i=1; $i <= $quantity ; $i++) {  
        //     $asd[] = [
        //     'isbn'.$i => [
        //         'nullable',
        //         Rule::unique('books', 'isbn')->where('title', '!=',  $data->title),
        //     ],
        //     ];
        // }
        // return collect($asd)->toArray();
        return [
                    //
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
