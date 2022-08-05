<?php

namespace App\Widgets;

use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Student;
use Arrilot\Widgets\AbstractWidget;

class EnrolmentSummary extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'count' => 10
    ];

    public function placeholder()
    {
        return '<center>Loading</center>';
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        $schoolyear = SchoolYear::where('isActive',1)->first();
        $schoolyear = $schoolyear !== null ? $schoolyear : null;

        $school_year = null;
        $enrolment_data_total = 0;

        $students = 0;
        $rawChartDS = [];
        $rawChartLabel = [];
        $dataMaleGender = null;
        $dataFemaleGender = null;
        $enrolment_by_level = [];

        if($schoolyear !== null) {
            $school_year = $schoolyear->schoolYear; 
            $enrolment_data_total = Enrollment::where('school_year_id', $schoolyear->id)
                                        ->where('deleted_at', null)
                                        ->where('is_applicant', 0)
                                        ->with('student')
                                        ->get();

            $school_year_id = $schoolyear->id;

            $enrolment_by_level = Enrollment::with('level')->where('school_year_id', $schoolyear->id)
                                            ->where('school_year_id', $school_year_id)
                                            ->where('is_applicant', 0)
                                            ->selectRaw('count(level_id) as level_total, level_id')
                                            ->groupBy('level_id')
                                            ->get();
            

            $enrollment_gender = Student::whereHas('enrollments', function($query) use ($school_year_id)
                            {
                                        $query->where('school_year_id',$school_year_id);
                                        $query->where('is_applicant', 0);
                                        $query->groupBy('level_id');
                                })
                                // ->where('level_id', 1)
                                ->groupBy('level_id','gender')
                                ->selectRaw('level_id, gender, count(gender) as population')
                                ->get();

            $enrollment_gender_male = Student::whereHas('enrollments', function($query) use ($school_year_id)               {
                                        $query->where('school_year_id',$school_year_id);
                                        $query->where('is_applicant', 0);
                                        $query->groupBy('level_id');
                                })
                                ->where('gender', "Male")
                                ->get()->count();

            $enrollment_gender_female = Student::whereHas('enrollments', function($query) use ($school_year_id){
                                        $query->where('school_year_id',$school_year_id);
                                        $query->where('is_applicant', 0);
                                        $query->groupBy('level_id');
                                })
                                ->where('gender', "Female")
                                ->get()->count();                    

            
            $arrayProcessedMale = [];
            $arrayProcessedFemale = [];

            foreach($enrollment_gender as $row) {
                if($row->gender == "Male"){
                    if($row->population){
                        array_push($arrayProcessedMale, $row->population ?? 0);
                    } else {
                        array_push($arrayProcessedMale, 0);
                    }
                    
                }
                if($row->gender == "Female"){
                    if($row->population){
                        array_push($arrayProcessedFemale, $row->population ?? 0);
                    } else {
                        array_push($arrayProcessedFemale, 0);
                    }
                }
               
            }


            $arrayProcessedMale = collect($arrayProcessedMale)->sortBy('sequence');

            $arrayProcessedFemale = collect($arrayProcessedFemale)->sortBy('sequence');

            $dataMaleGender = [
                'label' => 'Male',
                'backgroundColor' => '#7aa7f9',
                'borderColor' => '#3868c1',
                'borderWidth' => 1,
                'data' => $arrayProcessedMale
            ];

            $dataFemaleGender = [
                'label' => 'Female',
                'backgroundColor' => '#ffc1ee',
                'borderColor' => '#ea96d3',
                'borderWidth' => 1,
                'data' => $arrayProcessedFemale
            ];

            $dataMaleGender = json_encode($dataMaleGender);
            $dataFemaleGender = json_encode($dataFemaleGender);

            foreach($enrolment_by_level as $item) {
                array_push($rawChartDS, $item->level_total);
            }

            foreach($enrolment_by_level as $item) {
                array_push($rawChartLabel, $item->level->year ?? "Undef");
            }
            
            $rawChartLabel = json_encode($rawChartLabel);
            $rawChartDS = json_encode($rawChartDS);
           
            $enrolment_data_by_level_gender = Enrollment::with('students')->whereHas('students',function ($query) {
                                                    $query->where('gender','male');
                                                    $query->where('is_applicant', 0);
                                                    $query->selectRaw('count(students.gender) count');
                                                })
                                            ->groupBy('level_id')
                                            ->get();
        }

        $data = compact(
            'enrolment_data_total',
            'enrolment_by_level',
            'rawChartDS',
            'rawChartLabel',
            'dataMaleGender',
            'dataFemaleGender',
            'school_year',
            'enrollment_gender_male',
            'enrollment_gender_female'
        );

        return view('widgets.enrolment_summary', $data);
    }
}
