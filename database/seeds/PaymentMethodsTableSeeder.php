<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::firstOrCreate([
            'name' => "Cash",
            'icon' => "fa-money",
        ]);

        // DB::table('payment_methods')->insert([
        //     'name' => "Paypal",
        //     'icon' => "fa-paypal",
        // ]);
        // DB::table('payment_methods')->insert([
        //     'name' => "Credit Card",
        //     'icon' => "fa-credit-card"
        // ]);

        $payments = config('paynamics.payment_method_list');

        foreach ($payments as $key => $payment) {
            PaymentMethod::firstOrCreate([
                'code' => $key,
                'name' => $payment,
                'fee'  => 0,
                'fixed_amount' => 0
            ]);
        }
    }
}
