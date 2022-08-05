<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class EncodeGrade extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'encode_grades';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'template_id',
        'subject_id',
        'section_id',
        'term_type',
        'period_id',
        'teacher_id',
        'submitted',
        'state',
        'rows'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['period_name'];
    protected $dates = ['deleted_at', 'submitted_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getGrades ($studentnumber, $level_id, $section_id, $divisor)
    {
        // First, Extract The Grade Data From JSON Where Has Clause Of `studentnumber` & `section`
        $grades = self::where('section_id', $section_id)
                        ->whereRaw("json_contains(`rows`, '$studentnumber', '$[0].studentnumber')")
                        ->where('submitted', 1)
                        ->with([
                            'template',
                            'subject' => function ($query) {
                                $query->with('parent:id,subject_title,subject_code,subject_description,percent');
                            },
                            'teacher:id,name,employee_id,email',
                        ])->get();
                        
        // Second, Get The Row Data Only By `studentnumber` And Exclude All The Data Rows  
        $grades->map(function ($items) use ($studentnumber) {
            $grades = $items->rows;
            $items->rows =  collect($grades)->filter(function ($value, $key) use ($studentnumber) { 
                                return $value->studentnumber == $studentnumber; 
                            });
        });

        $old_subject_id = null;
        $old_section_id = null;

        $data   = [];
        $newRow = [];

        foreach ($grades as $key => $grade) {
            if(count($newRow) > 0) {
                if ($grade->subject->parent_id === null && $grade->period) {
                    $newRow[] = [
                        'id'            => $grade->id,
                        'subject_code'  => $grade->subject->subject_code,
                        'subject_id'    => $grade->subject_id,
                        'period_name'   => $grade->period->name,
                        'period_id'     => $grade->period->id,
                        'period_name'   => $grade->period->name,
                        'period_id'     => $grade->period->id,
                        "percent"       => $grade->subject->percent,
                        "initial_grade" => $grade->rows[0]->initial_grade,
                        "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                        'teacher_id'    => $grade->teacher_id,
                        'teacher_name'  => $grade->teacher->name,
                        'childs'        => null,
                    ];
                } else {
                    foreach ($newRow as $key => $row) {

                        // Check If Already Existing In `$newRow` (period_id, subject_id)
                        if($grade->subject->parent_id !== null && $grade->period) {
                            if($row['subject_id'] == $grade->subject->parent_id && $row['period_id'] == $grade->period_id) {
                                    $newRow[$key]['childs'][] = [
                                        "id"            => $grade->id,
                                        "subject_code"  => $grade->subject->subject_code,
                                        'period_name'   => $grade->period->name,
                                        'period_id'     => $grade->period->id,
                                        "percent"       => $grade->subject->percent,
                                        "parent_id"     => $grade->subject->parent_id,
                                        "initial_grade" => $grade->rows[0]->initial_grade,
                                        "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                                        'teacher_id'    => $grade->teacher_id,
                                        'teacher_name'  => $grade->teacher->name,
                                    ];
                                
                            } else { // If Not Existing To New Array, Create A New
                                $parent = SubjectManagement::where('id', $grade->subject->parent_id)->first();
                                $exists = collect($newRow)->where('id', $parent->id);
                                if(count($exists) < 1 && $grade->period) {
                                    $newRow[] = [
                                        'id'            => $parent->id,
                                        'subject_code'  => $parent->subject_code,
                                        'subject_id'    => $parent->id,
                                        'period_name'   => $grade->period->name,
                                        'period_id'     => $grade->period->id,
                                        'childs'        =>  [
                                                                [
                                                                    "id"            => $grade->id,
                                                                    "subject_code"  => $grade->subject->subject_code,
                                                                    'period_name'   => $grade->period->name,
                                                                    'period_id'     => $grade->period->id,
                                                                    "percent"       => $grade->subject->percent,
                                                                    "parent_id"     => $grade->subject->parent_id,
                                                                    "initial_grade" => $grade->rows[0]->initial_grade,
                                                                    "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                                                                    'teacher_id'    => $grade->teacher_id,
                                                                    'teacher_name'  => $grade->teacher->name,
                                                                ]
                                                            ],
                                    ];
                                }
                            }
                        }
                    }
                }
            } else {
                if ($grade->subject->parent_id === null && $grade->period) {
                    $newRow[] = [
                        'id'            => $grade->id,
                        'subject_code'  => $grade->subject->subject_code,
                        'subject_id'    => $grade->subject->id,
                        'period_name'   => $grade->period->name,
                        'period_id'     => $grade->period->id,
                        "percentage"    => $grade->subject->percent,
                        "initial_grade" => $grade->rows[0]->initial_grade,
                        "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                        'teacher_id'    => $grade->teacher_id,
                        'teacher_name'  => $grade->teacher->name,
                        'childs'        => null,
                    ];

                } else {
                    foreach ($newRow as $key => $row) {

                        // Check If Already Existing In `$newRow` (period_id, subject_id)
                        if($grade->subject->parent_id !== null && $grade->period) {
                            if($row['subject_id'] == $grade->subject->parent_id && $row['period_id'] == $grade->period_id) {
                                    $newRow[$key]['childs'][] = [
                                        "id"            => $grade->subject->id,
                                        "subject_code"  => $grade->subject->subject_code,
                                        'period_name'   => $grade->period->name,
                                        'period_id'     => $grade->period->id,
                                        "percentage"    => $grade->subject->percent,
                                        "parent_id"     => $grade->subject->parent_id,
                                        "initial_grade" => $grade->rows[0]->initial_grade,
                                        "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                                        'teacher_id'    => $grade->teacher_id,
                                        'teacher_name'  => $grade->teacher->name,
                                    ];
                                
                            } else { // If Not Existing To New Array, Create A New
                                $parent = SubjectManagement::where('id', $grade->subject->parent_id)->first();
                                $exists = collect($newRow)->where('id', $parent->id);
                                if(count($exists) < 1 ) {
                                    $newRow[] = [
                                        'id'            => $parent->id,
                                        'subject_code'  => $parent->subject_code,
                                        'subject_id'    => $parent->id,
                                        'period_name'   => $grade->period->name,
                                        'period_id'     => $grade->period->id,
                                        'childs'      =>  [
                                            [
                                                "id"            => $grade->subject->id,
                                                "subject_code"  => $grade->subject->subject_code,
                                                'period_name'   => $grade->period->name,
                                                'period_id'     => $grade->period->id,
                                                "percentage"    => $grade->subject->percent,
                                                "parent_id"     => $grade->subject->parent_id,
                                                "initial_grade" => $grade->rows[0]->initial_grade,
                                                "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                                                'teacher_id'    => $grade->teacher_id,
                                                'teacher_name'  => $grade->teacher->name,
                                            ]
                                        ],
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        $subjects = self::getSubjects($level_id);

        // MERGE $subjects and $grades
        foreach($subjects as $subjectKey => $subject) {
            foreach ($newRow as $key => $row) {
                if($row['subject_id'] === $subject['id']) {
                    $subjects[$subjectKey]['subjects'][] = $row;
                }
            }
        }


        // Find `$subjects` That Has Childs And Compute The Initial Grade And Compute The Final Grade All Periods 
        foreach ($subjects as $subjectKey => $subject) {
            if(isset($subject['subjects'])) {

                foreach($subject['subjects'] as $subSubjectKey => $subSubject) {

                    // If Subjects Has No Child Then Directly Get The Initial Grade And Sum Up All The (Periods/Term) 
                    if($subSubject['childs'] == null) {
                        $finalGrade = collect($subject['subjects'])->sum('quarterly_grade') / $divisor;
                        $subjects[$subjectKey]['final_grade'] = $finalGrade;
                    }

                    // Check If Has Childs, Sum Up All Initial Grade And Multiply The Percentage Of The Subject (initial_grade * percentage)
                    if($subSubject['childs'] !== null) {
                        $childs = collect($subSubject['childs']);
                        $map =  $childs->map(function ($item, $key) {
                                    return $item['quarterly_grade'] * ( (float)($item['percent']) / 100 );
                                });
                        $finalGrade = $map->sum();
                        $subjects[$subjectKey]['subjects'][$subSubjectKey]['quarterly_grade'] =  $finalGrade;
                        $subjects[$subjectKey]['final_grade'] = $finalGrade / $divisor;
                    }
                }
            }
        }
        return $subjects;
    }

    public static function getTermGrades ($studentnumber, $level_id, $section_id, $divisor, $term_type)
    {
        // First, Extract The Grade Data From JSON Where Has Clause Of `studentnumber` & `section`
        $grades = self::where('section_id', $section_id)
                        ->whereRaw("json_contains(`rows`, '$studentnumber', '$[0].studentnumber')")
                        ->where('submitted', 1)
                        ->where('term_type', $term_type)
                        ->with([
                            'template',
                            'subject' => function ($query) {
                                $query->with('parent:id,subject_title,subject_code,subject_description,percent');
                            },
                            'teacher:id,name,employee_id,email',
                        ])->get();
                        
        // Second, Get The Row Data Only By `studentnumber` And Exclude All The Data Rows  
        $grades->map(function ($items) use ($studentnumber) {
            $grades = $items->rows;
            $items->rows =  collect($grades)->filter(function ($value, $key) use ($studentnumber) { 
                                return $value->studentnumber == $studentnumber; 
                            });
        });

        $old_subject_id = null;
        $old_section_id = null;

        $data   = [];
        $newRow = [];

        foreach ($grades as $key => $grade) {
            if(count($newRow) > 0) {
                if ($grade->subject->parent_id === null && $grade->period) {
                    $newRow[] = [
                        'id'            => $grade->id,
                        'subject_code'  => $grade->subject->subject_code,
                        'subject_id'    => $grade->subject_id,
                        'period_name'   => $grade->period->name,
                        'period_id'     => $grade->period->id,
                        'period_name'   => $grade->period->name,
                        'period_id'     => $grade->period->id,
                        "percent"       => $grade->subject->percent,
                        "initial_grade" => $grade->rows[0]->initial_grade,
                        "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                        'teacher_id'    => $grade->teacher_id,
                        'teacher_name'  => $grade->teacher->name,
                        'childs'        => null,
                    ];
                } else {
                    foreach ($newRow as $key => $row) {

                        // Check If Already Existing In `$newRow` (period_id, subject_id)
                        if($grade->subject->parent_id !== null && $grade->period) {
                            if($row['subject_id'] == $grade->subject->parent_id && $row['period_id'] == $grade->period_id) {
                                    $newRow[$key]['childs'][] = [
                                        "id"            => $grade->id,
                                        "subject_code"  => $grade->subject->subject_code,
                                        'period_name'   => $grade->period->name,
                                        'period_id'     => $grade->period->id,
                                        "percent"       => $grade->subject->percent,
                                        "parent_id"     => $grade->subject->parent_id,
                                        "initial_grade" => $grade->rows[0]->initial_grade,
                                        "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                                        'teacher_id'    => $grade->teacher_id,
                                        'teacher_name'  => $grade->teacher->name,
                                    ];
                                
                            } else { // If Not Existing To New Array, Create A New
                                $parent = SubjectManagement::where('id', $grade->subject->parent_id)->first();
                                $exists = collect($newRow)->where('id', $parent->id);
                                if(count($exists) < 1 && $grade->period) {
                                    $newRow[] = [
                                        'id'            => $parent->id,
                                        'subject_code'  => $parent->subject_code,
                                        'subject_id'    => $parent->id,
                                        'period_name'   => $grade->period->name,
                                        'period_id'     => $grade->period->id,
                                        'childs'        =>  [
                                                                [
                                                                    "id"            => $grade->id,
                                                                    "subject_code"  => $grade->subject->subject_code,
                                                                    'period_name'   => $grade->period->name,
                                                                    'period_id'     => $grade->period->id,
                                                                    "percent"       => $grade->subject->percent,
                                                                    "parent_id"     => $grade->subject->parent_id,
                                                                    "initial_grade" => $grade->rows[0]->initial_grade,
                                                                    "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                                                                    'teacher_id'    => $grade->teacher_id,
                                                                    'teacher_name'  => $grade->teacher->name,
                                                                ]
                                                            ],
                                    ];
                                }
                            }
                        }
                    }
                }
            } else {
                if ($grade->subject->parent_id === null && $grade->period) {
                    $newRow[] = [
                        'id'            => $grade->id,
                        'subject_code'  => $grade->subject->subject_code,
                        'subject_id'    => $grade->subject->id,
                        'period_name'   => $grade->period->name,
                        'period_id'     => $grade->period->id,
                        "percentage"    => $grade->subject->percent,
                        "initial_grade" => $grade->rows[0]->initial_grade,
                        "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                        'teacher_id'    => $grade->teacher_id,
                        'teacher_name'  => $grade->teacher->name,
                        'childs'        => null,
                    ];

                } else {
                    foreach ($newRow as $key => $row) {

                        // Check If Already Existing In `$newRow` (period_id, subject_id)
                        if($grade->subject->parent_id !== null && $grade->period) {
                            if($row['subject_id'] == $grade->subject->parent_id && $row['period_id'] == $grade->period_id) {
                                    $newRow[$key]['childs'][] = [
                                        "id"            => $grade->subject->id,
                                        "subject_code"  => $grade->subject->subject_code,
                                        'period_name'   => $grade->period->name,
                                        'period_id'     => $grade->period->id,
                                        "percentage"    => $grade->subject->percent,
                                        "parent_id"     => $grade->subject->parent_id,
                                        "initial_grade" => $grade->rows[0]->initial_grade,
                                        "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                                        'teacher_id'    => $grade->teacher_id,
                                        'teacher_name'  => $grade->teacher->name,
                                    ];
                                
                            } else { // If Not Existing To New Array, Create A New
                                $parent = SubjectManagement::where('id', $grade->subject->parent_id)->first();
                                $exists = collect($newRow)->where('id', $parent->id);
                                if(count($exists) < 1 ) {
                                    $newRow[] = [
                                        'id'            => $parent->id,
                                        'subject_code'  => $parent->subject_code,
                                        'subject_id'    => $parent->id,
                                        'period_name'   => $grade->period->name,
                                        'period_id'     => $grade->period->id,
                                        'childs'      =>  [
                                            [
                                                "id"            => $grade->subject->id,
                                                "subject_code"  => $grade->subject->subject_code,
                                                'period_name'   => $grade->period->name,
                                                'period_id'     => $grade->period->id,
                                                "percentage"    => $grade->subject->percent,
                                                "parent_id"     => $grade->subject->parent_id,
                                                "initial_grade" => $grade->rows[0]->initial_grade,
                                                "quarterly_grade" => $grade->rows[0]->quarterly_grade,
                                                'teacher_id'    => $grade->teacher_id,
                                                'teacher_name'  => $grade->teacher->name,
                                            ]
                                        ],
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        $subjects = self::getTermSubjects($level_id, $term_type);

        // MERGE $subjects and $grades
        foreach($subjects as $subjectKey => $subject) {
            foreach ($newRow as $key => $row) {
                if($row['subject_id'] === $subject['id']) {
                    $subjects[$subjectKey]['subjects'][] = $row;
                }
            }
        }


        // Find `$subjects` That Has Childs And Compute The Initial Grade And Compute The Final Grade All Periods 
        foreach ($subjects as $subjectKey => $subject) {
            if(isset($subject['subjects'])) {
                foreach($subject['subjects'] as $subSubjectKey => $subSubject) {

                    // If Subjects Has No Child Then Directly Get The Initial Grade And Sum Up All The (Periods/Term) 
                    if($subSubject['childs'] == null) {
                        $finalGrade = collect($subject['subjects'])->sum('quarterly_grade') / $divisor;
                        $subjects[$subjectKey]['final_grade'] = $finalGrade;
                    }

                    // Check If Has Childs, Sum Up All Initial Grade And Multiply The Percentage Of The Subject (initial_grade * percentage)
                    if($subSubject['childs'] !== null) {
                        $childs = collect($subSubject['childs']);
                        $map =  $childs->map(function ($item, $key) {
                                    return $item['quarterly_grade'] * ( (float)($item['percent']) / 100 );
                                });
                        $finalGrade = $map->sum();
                        $subjects[$subjectKey]['subjects'][$subSubjectKey]['quarterly_grade'] =  $finalGrade;
                        $subjects[$subjectKey]['final_grade'] = $finalGrade / $divisor;
                    }
                }
            }
        }
        return $subjects;
    }

    public static function getTermSubjects ($level_id, $term_type)
    {
        $subjectMapping = SubjectMapping::where('level_id', $level_id)->where('term_type', $term_type)->with('level')->first();
        $subjectsIds    = collect($subjectMapping->subjects)->pluck('subject_code')->toArray();
        $subjects       = SubjectManagement::select('id', 'subject_title', 'subject_code', 'subject_description', 'percent', 'parent_id')->findMany($subjectsIds);
        $newRow         = [];

        foreach ($subjects as $subject) {
            
            // If No Parent
            if($subject->parent_id === null) {
                $newRow[] = $subject->toArray();
            }

            // If Has Parent
            if($subject->parent_id !== null) {
                $parent = SubjectManagement::where('id', $subject->parent_id)->select('id', 'subject_title', 'subject_code', 'subject_description', 'percent', 'parent_id')->first();
                $exists = collect($newRow)->where('id', $subject->parent_id);
                if(count($exists) < 1) {
                    $newRow[] = $parent->toArray();
                }
            }
        }

        return $newRow;
    }

    public static function getSubjects ($level_id)
    {
        $subjectMapping = SubjectMapping::where('level_id', $level_id)->with('level')->first();
        $subjectsIds    = collect($subjectMapping->subjects)->pluck('subject_code')->toArray();
        $subjects       = SubjectManagement::select('id', 'subject_title', 'subject_code', 'subject_description', 'percent', 'parent_id')->findMany($subjectsIds);
        $newRow         = [];

        foreach ($subjects as $subject) {
            
            // If No Parent
            if($subject->parent_id === null) {
                $newRow[] = $subject->toArray();
            }

            // If Has Parent
            if($subject->parent_id !== null) {
                $parent = SubjectManagement::where('id', $subject->parent_id)->select('id', 'subject_title', 'subject_code', 'subject_description', 'percent', 'parent_id')->first();
                $exists = collect($newRow)->where('id', $subject->parent_id);
                if(count($exists) < 1) {
                    $newRow[] = $parent->toArray();
                }
            }
        }

        return $newRow;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    
    public function template ()
    {
        return $this->belongsTo(GradeTemplate::class)->withTrashed();
    }

    public function sectionForEncodeGrade () 
    {
        return $this->belongsTo('App\Models\SectionManagement');
    }

    public function period ()
    {
        return $this->belongsTo(Period::class);
    }

    public function subject ()
    {
        return $this->belongsTo(SubjectManagement::class);
    }

    public function teacher ()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS WITH TRASHED
    |--------------------------------------------------------------------------
    */

    public function templateWithTrashed ()
    {
        return $this->template()->withTrashed();
    }

    public function sectionForEncodeGradeWithTrashed () 
    {
        return $this->sectionForEncodeGrade()->withTrashed();
    }

    public function periodWithTrashed ()
    {
        return $this->period()->withTrashed();
    }

    public function subjectWithTrashed ()
    {
        return $this->subject()->withTrashed();
    }

    public function teacherWithTrashed ()
    {
        return $this->teacher()->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getPeriodNameAttribute ()
    {
        $period = $this->period()->first();

        if($period !== null) {
            return $period->name;
        }

        return '-';
    }

    public function getRowsAttribute ()
    {
        return json_decode($this->attributes['rows']);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
