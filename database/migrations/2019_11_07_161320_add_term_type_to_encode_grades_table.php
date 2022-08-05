<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTermTypeToEncodeGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('encode_grades', function (Blueprint $table) {
            $table->enum('term_type', ['Full', 'First', 'Second'])->after('section_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('encode_grades', function (Blueprint $table) {
            $table->dropColumn('term_type');
        });
    }
}
