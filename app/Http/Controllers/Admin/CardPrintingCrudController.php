<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CardPrintingRequest as StoreRequest;
use App\Http\Requests\CardPrintingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// MODELS
use App\Models\Student;
use App\Models\CardSetup;
use App\Models\SmartCardPrintLogs;

/**
 * Class CardPrintingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CardPrintingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        // $this->crud->setModel('App\Models\CardPrinting');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/smartcard/card-printing');
        $this->crud->setEntityNameStrings('Card Printing', 'Card Printing');
        $this->crud->setListView('cardPrinting.list');
        // $this->crud->denyAccess(['create', 'update', 'delete']);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in CardPrintingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // GET ALL COLUMN TABLE AND APPEND
        $studentColumns                 = new Student;
        $appends                        = collect($studentColumns->getMutatedAttributes());
        $columns                        = $studentColumns->getFillable();
        $studentColumn                  = $appends->merge($columns);        
        $this->data["studentColumns"]   = $studentColumn;

        $this->data['templates']    = CardSetup::all();

        // GET SET DEFAULT TEMPLATE
        $this->data['entry'] = CardSetup::active()->first();

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

    public function selectStudent ($studentnumber)
    {
        $template = CardSetup::active()->first();
        $front_card_columns = $template->front_card_columns->toArray(); 
        $back_card_columns = $template->back_card_columns->toArray(); 

        $front_card_items   = null;
        $back_card_items    = null;
        $student            = Student::where('studentnumber', $studentnumber)->with(['level', 'schoolYear'])->first();
        if($front_card_columns !== "" && $student !== null)
        {
            $front_card_items = [];
            foreach ($front_card_columns as $column) {

                if(str_contains($column, 'level_id')) {
                    $front_card_items[$column] = $student->level->year;
                }
                else if (str_contains($column, 'schoolyear')) {
                    $front_card_items[$column] = $student->schoolYear->schoolYear;
                }
                else if (str_contains($column, 'track_id')) {
                    $front_card_items[$column] = $student->track->code;
                }
                else if (str_contains($column, 'department_id')) {
                    $front_card_items[$column] = $student->department->name;
                } 
                else if (str_contains($column, 'photo')) {
                    if(!str_contains($student->photo, 'headshot-default.png')) {
                        $path   = \Storage::disk('public')->path($student->orig_photo);
                        $base64 = null;
                        // dd($path);
                        if(file_exists($path)) {
                            $type   = pathinfo($path, PATHINFO_EXTENSION);
                            $data   = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            $front_card_items[$column] = $base64;
                        }   else {
                                $path   = public_path("images/headshot-default.png");
                                $type   = pathinfo($path, PATHINFO_EXTENSION);
                                $data   = file_get_contents($path);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                $front_card_items[$column] = $base64;
                        }
                    } else {
                        $path   = public_path("images/headshot-default.png");
                        $type   = pathinfo($path, PATHINFO_EXTENSION);
                        $data   = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        $front_card_items[$column] = $base64;
                    }
                }
                else {
                    $front_card_items[$column] = $student->{$column};
                }
            }
        }

        // BACK CARD 
        if($back_card_columns !== "" && $student !== null)
        {
            $back_card_items = [];
            foreach ($back_card_columns as $column) {

                if(str_contains($column, 'level_id')) {
                    $back_card_items[$column] = $student->level->year;
                }
                else if (str_contains($column, 'schoolyear')) {
                    $back_card_items[$column] = $student->schoolYear->schoolYear;
                }
                else if (str_contains($column, 'track_id')) {
                    $back_card_items[$column] = $student->track->code;
                }
                else if (str_contains($column, 'department_id')) {
                    if($student->department !== null) {
                        $back_card_items[$column] = $student->department->name;
                    } else {
                        $back_card_items[$column] = null;
                    }
                } 
                else if (str_contains($column, 'photo')) {
                    if(!str_contains($student->photo, 'headshot-default.png')) {
                        $path   = \Storage::disk('public')->path($student->orig_photo);
                        $base64 = null;
                        if(\Storage::disk('public')->has($path)) {
                            $type   = pathinfo($path, PATHINFO_EXTENSION);
                            $data   = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        }
                        $back_card_items[$column] = $base64;
                    } else {
                        $path   = public_path("images/headshot-default.png");
                        $type   = pathinfo($path, PATHINFO_EXTENSION);
                        $data   = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        $back_card_items[$column] = $base64;
                    }
                }
                else {
                    $back_card_items[$column] = $student->{$column};
                }
            }
        }

        return response()->json(['front_card_items' => $front_card_items, 'back_card_items' => $back_card_items]);
    }

    public function printPDF ()
    {

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper(array(0, 0, 204, 326.688), 'portrait');
        // $pdf->setPaper('Letter', 'portrait');

        $front_card = request()->front;
        $rear_card  = request()->back;

        $html2 = "
        <title>Document</title>
        <style>
            @page{
                margin: 0;
            }
            
        </style>   

        <img src='". $front_card . "' width='100%'>

        <img src='". $rear_card ."' width='100%'>
        ";

        
        
        $pdf->loadHtml( $html2 );

        // dd($pdf);

        return $pdf->stream();
    }

    public function saveLogs ()
    {
        $log                = new SmartCardPrintLogs;
        $log->studentnumber = request()->studentnumber;
        $log->printed       = request()->printed;

        if($log->save()) {

        }
    }

    public function changeTemplate() {
        $template_id = request()->template_id;

        if(CardSetup::where('id', $template_id)->exists()) {
            CardSetup::query()->update([ 'active' => 0 ]);
            CardSetup::where('id', $template_id)->update([ 'active' => 1 ]);
        }
    }
}
