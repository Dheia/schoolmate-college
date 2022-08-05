<?php

use Illuminate\Database\Seeder;

class ReferralInKioskSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kiosk_settings')->insert([
        	'key' => "referral",
            'name' => "Allow Referral",
            'description' => "Allow Referral",
            'active' => "0"
        ]);
    }
}
