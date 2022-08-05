<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncodeGradeStatusHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encode_grade_status_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('setup_grade_id')->unsigned();
            $table->integer('studentnumber')->unsigned();
            $table->enum('remarks', ['Incomplete', 'Complete', 'Drop']);
            $table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('encode_grade_status_histories');
    }
}
