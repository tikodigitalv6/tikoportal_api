<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            // Izibiz tarafındaki müşteri id'si (integer).
            $table->unsignedBigInteger('id')->primary();

            // Müşteri kaydının tamamını JSON olarak tutuyoruz.
            $table->json('data');

            // API'nin mevcut search mantığını DB'de hızlıca uygulamak için.
            $table->text('search_text')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

