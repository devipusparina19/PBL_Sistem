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
    Schema::create('milestones', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->string('judul');
        $table->string('kelompok');
        $table->text('rincian');
        $table->string('foto')->nullable(); // untuk upload dokumentasi
        $table->timestamps();
    });
}

};
