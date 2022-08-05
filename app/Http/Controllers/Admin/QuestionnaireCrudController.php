<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuestionnaireRequest as StoreRequest;
use App\Http\Requests\QuestionnaireRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// QUESTIONNAIRE TEMPLATE
use App\Http\Controllers\Admin\Assessment\QuestionnaireTemplates;

// MODELS
use App\Models\SubjectManagement;
use App\Models\SchoolYear;

/**
 * Class QuestionnaireCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QuestionnaireCrudController extends CrudController
{
    use QuestionnaireTemplates;

    public function setup($template_name = false)
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Questionnaire');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/questionnaire');
        $this->crud->setEntityNameStrings('questionnaire', 'questionnaires');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in QuestionnaireRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        /*
        |--------------------------------------------------------------------------
        | USER AND LINK VALIDATION
        |--------------------------------------------------------------------------
        */
        if(!backpack_user()->hasRole('School Head')){

           $this->crud->addClause('where', 'user_id', backpack_auth()->user()->id);
               
        }

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([  // Select2
           'label' => "Subject",
           'type' => 'select',
           'name' => 'subject_id', // the db column for the foreign key
           'entity' => 'subject', // the method that defines the relationship in your Model
           'attribute' => 'subject_title', // foreign key attribute that is shown to user
           'model' => "App\Models\SubjectManagement" // foreign key model
        ]);

        $this->crud->addColumn([  // Select2
           'label' => "Type",
           'type' => 'text',
           'name' => 'type'
        ]);

        $this->crud->addColumn([
            'label' => 'Name',
            'type'  => 'markdown',
            'name'  => 'question'
        ]);

        $this->crud->addColumn([
            'label' => 'Answer',
            'type'  => 'text',
            'name'  => 'answer'
        ]);

        // if(request('view'))
        // {
        //     dd('asd00');
        // }
    }

    /*
    // -----------------------------------------------
    // Overwrites of CrudController
    // -----------------------------------------------
    // Overwrites the CrudController create() method to add template usage.
    */
    
    public function create($template_name = false)
    {
        $template = request('question_type');

        $this->addDefaultPageFields($template);
        $this->useQuestionTemplate($template);

        return parent::create($template);
    }

    public function store(StoreRequest $request)
    {
        $this->variable = 'A';
        $this->json_choice = [];
        $choices = [];
        $answer  = [];
        $json;
        // dd((json_decode($request->choices)));
        if($request->type == 'true_or_false')
        {
            $choices[] = [
                'choice' => $this->variable,
                'value' => true
            ];
            $this->variable++;
            $choices[] = [
                'choice' => $this->variable,
                'value' => false
            ];
            $json = [
                'type' => $request->type,
                'question' => $request->question,
                'correctAnswer' => $request->answer,
                'choices' => ["true", "false"]
            ];
        }
        else if($request->type == 'multiple_choice')
        {
            foreach (json_decode($request->choices) as $key => $reponse) {
                $choices[] = [
                    'choice' => $this->variable,
                    'value' => $reponse->value
                ];
                if($request->answer == $this->variable)
                {
                    $answer = $reponse->value;
                }
                $this->json_choice[] = $reponse->value;
                $this->variable++;
            }
            $json = [
                'type' => $request->type,
                'question' => $request->question,
                'correctAnswer' => $answer,
                'choices' => ($this->json_choice)
            ];
        }
        $request->request->set('json', json_encode($json));
        $request->request->set('choices', json_encode($choices));
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    // Overwrites the CrudController edit() method to add template usage.
    public function edit($id, $template = false)
    {
        $template = request('question_type');

        // if the template in the GET parameter is missing, figure it out from the db
        if ($template == false) {
            $model = $this->crud->model;
            $this->data['entry'] = $model::findOrFail($id);
            $template = $this->data['entry']->type;
        }

        if(!backpack_user()->hasRole('School Head')){

            if(backpack_auth()->user()->id != $this->data['entry']->user_id)
            {
                abort(403);
            }
               
        }

        $this->addDefaultPageFields($template);
        $this->useQuestionTemplate($template);

        return parent::edit($id);
    }

    public function update(UpdateRequest $request)
    {
        $this->variable = 'A';
        $this->json_choice = [];
        $choices = [];
        $answer = '';
        $json;
        // dd((json_decode($request->choices)));
        if($request->type == 'true_or_false')
        {
            $choices[] = [
                'choice' => $this->variable,
                'value' => true
            ];
            $this->variable++;
            $choices[] = [
                'choice' => $this->variable,
                'value' => false
            ];
            $json = [
                'type' => $request->type,
                'question' => $request->question,
                'correctAnswer' => $request->answer,
                'choices' => ["true", "false"]
            ];
        }
        else if($request->type == 'multiple_choice')
        {
            foreach (json_decode($request->choices) as $key => $reponse) {
                $choices[] = [
                    'choice' => $this->variable,
                    'value' => $reponse->value,
                ];
                if($request->answer == $this->variable)
                {
                    $answer = $reponse->value;
                }
                $this->json_choice[] = $reponse->value;
                $this->variable++;
            }
            $json = [
                'type' => $request->type,
                'question' => $request->question,
                'correctAnswer' => $answer,
                'choices' => ($this->json_choice)
            ];
        }
        $request->request->set('json', json_encode($json));
        $request->request->set('choices', json_encode($choices));
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // $this->addDefaultPageFields(\Request::input('type'));
        // $this->useQuestionTemplate(\Request::input('type'));

        return $redirect_location;
    }

     // -----------------------------------------------
    // Methods that are particular to the PageManager.
    // -----------------------------------------------

    /**
     * Populate the create/update forms with basic fields, that all pages need.
     *
     * @param string $template The name of the template that should be used in the current form.
     */
    public function addDefaultPageFields($template = false)
    {
        $this->crud->addField([
            'label' => 'Teacher',
            'type'  => 'hidden',
            'name'  => 'teacher_id',
            'value' => backpack_auth()->user()->employee_id
        ]);
        $this->crud->addField([
            'label' => 'User',
            'type'  => 'hidden',
            'name'  => 'user_id',
            'value' => backpack_auth()->user()->id
        ]);
        $this->crud->addField([
            'label' => 'School Year',
            'type'  => 'hidden',
            'name'  => 'school_year_id',
            'value' => SchoolYear::active()->first()->id
        ]);

        $this->crud->addField([
            'name' => 'type',
            'label' => 'Question Type',
            'type' => 'assessment.select_question_type',
            'options' => $this->getQuestionnaireTemplatesArray(),
            'value' => $template,
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12',
            ]
        ]);

        $this->crud->addField([  // Select2
           'label' => "Subject",
           'type' => 'select2',
           'name' => 'subject_id', // the db column for the foreign key
           'entity' => 'subject', // the method that defines the relationship in your Model
           'attribute' => 'subject_title', // foreign key attribute that is shown to user
           'model' => "App\Models\SubjectManagement" // foreign key model
        ]);

        $this->crud->addField([
            'name' => 'question',
            'label' => 'Question',
            'type' => 'wysiwyg',
        ]);
        
    }

    /**
     * Add the fields defined for a specific template.
     *
     * @param  string $template_name The name of the template that should be used in the current form.
     */
    public function useQuestionTemplate($template_name = false)
    {
        $templates = $this->getQuestionTemplates();

        // set the default template
        if ($template_name == false) {
            $template_name = $templates[0]->name;
        }

        // actually use the template
        if ($template_name) {
            $this->{$template_name}();
        }
    }

    /**
     * Get all defined templates.
     */
    public function getQuestionTemplates($template_name = false)
    {
        $templates_array = [];

        $templates_trait = new \ReflectionClass('App\Http\Controllers\Admin\Assessment\QuestionnaireTemplates');
        $templates = $templates_trait->getMethods(\ReflectionMethod::IS_PRIVATE);

        if (! count($templates)) {
            abort(503, trans('backpack::pagemanager.template_not_found'));
        }

        return $templates;
    }

    /**
     * Get all defined template as an array.
     *
     * Used to populate the template dropdown in the create/update forms.
     */
    public function getQuestionnaireTemplatesArray()
    {
        $templates = $this->getQuestionTemplates();

        foreach ($templates as $template) {
            $templates_array[$template->name] = str_replace('_', ' ', title_case($template->name));
        }

        return $templates_array;
    }
}
