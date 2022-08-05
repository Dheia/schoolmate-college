<?php

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::firstOrNew([
            'name' => "Pre School Department",
            'active' => "1",
            'deletable' => "0"
        ]);
         Department::firstOrNew([
            'name' => "Grade School Department",
			'active' => "1",
            'deletable' => "0"
        ]);
          Department::firstOrNew([
            'name' => "Junior High School Department",
			'active' => "1",
            'deletable' => "0"
        ]);
           Department::firstOrNew([
            'name' => "Senior High School Department",
			'active' => "1",
            'deletable' => "0"
        ]);
    }
}
