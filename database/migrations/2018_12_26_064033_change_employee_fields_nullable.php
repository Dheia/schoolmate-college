<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEmployeeFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement('ALTER TABLE employees MODIFY address1 VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE employees MODIFY address2 VARCHAR(255) NULL');
        DB::statement('ALTER TABLE employees MODIFY mobile VARCHAR(255) NULL');
        DB::statement('ALTER TABLE employees MODIFY telephone VARCHAR(255) NULL');
        DB::statement('ALTER TABLE employees MODIFY sss VARCHAR(255) NULL');
        DB::statement('ALTER TABLE employees MODIFY phil_no VARCHAR(255) NULL');
        DB::statement('ALTER TABLE employees MODIFY pagibig VARCHAR(255) NULL');
        DB::statement('ALTER TABLE employees MODIFY tinno VARCHAR(255) NULL');
        DB::statement('ALTER TABLE employees MODIFY domestic_profile LONGTEXT NULL');
        DB::statement('ALTER TABLE employees MODIFY religion VARCHAR(255) NULL');
        DB::statement('ALTER TABLE employees MODIFY post_graduate LONGTEXT NULL');
        DB::statement('ALTER TABLE employees MODIFY referral TINYINT(1) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
