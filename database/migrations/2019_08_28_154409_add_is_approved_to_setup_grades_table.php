<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsApprovedToSetupGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setup_grades', function (Blueprint $table) {
            $table->boolean('is_approved')->nullable()->after('depth');
            $table->integer('approved_by')->after('is_approved')->nullable();
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
            $table->dropColumn('is_approved');
            $table->dropColumn('approved_by');
        });
    }
}
