<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeringkatan', function (Blueprint $table) {
            $table->id(); // id auto increment

            $table->unsignedBigInteger('kelompok_id');
            $table->double('nilai_total')->default(0);
            $table->integer('peringkat')->default(0);

            $table->foreign('kelompok_id')
                  ->references('id_kelompok')
                  ->on('kelompok')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeringkatan');
    }
};
