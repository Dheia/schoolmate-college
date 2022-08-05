<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class AssetInventoryRequest extends FormRequest
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
            'name'          => 'required|min:1|max:255|string',
            'description'   => 'required|min:1|max:255',
            'serialno'      => 'required|min:1|max:255',
            'remarks'       => 'required|min:1|max:255',
            'condition'     => 'required|min:1|max:255',
            'building_id'   => 'required|numeric|exists:buildings,id',
            'room_id'       => 'required|numeric|exists:rooms,id'
            // 'user_id' => 'required|min:1|max:255',
        ];

        // name','description','serialno','remarks','condition','user_id','updated_by','room_id','items'
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
