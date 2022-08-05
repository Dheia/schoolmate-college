<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('studentnumber');
            $table->string('file_recommendation_form')->nullable();
            $table->string('file_good_moral')->nullable();
            $table->string('file_report_card')->nullable();
            $table->string('file_birth_certificate')->nullable();
            $table->string('file_medical_certificate')->nullable();
            $table->string('file_id_passport')->nullable();
            $table->string('file_guardian1_id')->nullable();
            $table->string('file_guardian2_id')->nullable();
            $table->string('file_guardian1_agreement')->nullable();
            $table->string('file_guardian2_agreement')->nullable();
            $table->string('file_visa')->nullable();
            $table->string('file_alien_certificate')->nullable();
            $table->string('file_ssp')->nullable();
            $table->integer('uploaded_by')->nullable();
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
        Schema::dropIfExists('requirements');
    }
}
