<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TagCrudRequest as StoreRequest;
use App\Http\Requests\TagCrudRequest as UpdateRequest;

class TagCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\Tag");
        $this->crud->setRoute("admin/tag");
        $this->crud->setEntityNameStrings('tag', 'tags');

        $this->crud->setColumns(['name','application']);
        $this->crud->addField([
			'name' => 'name',
			'label' => "Tag name",
			'tab' => 'Personal'
		]);
		$this->crud->addField([   // date_picker
		    'name' => 'application',
		    'type' => 'date_picker',
		    'label' => 'Application',
		    'tab' => 'Personal',
		    // optional:
		    'date_picker_options' => [
		        'todayBtn' => true,
		        'format' => 'dd-mm-yyyy',
		        'language' => 'fr'
		    ],
		]);
    }

	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}

	public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }
}