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
        Schema::table('soal', function (Blueprint $table) {
            $table->enum('tipe_soal', ['Pilihan Ganda', 'Essai', 'Pilihan Ganda Kompleks'])
                  ->default('Pilihan Ganda')
                  ->after('mapel');
            
            // Modify kunci_jawaban to be text for flexibility
            $table->text('kunci_jawaban')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn('tipe_soal');
        });
    }
};
