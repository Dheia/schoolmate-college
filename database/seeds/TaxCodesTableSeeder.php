<?php

use Illuminate\Database\Seeder;

class TaxCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tax_codes')->insert([
			'name' => 'Philippines - VAT 0%',
		]);

		DB::table('tax_codes')->insert([
			'name' => 'Philippines - VAT 12% (Goods)',
		]);

		DB::table('tax_codes')->insert([
			'name' => 'Philippines - VAT 12% (Services)',
		]);

		DB::table('tax_codes')->insert([
			'name' => 'Philippines - VAT Exempt',
		]);
    }
}