<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectedOtherServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_other_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('other_service_id');
            $table->integer('student_no');
            $table->integer('commitment_payment_id');
            $table->integer('grade_level_id');
            $table->integer('tuition_id');
            $table->integer('school_year_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selected_other_services');
    }
}
