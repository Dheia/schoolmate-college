<?php
namespace App\Http\Controllers\Admin\Assessment;
trait QuestionnaireTemplates
{
    /*
    |--------------------------------------------------------------------------
    | Questionnaire Templates for Question Bank
    |--------------------------------------------------------------------------
    |
    | Each page template has its own method, that define what fields should show up using the Backpack\CRUD API.
    | Use snake_case for naming and PageManager will make sure it looks pretty in the create/update form
    | template dropdown.
    |
    | Any fields defined here will show up after the standard page fields:
    | - select template
    | - page name (only seen by admins)
    | - page title
    | - page slug
    */
    private function multiple_choice()
    {
        // $this->crud->addField([
        //     'name' => 'choices',
        //     'label' => 'Choices',
        //     'type' => 'assessment.table_choices',
        //     'entity_singular' => 'Choice', // used on the "Add X" button
        //     'columns' => [
        //          [
        //             'label'      => '',
        //             'type'       => 'hidden',
        //             'name'       => 'choice',
        //         ],
               
        //         [
        //             'label'      => '',
        //             'type'       => 'child_text',
        //             'name'       => 'value',
        //             'attributes' => [
        //                 'required' => 'true',
        //             ],
        //         ],
                
        //     ],
        //     'min' => '3',
        //     'max' => '4'
        // ]);
        $this->crud->addField([ // Table
            'name' => 'choices',
            'label' => 'Choices',
            'type' => 'assessment.table_choices',
            'entity_singular' => 'Choices', // used on the "Add X" button
            'columns' => [
                'value' => ''
            ],
            'max' => 5, // maximum rows allowed in the table
            'min' => 3, // minimum rows allowed in the table
        ]);
    }

    private function true_or_false()
    {
        $this->crud->addField([
            'name'        => 'answer', // the name of the db column
            'label'       => 'Correct Answer', // the input label
            'type'        => 'radio',
            'options'     => [ // the key will be stored in the db, the value will be shown as label; 
                                'true' => "True",
                                'false' => "False"
                            ],
            // optional
            'inline'      => true, // show the radios all on the same line?
        ]);
        $this->crud->addField([
            'name'        => 'points', // the name of the db column
            'label'       => 'Points', // the input label
            'type'        => 'number'
        ]);

    }

    private function essay()
    {

    }
    
}