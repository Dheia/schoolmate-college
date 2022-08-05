<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SchoolYearsTableSeeder::class);
        $this->call(CurriculumManagementTableSeeder::class);
        $this->call(SubjectManagementTableSeeder::class);
        $this->call(YearManagementTableSeeder::class);
        $this->call(CommitmentPaymentTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(MiscellaneousTableSeeder::class);
        // $this->call(StudentsTableSeeder::class);
        $this->call(TuitionsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(TaxCodesTableSeeder::class);
        $this->call(CashAccountsTableSeeder::class);
        // $this->call(AdminPermissionTableSeeder::class);
        $this->call(ActionsTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UsersRolesTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(KioskSettingsTableSeeder::class);

        // Add Tuition Row In Kiosk Settings
        $this->call(TuitionInKioskSettingsTableSeeder::class);
        $this->call(ReferralInKioskSettingsTableSeeder::class);

        // Add Row In Settings
        $this->call(ViewStudentAccountInSettingsTableSeeder::class);
        $this->call(AllowOtherProgramAndServiceEnrollmentInSettingsTableSeeder::class);
    }
}
