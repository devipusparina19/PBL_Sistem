<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'bobot_milestone',
                'value' => '50',
                'description' => 'Bobot nilai milestone (%)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'bobot_nilai_anggota',
                'value' => '50',
                'description' => 'Bobot nilai rata-rata anggota (%)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'minimum_milestone',
                'value' => '1',
                'description' => 'Minimum milestone disetujui untuk hitung nilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'bonus_per_minggu',
                'value' => '5',
                'description' => 'Bonus poin per minggu lebih cepat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'penalty_per_minggu',
                'value' => '5',
                'description' => 'Pengurangan poin per minggu terlambat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
