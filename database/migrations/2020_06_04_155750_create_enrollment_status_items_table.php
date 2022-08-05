<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentStatusItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollment_status_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('enrollment_status_id');
            $table->string('term');
            $table->date('term_start')->nullable();
            $table->date('term_end')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('enrollment_status_items');
    }
}
