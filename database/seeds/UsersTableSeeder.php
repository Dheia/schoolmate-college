<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Models\Role;
use App\Models\BackpackUser;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user            = new User;
        $user->name      = "SchoolMATE Developer";
        $user->email     = 'dev@schoolmate-online.net';
        $user->password  = bcrypt('$ecurit1');
        $user->save();

        $constRoles = [
            'Administrator', 
            'Admission', 
            'Enrollment', 
            'Accounting', 
            'Teacher', 
            'Inventory', 
            'Security', 
            'Human Resource', 
            'Payroll', 
            'Canteen', 
            'School Store', 
            'Library'
        ];
        
        foreach ($constRoles as $value) {
            Role::findOrCreate($value);
            BackpackUser::where('id', $user->id)->first()->assignRole($value);
        }
    }
}
