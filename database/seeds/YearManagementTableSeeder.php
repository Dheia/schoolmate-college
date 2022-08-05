<?php

use Illuminate\Database\Seeder;

class YearManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('year_managements')->insert([
        	'year' => "Toddler"
    	]);
    	DB::table('year_managements')->insert([
        	'year' => "Nursery"
    	]);
    	DB::table('year_managements')->insert([
        	'year' => "Pre Kinder"
    	]);
    	DB::table('year_managements')->insert([
        	'year' => "Kinder"
    	]);
    	

		DB::table('year_managements')->insert([
        	'year' => "Grade 1"
    	]);
		DB::table('year_managements')->insert([
			'year' => "Grade 2"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 3"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 4"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 5"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 6"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 7"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 8"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 9"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 10"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 11"
		]);
		DB::table('year_managements')->insert([
			'year' => "Grade 12"
		]);
    }
}
