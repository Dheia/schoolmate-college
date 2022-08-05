<?php

use Illuminate\Database\Seeder;

class TuitionInKioskSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kiosk_settings')->insert([
        	'key' => "tuition",
            'name' => "Show Tuition",
            'description' => "Show Tuition",
            'active' => "1"
        ]);
    }
}
