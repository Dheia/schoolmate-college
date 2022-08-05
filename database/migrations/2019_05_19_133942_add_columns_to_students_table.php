<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('mother_occupation')->after('mothercitizenship');
            $table->string('father_occupation')->after('fathercitizenship');
            $table->enum('mother_living_deceased', ['living', 'deceased'])->after('mother');
            $table->enum('father_living_deceased', ['living', 'deceased'])->after('father');
            $table->string('other_relative')->after('living')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('mother_occupation');
            $table->dropColumn('father_occupation');
            $table->dropColumn('mother_living_deceased');
            $table->dropColumn('father_living_deceased');
            $table->dropColumn('other_relative');
        });
    }
}
