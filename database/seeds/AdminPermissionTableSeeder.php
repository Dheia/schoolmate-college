<?php

use Illuminate\Database\Seeder;

class AdminPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          // ********* //
         //   ROLES   //
        // ********* //
        DB::table('roles')->insert([
            'name' => "Admin",
            'guard_name' => 'web',
            'created_at' => Carbon\Carbon::now(),
        ]);

        DB::table('roles')->insert([
            'name' => "Human Resources",
            'guard_name' => 'web',
            'created_at' => Carbon\Carbon::now(),
        ]);

        DB::table('roles')->insert([
            'name' => "Accounting",
            'guard_name' => 'web',
            'created_at' => Carbon\Carbon::now(),
        ]);

        DB::table('roles')->insert([
            'name' => "Security",
            'guard_name' => 'web',
            'created_at' => Carbon\Carbon::now(),
        ]);

        DB::table('roles')->insert([
            'name' => "Inventory",
            'guard_name' => 'web',
            'created_at' => Carbon\Carbon::now(),
        ]);

        DB::table('roles')->insert([
            'name' => "Admission",
            'guard_name' => 'web',
            'created_at' => Carbon\Carbon::now(),
        ]);

          // *************** //
         // MODEL HAS ROLES //
        // *************** //
        DB::table('model_has_roles')->insert([
        	'role_id'  => '1',
        	'model_id' => '1',
        	'created_at' => Carbon\Carbon::now(),
        ]);
    }
}
