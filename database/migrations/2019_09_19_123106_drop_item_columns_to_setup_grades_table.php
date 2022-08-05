<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropItemColumnsToSetupGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setup_grades', function (Blueprint $table) {
            $table->dropColumn('name');           
            $table->dropColumn('description');
            $table->dropColumn('max');
            $table->dropColumn('type');
            $table->dropColumn('parent_id');
            $table->dropColumn('lft');
            $table->dropColumn('rgt');
            $table->dropColumn('depth');
            $table->bigInteger('school_year_id')->unsigned()->after('id');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setup_grades', function (Blueprint $table) {
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('max')->nullable();
            $table->enum('type', ['percent', 'raw']);
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('lft')->nullable();
            $table->bigInteger('rgt')->nullable();
            $table->bigInteger('depth')->nullable();
            $table->dropColumn('school_year_id');
            $table->dropColumn('approved_at');
        });
    }
}
