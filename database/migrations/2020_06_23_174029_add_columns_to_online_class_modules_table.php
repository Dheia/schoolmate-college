<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOnlineClassModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_class_modules', function (Blueprint $table) {
            $table->longText('content_standard')->nullable()->after('description');
            $table->longText('performance_standard')->nullable()->after('content_standard');
            $table->longText('learning_competency')->nullable()->after('performance_standard');
            $table->longText('learning_objective')->nullable()->after('learning_competency');
            $table->longText('resources')->nullable()->after('learning_objective');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_class_modules', function (Blueprint $table) {
            $table->dropColumn('content_standard');
            $table->dropColumn('performance_standard');
            $table->dropColumn('learning_competency');
            $table->dropColumn('learning_objective');
            $table->dropColumn('resources');
        });
    }
}
