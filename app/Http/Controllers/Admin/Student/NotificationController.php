<?php

namespace App\Http\Controllers\Admin\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function __construct ($crud, $tab)
    {
        $crud->crud->addField([
            'label'             => 'Father Email',
            'name'              => 'father_email',
            'type'              => 'email',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab'               => $tab,
        ]);

        $crud->crud->addField([
            'label'             => 'Mother Email',
            'name'              => 'mother_email',
            'type'              => 'email',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab'               => $tab,
        ]);

        $crud->crud->addField([
            'label'             => 'Legal Guardian Email',
            'name'              => 'legal_guardian_email',
            'type'              => 'email',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab'               => $tab,
        ]);

        $crud->crud->addField([
            'label'             => 'Emergency Email',
            'name'              => 'emergency_email',
            'type'              => 'email',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            'tab'               => $tab,
        ]);
    }
}
