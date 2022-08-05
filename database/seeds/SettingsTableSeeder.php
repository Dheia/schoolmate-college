<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Http\Controllers\QuickBooks\QuickBooksOnline as QBO;

class SettingsTableSeeder extends Seeder
{
    /**
     * The settings to add.
     */
    protected $settings = [
        [
            'key'         => 'schoolname',
            'name'        => 'School Name',
            'description' => 'Complete School Name',
            'value'       => 'Your School Name',
            'field'       => '{ "name":"value","label":"Value", "type":"text"}',
            'active'      => 1,
        ],
        [
            'key'           => 'schoolemail',
            'name'          => 'Official School Email',
            'description'   => 'School Official Email',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value","type":"email"}',
            'active'        => 1,

        ],
        [
            'key'         => 'schoolabbr',
            'name'        => 'School Abbreviation',
            'description' => 'School Abbreviation',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'           => 'schooladdress',
            'name'          => 'Complete School Address',
            'description'   => 'Complete School Address',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value","type":"text"}',
            'active'        => 1,
        ],
        [
            'key'         => 'schoolcontactnumber',
            'name'        => 'School Contact Number',
            'description' => 'School Contact Number',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,

        ],
        [
            'key'         => 'schoollogo',
            'name'        => 'School Logo',
            'description' => 'Official School Logo',
            'value'       => 'uploads/no-logo.png',
            'field'       => '{"name":"value","label":"Value","type":"browse"}',
            'active'      => 1,

        ],
        [
            'key'         => 'rfidmarketingvideo',
            'name'        => 'Marketing Video',
            'description' => 'Marketing Video Showed in RFID Logs',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"browse"}',
            'active'      => 1,

        ],
        [
            'key'         => 'firstcutoff',
            'name'        => 'First Cut Off',
            'description' => 'First Cut Off',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"number"}',
            'active'      => 1,
        ],
        [
            'key'         => 'secondcutoff',
            'name'        => 'Second Cut Off',
            'description' => 'Second Cut Off',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"number"}',
            'active'      => 1,

        ],
        [
            'key'         => 'tuitionfeeinvoiceduedate',
            'name'        => 'Tuition Fee Invoice Due Date',
            'description' => 'Tuition Fee Invoice Due Date',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"date"}',
            'active'      => 1,
        ],
        [
            'key'         => 'autoapprovepayments',
            'name'        => 'Auto Approve Payments',
            'description' => 'Auto Approve Payments',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"check"}',
            'active'      => 1,
        ],
        [
            'key'         => 'paymentnotes',
            'name'        => 'Payment Notes',
            'description' => 'Payment Notes',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'smsgroup',
            'name'        => 'Smart Messaging Group',
            'description' => 'Smart Messaging Group For Tigernet Hosting School',
            'value'       => '',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ]
    ];

    public function taxAgency ()
    {   
        try {
            $qbo = new QBO;
            $qbo->initialize();

            if($qbo->dataService === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = "Oops.. Something Went Wrong! Please Try Again.";
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }

        // INITIALIZE QBO OAUTH
        // $qbo = new QBO;
        // $qbo->initialize();
        $taxRates = $qbo->dataService->Query("SELECT * FROM TaxRate maxresults 1000");

        $taxRateArray = [];
        if(count(collect($taxRates)->toArray()) > 0) {
            foreach ($taxRates as $key => $rate) {
                $taxRateArray[] = [
                    'Name' => $rate->Name . ' (' . $rate->RateValue . ')',
                    'Id' => $rate->Id 
                ];
            }
        }

        $taxRateArray = collect($taxRates)->pluck('Name', 'Id');

        $this->settings[] = [
            'key'         => 'taxrate',
            'name'        => 'Tax Rate',
            'description' => 'A TaxRate object represents rate applied to calculate tax liability. Use the TaxService entity to create a taxrate.',
            'value'       => '',
            // 'field'       => '{"name":"value","label":"Value","type":"select_from_array","options": ' . json_encode($taxRates->toArray()) . '}',
            'field'       => '{"name":"value","label":"Value","type":"select_from_array","options": ' . json_encode($taxRateArray->toArray()) . '}',
            'active'      => 1,
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('settings')->truncate();
        $this->taxAgency();

        foreach ($this->settings as $index => $setting) {
            if(DB::table('settings')->where('key', $setting['key'])->exists())
            {
                $this->command->info('Inserted ' . $setting['key'] . ' record.');
            }
            else {
                $result = DB::table('settings')->insert($setting);

                if (!$result) {
                    $this->command->info("Insert failed at record $index.");

                    return;
                }
            }
        }

        $this->command->info('Inserted '.count($this->settings).' records.');
    }
}
