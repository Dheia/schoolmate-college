<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PaymentMethodRequest as StoreRequest;
use App\Http\Requests\PaymentMethodRequest as UpdateRequest;

use App\Models\PaymentMethodCategory;
use App\Models\PaymentMethod;


/**
 * Class PaymentMethodCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PaymentMethodCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PaymentMethod');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/payment-method');
        $this->crud->setEntityNameStrings('Payment Method', 'Payment Methods');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in PaymentMethodRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */
        $this->crud->addFilter([ // select2_multiple filter
          'name' => 'payment_method_category_id',
          'type' => 'select2',
          'label'=> 'Payment Method Category'
        ], function() { // the options that show up in the select2
            return PaymentMethodCategory::all()->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'payment_method_category_id', $value);
        });

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name'
        ]);

        $this->crud->addColumn([
            'label' => 'Code',
            'type' => 'text',
            'name' => 'code'
        ]);

        $this->crud->addColumn([
            'label' => 'Method',
            'name' => 'method',
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'label' => 'Payment Action',
            'name' => 'payment_action',
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'label' => 'Category',
            'name' => 'payment_method_category_id',
            'type' => 'select',
            'entity' => 'paymentMethodCategory',
            'attribute' => 'name',
            'model' => 'App\Models\PaymentMethodCategory'
        ]);

        $this->crud->addColumn([
           'name' => "icon",
           'label' => "Icon",
           'type' => "model_function",
           'function_name' => 'getIcon'
        ]);

        $this->crud->addColumn([
            'label' => 'Fee (%)',
            'type' => 'text',
            'name' => 'fee'
        ]);

        $this->crud->addColumn([
            'label' => 'Minimum Fee',
            'type' => 'text',
            'name' => 'minimum_fee'
        ]);

        $this->crud->addColumn([
           'name' => 'fixed_amount', // The db column name
           'label' => "Fixed Amount", // Table column heading
           'type' => "number",
           'prefix' => "₱ ",
           // 'suffix' => " EUR",
           'decimals' => 2,
           'dec_point' => '.',
           'thousands_sep' => ',',
           // decimals, dec_point and thousands_sep are used to format the number;
           // for details on how they work check out PHP's number_format() method, they're passed directly to it;
           // https://www.php.net/manual/en/function.number-format.php
        ]);

        $this->crud->addColumn([
           'name' => 'description', // The db column name
           'label' => "Description", // Table column heading
           'type' => "markdown"
        ]);

        $this->crud->addColumn([
            'name'    => 'active',
            'label'   => 'Active',
            'type'    => 'boolean',
            'options' => [0 => 'Inactive', 1 => 'Active'], // optional
            'wrapper' => [
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) {
                    if ($column['text'] == 'Active') {
                        return 'badge badge-success';
                    }
        
                    return 'badge badge-default';
                },
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label' => 'Name',
            'name' => 'name',
            'type' => 'text',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6 col-xs-12' ]
        ]);

        $this->crud->addField([
            'label' => 'Code',
            'name' => 'code',
            'type' => 'text',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6 col-xs-12' ]
        ]);

        $this->crud->addField([
            'label' => 'Payment Method',
            'name' => 'method',
            'type' => 'text',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6 col-xs-12' ]
        ]);

        $this->crud->addField([
            'label' => 'Payment Action',
            'name' => 'payment_action',
            'type' => 'text',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6 col-xs-12' ]
        ]);

        $this->crud->addField([
            'label' => 'Category',
            'name' => 'payment_method_category_id',
            'type' => 'select',
            'entity' => 'paymentMethodCategory',
            'attribute' => 'name',
            'model' => 'App\Models\PaymentMethodCategory',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6 col-xs-12' ]
        ]);

        $this->crud->addField([
            'label' => "Icon",
            'name' => 'icon',
            'type' => 'icon_picker',
            'iconset' => 'fontawesome', // options: fontawesome, glyphicon, ionicon, weathericon, mapicon, octicon, typicon, elusiveicon, materialdesign
            'wrapperAttributes' => [ 'class' => 'form-group col-md-6 col-xs-12' ]
        ]);

        $this->crud->addField([
            'name'  => 'logo',
            'label' => 'Logo',
            'type'  => 'browse'
        ]);

        $this->crud->addField([
            'label' => 'Fee (%)',
            'type' => 'text',
            'name' => 'fee',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4 col-xs-12' ]
        ]);

        $this->crud->addField([
            'label' => 'Minimum Fee',
            'type' => 'text',
            'name' => 'minimum_fee',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4 col-xs-12' ]
        ]);

        $this->crud->addField([
            'label' => 'Fixed Amount (PHP)',
            'type' => 'number',
            'name' => 'fixed_amount',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4 col-xs-12' ]
        ]);

        $this->crud->addField([
            'name'  => 'active',
            'label' => 'Active',
            'type'  => 'checkbox'
        ]);

        // $this->crud->removeFields(['code']);
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }




      // ------------------------------------
     // --------- CUSTOMM FUNCTION ---------
    // ------------------------------------

    public function getPaymentMethodList()
    {
        $payment_methods = $this->crud->model::all();
        return response()->json($payment_methods);
    }
    
    /** 
     * GET PAYMENT METHOD
     */
    public function getPaymentMethod($id)
    {
        $paymentMethod = PaymentMethod::active()->findOrFail($id);
        return $paymentMethod;
    }


}
