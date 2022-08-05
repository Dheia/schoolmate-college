<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTuitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tuitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('form_name');
            // $table->string('commitment_payment_id');
            $table->string('schoolyear_id');
            
            $table->string('grade_level_id');
            $table->longText('tuition_fees');
            $table->longText('miscellaneous');
            $table->longText('payment_scheme');
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
        Schema::dropIfExists('tuitions');
    }
}
