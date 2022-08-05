<?php

use Illuminate\Database\Seeder;

class CashAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cash_accounts')->insert([
            'name' => "Cash",
            'code' => "COD",
            'currency_id' => 176,
            'currency' => 'PHP',
            'is_bank_maintained' => 0,
            'is_starting_balance' => 1,
            'starting_balance' => 600.00,
            'inactive' => 0
        ]);

        DB::table('cash_accounts')->insert([
            'name' => "Paypal",
            'code' => "PP",
            'currency_id' => 4,
            'currency' => 'PHP',
            'is_bank_maintained' => 1,
            'is_starting_balance' => 1,
            'starting_balance' => 45.08,
            'inactive' => 0
        ]);

        DB::table('cash_accounts')->insert([
            'name' => "Banco de Oro",
            'code' => "BDO",
            'currency_id' => 176,
            'currency' => 'PHP',
            'is_bank_maintained' => 1,
            'is_starting_balance' => 1,
            'starting_balance' => 100000.00,
            'inactive' => 0
        ]);
    }
}
