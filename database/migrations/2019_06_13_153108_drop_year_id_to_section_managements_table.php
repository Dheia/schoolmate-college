<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropYearIdToSectionManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section_managements', function (Blueprint $table) {
            $table->dropColumn("year_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section_managements', function (Blueprint $table) {
            $table->integer("year_id")->after("curriculum_id")->nullable();
        });
    }
}
