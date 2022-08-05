<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfitsLossStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profits_loss_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_expenses')->nullable();
            $table->enum('hierarchy_type', ['Group', 'Account']);
            $table->integer('group_id')->unsigned()->nullable();
            $table->integer('tax_code')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('profits_loss_statements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profits_loss_statements');
    }
}
