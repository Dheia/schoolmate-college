<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReferralRequest as StoreRequest;
use App\Http\Requests\ReferralRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\Student;
use App\Models\Referral;

/**
 * Class ReferralCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReferralCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Referral');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/referral');
        $this->crud->setEntityNameStrings('referral', 'referrals');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $this->crud->denyAccess(['update', 'delete']);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'label' => 'Student',
            'type' => 'text',
            'name' => 'student.full_name'
        ]);

        $this->crud->addColumn([
            'label' => 'Medium',
            'type' => 'text',
            'name' => 'medium'
        ]);

        $this->crud->addColumn([
            'label' => 'Referred by',
            'type' => 'text',
            'name' => 'referred_by'
        ]);

        $this->crud->addColumn([
            'label' => 'Contact',
            'type' => 'text',
            'name' => 'contact'
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([ // select_from_array
            'name'      =>  'student_id',
            'label'     =>  'Student',
            'type'      =>  'select2_from_array',
            'options'   =>  Student::whereNotIn('id', Referral::get()->pluck('student_id'))
                                ->orderBy('lastname')
                                ->orderBy('firstname')
                                ->orderBy('middlename')
                                ->get()
                                ->pluck('fullname_last_first', 'id'),
            'allows_null' => false,
            'wrapperAttributes' => [
                'id' => 'sttudent_id',
                'class' => 'form-group col-md-12 col-xs-12'
            ]
        ]);

        $this->crud->addField([ // select_from_array
            'name'    => 'medium',
            'label'   => 'Medium',
            'type'    => 'select_from_array',
            'options' => [
                'social media'  => 'Social Media', 
                'search engine' => 'Search Engine',
                'referred'      => 'Referred',
                'other'         => 'Other'
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'id' => 'medium',
                'class' => 'form-group col-md-12 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Referred by',
            'type' => 'text',
            'name' => 'referred_by',
            'wrapperAttributes' => [
                'id' => 'referred_by',
                'class' => 'form-group col-md-12 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Contact',
            'type' => 'text',
            'name' => 'contact',
            'wrapperAttributes' => [
                'id' => 'contact',
                'class' => 'form-group col-md-12 col-xs-12',
                'style' => 'display: none;'
            ]
        ]);

        $this->crud->addField([
            'label' => '',
            'type'  => 'referral.script',
            'name'  => 'referral_script'
        ]);
        
        // add asterisk for fields that are required in ReferralRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
}
