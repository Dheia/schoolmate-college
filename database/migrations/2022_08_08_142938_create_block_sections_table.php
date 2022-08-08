<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code');

            $table->bigInteger('employee_id')->unsigned();

            $table->bigInteger('school_year_id')->unsigned();
            $table->string('term_type');

            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('level_id')->unsigned();
            $table->bigInteger('section_id')->unsigned();

            $table->longText('subjects')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_sections');
    }
}
