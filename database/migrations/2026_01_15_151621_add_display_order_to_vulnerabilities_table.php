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
        Schema::table('vulnerabilities', function (Blueprint $table) {
            $table->integer('display_order')->default(0)->after('pentest_id');
            $table->index(['pentest_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vulnerabilities', function (Blueprint $table) {
            $table->dropIndex(['pentest_id', 'display_order']);
            $table->dropColumn('display_order');
        });
    }
};
