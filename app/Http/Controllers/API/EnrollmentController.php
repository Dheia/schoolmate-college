<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public static function addNewEnrollment()
    {
        // ...
    }

    
    public function enrollmentCollections()
    {
        $q = "SELECT id as id, 'school_year' as collection_type, schoolYear as definition, 
        NULL as assortion
        FROM school_years WHERE isActive = 1 
        UNION 
        SELECT id as id, 'department' as collection_type, name as definition, 
        NULL as assortion 
        FROM departments WHERE active = 1
        UNION
        SELECT id as id, 'curriculum' as collection_type, curriculum_name as definition, 
        NULL as assortion 
        FROM curriculum_managements WHERE is_active = 1 
        UNION        
        SELECT ANY_VALUE(id) as id, 'level' as collection_type, year as definition, 
        JSON_ARRAYAGG(JSON_OBJECT('department', department_id)) as assortion 
        FROM year_managements GROUP BY year, id
        UNION        
        SELECT ANY_VALUE(id) as id, 'tuition_form' as collection_type, form_name as definition, 
        JSON_ARRAYAGG(JSON_OBJECT('department', department_id, 'school_year', schoolyear_id, 'level', grade_level_id, 'track', track_id))
        as assortion 
        FROM tuitions WHERE active = 1 GROUP BY form_name, id
        UNION        
        SELECT ANY_VALUE(id) as id, 'track' as collection_type, code as definition, 
        JSON_ARRAYAGG(JSON_OBJECT('level', level_id)) as assortion 
        FROM track_managements GROUP BY code
        UNION        
        SELECT ANY_VALUE(id) as id, 'term' as collection_type, type as definition, 
        JSON_ARRAYAGG(JSON_OBJECT('level',level_id, 'department', department_id)) as assortion 
        FROM term_managements GROUP BY type
        UNION        
        SELECT id as id, 'commitment_payment' as collection_type, name as definition, 
        NULL as assortion 
        FROM commitment_payments";
        $result = DB::select(DB::raw($q), [] );
        
        foreach($result as $row)
        {
            $row->assortion = json_decode($row->assortion);
        }

        return response()->json(["enrollment-collections" => $result]);
  
    }

}

