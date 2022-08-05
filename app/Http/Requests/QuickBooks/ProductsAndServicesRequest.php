<?php

namespace App\Http\Requests\QuickBooks;

use Illuminate\Foundation\Http\FormRequest;

class ProductsAndServicesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->Type === 'Inventory') {
            return [
                'Name'              => 'required|string|max:100',
                'QtyOnHand'         => 'numeric',
                'UnitPrice'         => 'numeric',
                'IncomeAccountRef'  => 'required',
                'AssetAccountRef'   => 'required',
                'ExpenseAccountRef' => 'required',
                'InvStartDate'      => 'required|date',
                'Sku'               => 'string',
            ];
        }

        else if($this->Type === 'NonInventory') {
            return [
                'Name'              => 'required|string|max:100',
                // 'QtyOnHand'         => 'numeric',
                // 'UnitPrice'         => 'numeric',
                // 'IncomeAccountRef'  => 'required',
                // 'AssetAccountRef'   => 'required',
                // 'ExpenseAccountRef' => 'required',
                // 'InvStartDate'      => 'required|date',
                'Sku'               => 'string',
            ];
        }

        else if($this->Type === 'Service') {

            return [
                'Name'              => 'required|string|max:255',
                'Sku'               => 'string',
            ];
        }

        else if($this->Type === 'Bundle') {
            return [
                'Name'                 => 'required|string|max:100',
                'Description'          => 'string|max:200',
                // 'IncomeAccountRef'  => 'required',
                // 'AssetAccountRef'   => 'required',
                // 'ExpenseAccountRef' => 'required',
                // 'InvStartDate'      => 'required|date',
                'Sku'               => 'string',
            ];
        }

        else {
            dd("INVALID CATEGORY TYPE");
        }
    }
}
