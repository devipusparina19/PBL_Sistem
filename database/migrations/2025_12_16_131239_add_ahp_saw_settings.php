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
        // Insert default SAW weights for ranking criteria
        $defaults = [
            'saw_weight_it_project' => 20,
            'saw_weight_pwl' => 20,
            'saw_weight_integrasi' => 20,
            'saw_weight_tpk' => 20,
            'saw_weight_proyek' => 10,
            'saw_weight_sejawat' => 10,
            'ranking_method' => 'saw', // 'saw' or 'average'
        ];

        foreach ($defaults as $key => $value) {
            \App\Models\Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'saw_weight_it_project',
            'saw_weight_pwl',
            'saw_weight_integrasi',
            'saw_weight_tpk',
            'saw_weight_proyek',
            'saw_weight_sejawat',
            'ranking_method',
        ];

        \App\Models\Setting::whereIn('key', $keys)->delete();
    }
};
