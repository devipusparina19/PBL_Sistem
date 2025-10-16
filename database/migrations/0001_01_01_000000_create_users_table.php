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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Data utama
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // Tambahan data identitas
            $table->string('nim_nip')->nullable();       // untuk mahasiswa/dosen
            $table->string('kelas')->nullable();         // hanya untuk mahasiswa

            // Role dan peran dalam kelompok
            $table->string('role')->default('mahasiswa');      // admin/dosen/koor/mahasiswa
            $table->string('role_kelompok')->nullable();       // misal "Kelompok 1"
            $table->string('role_di_kelompok')->nullable();    // misal "Ketua", "Anggota"

            // Tambahan lainnya
            $table->string('photo')->nullable();

            // Laravel default
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Session table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
