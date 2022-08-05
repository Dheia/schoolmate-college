<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetupGradeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setup_grade_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('setup_grade_id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('max')->nullable();
            $table->enum('type', ['percent', 'raw']);
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('lft')->nullable();
            $table->bigInteger('rgt')->nullable();
            $table->bigInteger('depth')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('setup_grade_id')->references('id')->on('setup_grades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setup_grade_items');
    }
}
