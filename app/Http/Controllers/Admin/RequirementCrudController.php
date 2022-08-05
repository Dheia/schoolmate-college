<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RequirementRequest as StoreRequest;
use App\Http\Requests\RequirementRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class RequirementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RequirementCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Requirement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/requirement');
        $this->crud->setEntityNameStrings('requirement', 'requirements');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'name' => 'fullname',
            'type' => 'text',
            'label' => 'Name'
        ]);
        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in RequirementRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addColumn([
            'name' => 'studentnumber',
            'type' => 'text',
            'label' => 'Student No.',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

        $this->crud->addColumn([
            'name' => 'recommendation_is_uploaded',
            'type' => 'check',
            'label' => 'Recommendation Letter'
        ]);

        $this->crud->addColumn([
            'name' => 'fullname',
            'type' => 'text',
            'label' => 'Name'
        ]);

        $this->crud->addField([
            'label'         => 'Student Number',
            'name'          => 'studentnumber',
            'type'          => 'hidden',
            'attributes'    => [
                'id' => 'studentNumber'
            ]
        ]);

        $this->crud->addField([
            'name' => 'searchStudent',
            'type' => 'searchStudent',
            'label' => '',
            'attributes' => [
                'id' => 'searchInput',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)',
            ],
        ])->beforeField('studentnumber');


        $this->crud->addField(
            [   // Upload
                'name' => 'file_recommendation_form',
                'label' => 'Recommendation Form',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_recommendation_form',
                'label' => 'Recom. Form',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_good_moral',
                'label' => 'Good Moral',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_good_moral',
                'label' => 'Good Moral',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_report_card',
                'label' => 'Report Card',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_report_card',
                'label' => 'Report Card',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_birth_certificate',
                'label' => 'Birth Certificate',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_birth_certificate',
                'label' => 'Birth Certificate',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_medical_certificate',
                'label' => 'Medical Certificate',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_medical_certificate',
                'label' => 'Medical Certificate',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_id_passport',
                'label' => 'Passport',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_id_passport',
                'label' => 'Passport',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_guardian1_id',
                'label' => 'Guardian ID 1',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_guardian1_id',
                'label' => 'Guardian ID 1',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_guardian2_id',
                'label' => 'Guardian ID 2',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_guardian2_id',
                'label' => 'Guardian ID 2',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );


        $this->crud->addField(
            [   // Upload
                'name' => 'file_guardian1_agreement',
                'label' => 'Guardian 1 Agreement ',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_guardian1_agreement',
                'label' => 'Guardian 1 Agreement',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_guardian2_agreement',
                'label' => 'Guardian 2 Agreement ',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_guardian2_agreement',
                'label' => 'Guardian 2 Agreement ',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_visa',
                'label' => 'Visa',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_visa',
                'label' => 'Visa',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_alien_certificate',
                'label' => 'Alien Certificate',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_alien_certificate',
                'label' => 'Alien Certificate',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'file_ssp',
                'label' => 'SSP Certificate',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'file_ssp',
                'label' => 'SSP Certificate',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );

        $this->crud->addField(
            [   // Upload
                'name' => 'other',
                'label' => 'Other',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'public' 
            ]
        );

        $this->crud->addColumn(
            [   // Upload
                'name' => 'other',
                'label' => 'Other',
                'type' => 'uploadRequirements.upload_multiple_boolean'
            ]
        );


        $this->crud->addField(
            [   // Upload
                'name' => 'uploaded_by',
                'type' => 'hidden',
                'value' => backpack_auth()->user()->id
            ]
        );




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
}
