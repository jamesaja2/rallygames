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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained()->onDelete('cascade');
            $table->string('keterangan'); // Modal, Jual, Beli
            $table->string('kode_soal')->nullable();
            $table->decimal('debet', 10, 2)->default(0);   // Tambah
            $table->decimal('kredit', 10, 2)->default(0);  // Kurang
            $table->decimal('total_saldo', 10, 2);         // Saldo saat transaksi
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
