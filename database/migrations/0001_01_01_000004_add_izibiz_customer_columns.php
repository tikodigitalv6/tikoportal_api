<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Izibiz customer payload'ındaki üst seviye alanlar birebir sütun.
            $table->string('commercialName', 255)->nullable();
            $table->string('firstName', 255)->nullable();
            $table->string('lastName', 255)->nullable();
            $table->string('vknTckno', 64)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('taxOffice', 255)->nullable();

            $table->string('status', 16)->nullable();
            $table->dateTime('statusDate')->nullable();
            $table->string('statusDesc', 255)->nullable();

            $table->string('customerType', 32)->nullable();
            $table->string('configType', 32)->nullable();
            $table->string('configTypeDesc', 255)->nullable();
            $table->string('companyType', 32)->nullable();
            $table->string('companyTypeDesc', 255)->nullable();

            $table->unsignedBigInteger('channelRefId')->nullable();
            $table->unsignedBigInteger('dealerRefId')->nullable();
            $table->unsignedBigInteger('accountRefId')->nullable();
            $table->string('accountRefName', 255)->nullable();
            $table->string('channelRefName', 255)->nullable();
            $table->string('dealerRefName', 255)->nullable();

            $table->string('sicilNo', 64)->nullable();
            $table->string('isletmeMerkezi', 255)->nullable();
            $table->string('mersisNo', 64)->nullable();

            $table->boolean('contractSigned')->nullable();
            $table->text('activationReason')->nullable();
            $table->text('deactivationReason')->nullable();
            $table->unsignedBigInteger('billingCustomerId')->nullable();

            $table->unsignedInteger('tariffType')->nullable();
            $table->string('email', 255)->nullable();

            // addressList: UI'nin kullandığı ilk eleman dahil komple JSON.
            $table->json('addressList')->nullable();

            $table->string('billingPayerFlag', 64)->nullable();
            $table->decimal('progressPaymentRate', 20, 4)->nullable();
            $table->string('tempPassword', 255)->nullable();
            $table->string('earchiveMailFlag', 64)->nullable();
            $table->boolean('bulkTariffFlag')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'commercialName',
                'firstName',
                'lastName',
                'vknTckno',
                'website',
                'taxOffice',
                'status',
                'statusDate',
                'statusDesc',
                'customerType',
                'configType',
                'configTypeDesc',
                'companyType',
                'companyTypeDesc',
                'channelRefId',
                'dealerRefId',
                'accountRefId',
                'accountRefName',
                'channelRefName',
                'dealerRefName',
                'sicilNo',
                'isletmeMerkezi',
                'mersisNo',
                'contractSigned',
                'activationReason',
                'deactivationReason',
                'billingCustomerId',
                'tariffType',
                'email',
                'addressList',
                'billingPayerFlag',
                'progressPaymentRate',
                'tempPassword',
                'earchiveMailFlag',
                'bulkTariffFlag',
            ]);
        });
    }
};

