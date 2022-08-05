<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'Administrator',
                'guard_name' => 'web'
            ], 
            [
                'name' => 'Human Resource',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Accounting',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Security',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Inventory',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Admission',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Teacher',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Canteen',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Payroll',
                'guard_name' => 'web'
            ],
            [
                'name' => 'System',
                'guard_name' => 'web'
            ],
            [
                'name' => 'School Head',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Class',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Library',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Coordinator',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Employee',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Coordinator',
                'guard_name' => 'web'
            ],
        ]);
    }
}
