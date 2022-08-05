<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTermTypeToSubjectMappingsTable extends Migration
{
    public function __construct ()
    {
        \DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_mappings', function (Blueprint $table) {
            $table->string('term_type')->change();
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
            $table->enum('term_type', ['Full', 'First', 'Second']);
        });
    }
}
