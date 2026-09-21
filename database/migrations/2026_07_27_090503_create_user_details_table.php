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
        Schema::create('user_details', function (Blueprint $table) {
            $table->uuid('user_detail_id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('nik', 16)->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
