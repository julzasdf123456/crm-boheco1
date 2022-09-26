<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCrmBillDeposits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_BillDeposits', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId')->nullable();
            $table->string('Load')->nullable();
            $table->string('PowerFactor')->nullable();
            $table->string('DemandFactor')->nullable();
            $table->string('Hours')->nullable();
            $table->string('AverageRate')->nullable();
            $table->string('AverageTransmission')->nullable();
            $table->string('AverageDemand')->nullable();
            $table->string('BillDepositAmount')->nullable();
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
        Schema::dropIfExists('CRM_BillDeposits');
    }
}
