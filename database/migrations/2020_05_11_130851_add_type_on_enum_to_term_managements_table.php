<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeOnEnumToTermManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('term_managements', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('term_managements', function (Blueprint $table) {
            $table->enum('type', ['FullTerm', 'Semester', 'Trimester', 'Quadrimester'])->after('level_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('term_managements', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        
        Schema::table('term_managements', function (Blueprint $table) {
            $table->enum('type', ['FullTerm', 'Semester'])->after('level_id');
        });
    }
}
