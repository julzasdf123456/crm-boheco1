<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCrmElectricians extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_Electricians', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('IDNumber', 500)->nullable();
            $table->string('Name', 500)->nullable();
            $table->string('Address', 500)->nullable();
            $table->string('ContactNumber', 500)->nullable();
            $table->string('BankNumber', 500)->nullable();
            $table->string('Bank', 500)->nullable();
            $table->string('Town', 500)->nullable();
            $table->string('Barangay', 500)->nullable();
            $table->string('District', 500)->nullable();
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
        Schema::dropIfExists('CRM_Electricians');
    }
}
