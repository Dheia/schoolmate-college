<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['accession_number', 'code', 'call_number', 'isbn']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->string('call_number')->nullable()->after('title');
            $table->string('accession_number')->nullable()->after('call_number');
            $table->string('code')->nullable()->after('accession_number');
            $table->string('isbn')->nullable()->after('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['accession_number', 'code', 'call_number', 'isbn']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->string('call_number')->nullable()->after('title');
            $table->string('accession_number')->nullable()->after('call_number');
            $table->string('code')->nullable()->after('accession_number');
            $table->string('isbn')->nullable()->after('code');
        });
    }
}