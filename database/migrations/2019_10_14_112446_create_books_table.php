<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('code');
            $table->string('title');

            // $table->unsignedInteger('book_author_id');
            $table->unsignedInteger('book_category_id');
            // $table->unsignedInteger('book_subject_tag_id');
            $table->string('edition')->nullable();
            $table->string('year_published')->nullable();
            $table->string('publisher')->nullable();
            $table->string('isbn')->nullable();

            $table->string('accession_number')->unique();
            $table->string('call_number');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
