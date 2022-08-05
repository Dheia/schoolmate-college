<?php

use Illuminate\Database\Seeder;

class AllowOtherProgramAndServiceEnrollmentInSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'key'         => 'allow_program_and_service_enrollment',
            'name'        => 'Other Program and Service Enrollment in Student Portal',
            'description' => 'This will Enable/Disable the Other Program and Service Enrollment in Student Portal',
            'value'       => 0,
            'field'       => '{"name":"value","label":"Allow Other Program and Service Enrollment","type":"checkbox"}',
            'active'      => 1,
        ]);
    }
}
