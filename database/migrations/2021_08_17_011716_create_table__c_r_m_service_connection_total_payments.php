<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCRMServiceConnectionTotalPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_ServiceConnectionTotalPayments', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('ServiceConnectionId');
            $table->string('SubTotal', 60)->nullable();
            $table->string('Form2307TwoPercent', 60)->nullable();
            $table->string('Form2307FivePercent', 60)->nullable();
            $table->string('TotalVat', 60)->nullable();
            $table->string('Total', 60)->nullable();
            $table->string('Notes', 1000)->nullable();
            $table->string('ServiceConnectionFee', 60)->nullable();
            $table->string('BillDeposit', 60)->nullable();
            $table->string('WitholdableVat', 60)->nullable();
            $table->string('LaborCharge', 60)->nullable();
            $table->decimal('BOHECOShare', 15, 2)->nullable();
            $table->decimal('Particulars', 12, 2)->nullable();
            $table->string('BOHECOShareOnly', 12)->nullable();
            $table->string('ElectricianShareOnly', 12)->nullable();
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
        Schema::dropIfExists('CRM_ServiceConnectionTotalPayments');
    }
}
