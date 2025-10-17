<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('penilaian_sejawat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilai_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dinilai_id')->constrained('users')->onDelete('cascade');
            $table->integer('nilai')->nullable();
            $table->timestamps();

            $table->unique(['penilai_id', 'dinilai_id']); // agar 1 penilai hanya menilai 1x tiap orang
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_sejawat');
    }
};
