<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SettingRequest as StoreRequest;
use App\Http\Requests\SettingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class SettingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SettingCrudController extends CrudController
{
    public function setup()
    {
        parent::setup();

        $this->crud->setModel("Backpack\Settings\app\Models\Setting");
        $this->crud->setEntityNameStrings(trans('backpack::settings.setting_singular'), trans('backpack::settings.setting_plural'));
        $this->crud->setRoute(backpack_url('setting'));
        $this->crud->addClause('where', 'active', 1);
        $this->crud->denyAccess(['create', 'delete']);

        // $this->data['tabs'] = [
        //     'as' => 1
        // ];

        // dd($this->data['tabs']);
        
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => trans('backpack::settings.name'),
            ],
            [
                'name'  => 'value',
                'label' => trans('backpack::settings.value'),
            ],
            [
                'name'  => 'description',
                'label' => trans('backpack::settings.description'),
            ],
        ]);
        $this->crud->addField([
            'name'       => 'name',
            'label'      => trans('backpack::settings.name'),
            'type'       => 'text',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);

        $this->crud->setListView('settings.list');
    }

    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->crud->addField(json_decode($this->data['entry']->field, true)); // <---- this is where it's different
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
}
