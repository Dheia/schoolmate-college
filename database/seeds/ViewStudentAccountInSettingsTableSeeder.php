<?php

use Illuminate\Database\Seeder;

class ViewStudentAccountInSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'key'         => 'viewstudentaccount',
            'name'        => 'View Student Account in Student Portal',
            'description' => 'View Student Account in Student Portal',
            'value'       => '',
            'field'       => '{"name":"value","label":"Show Student Account","type":"checkbox"}',
            'active'      => 1,
        ]);
    }
}
