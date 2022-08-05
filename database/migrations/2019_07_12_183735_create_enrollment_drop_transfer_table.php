<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollmentDropTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollment_drop_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enrollment_id');
            $table->enum('type', ['Dropping', 'Transferring']);
            $table->longText('tuition_fees');
            $table->longText('miscellaneous');
            $table->longText('activities_fee');
            $table->longText('other_fees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollment_drop_transfer');
    }
}
