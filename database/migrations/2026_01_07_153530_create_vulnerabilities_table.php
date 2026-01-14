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
        Schema::create('vulnerabilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pentest_id');
            $table->text('description');
            $table->enum('criticality', ['critical', 'high', 'medium', 'low', 'informative']);
            $table->boolean('is_resolved')->default(false);
            $table->date('resolved_at')->nullable();
            $table->longText('recommendations')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('pentest_id')->references('id')->on('pentests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vulnerabilities');
    }
};
