<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTermTypeToSubjectMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_mappings', function (Blueprint $table) {
            $table->enum('term_type', ['Full', 'First', 'Second'])->after('term_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_mappings', function (Blueprint $table) {
            $table->dropColumn('term_type');
        });
    }
}
