<?php

use Illuminate\Database\Seeder;

class CommitmentPaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('commitment_payments')->insert([
            'name' => "Full Cash"
        ]);
        DB::table('commitment_payments')->insert([
            'name' => "Semi-Annual"
        ]);
        DB::table('commitment_payments')->insert([
            'name' => "Quarterly"
        ]);
        DB::table('commitment_payments')->insert([
            'name' => "Monthly"
        ]);
    }
}
