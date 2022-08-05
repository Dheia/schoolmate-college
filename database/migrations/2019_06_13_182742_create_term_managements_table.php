<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term_managements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id');
            $table->integer('level_id');
            $table->string("name");
            $table->enum("type", ["FullTerm", "Semester"]);
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
        Schema::dropIfExists('term_managements');
    }
}
