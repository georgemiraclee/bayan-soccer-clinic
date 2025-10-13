<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sekolah_bolas', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_pemain_7_8')->default(0);
            $table->unsignedInteger('jumlah_pemain_9_10')->default(0);
            $table->unsignedInteger('jumlah_pemain_11_12')->default(0);
            $table->unsignedInteger('jumlah_pemain_total')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('sekolah_bolas', function (Blueprint $table) {
            $table->dropColumn([
                'jumlah_pemain_7_8',
                'jumlah_pemain_9_10',
                'jumlah_pemain_11_12',
                'jumlah_pemain_total',
            ]);
        });
    }
};
