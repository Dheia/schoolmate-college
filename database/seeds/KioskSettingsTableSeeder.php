<?php

use Illuminate\Database\Seeder;

class KioskSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.\
     *
     * @return void
     */
    public function run()
    {
        DB::table('kiosk_settings')->insert([
        	'key' => "old_student_option",
            'name' => "Old Student Option",
            'description' => "Enable Old Student Option",
            'active' => "0"
        ]);

        DB::table('kiosk_settings')->insert([
            'key' => "new_student_option",
            'name' => "New Student Option",
            'description' => "Enable New Student Option",
            'active' => "0"
        ]);

        DB::table('kiosk_settings')->insert([
        	'key' => "new_school_option",
            'name' => "New School Option",
            'description' => "Enable New School Option",
            'active' => "1"
        ]);

        DB::table('kiosk_settings')->insert([
            'key' => "announcement",
            'name' => "Announcement",
            'description' => "Announcement",
            'active' => "0"
        ]);

        DB::table('kiosk_settings')->insert([
            'key' => "initial_payment",
            'name' => "Require Initial Payment",
            'description' => "Require Initial Payment",
            'active' => "0"
        ]);

        DB::table('kiosk_settings')->insert([
            'key' => "additional_page",
            'name' => "Enable Additional Page",
            'description' => '<h1>Your application has been submitted!</h1>
                            <p><small>Please check your email address for your a copy of your application form and tuition fee details.</small><br />
                            <br />
                            As a requirement by the Department of Education (DepEd), kindly fill up their form by clicking on the link below</p>',
            'active' => "0"
        ]);

        DB::table('kiosk_settings')->insert([
            'key' => "terms_conditions",
            'name' => "Terms, Conditions and Data Privacy",
            'description' => '<h1>Terms, Conditions and Data Privacy</h1>',
            'active' => "0"
        ]);
    }
}
