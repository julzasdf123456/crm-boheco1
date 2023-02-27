<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSmsNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SMS_Notifications', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('Source')->nullable();
            $table->string('SourceId', 300)->nullable();
            $table->string('ContactNumber', 300)->nullable();
            $table->string('Message', 3000)->nullable();
            $table->string('Status')->nullable();
            $table->string('AIFacilitator')->nullable();
            $table->string('Notes', 300)->nullable();
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
        Schema::dropIfExists('SMS_Notifications');
    }
}
