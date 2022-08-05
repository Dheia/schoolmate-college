<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ItemInventoryRequest extends FormRequest
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
            'code' => 'required|min:2|max:100|unique:item_inventories,code,' . $this->id,
            'name' => 'required|min:2|max:255',
            // 'barcode' => '',
            'description' => '',
            'quantity_on_hand' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'cost_price' => 'required|numeric',
        ];

        // 'code','name','barcode','description','quantity_on_hand','sale_price','cost_price'
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
