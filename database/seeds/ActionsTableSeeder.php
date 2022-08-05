<?php

use Illuminate\Database\Seeder;

class ActionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actions')->insert([
            'name' => "List",
            'created_at' => Carbon\Carbon::now(),
        ]);

        DB::table('actions')->insert([
            'name' => "Create",
            'created_at' => Carbon\Carbon::now(),
        ]);

        DB::table('actions')->insert([
            'name' => "Update",
            'created_at' => Carbon\Carbon::now(),
        ]);

        DB::table('actions')->insert([
            'name' => "Delete",
            'created_at' => Carbon\Carbon::now(),
        ]);
    }
}
