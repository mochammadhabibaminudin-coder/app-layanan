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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('client_category_id')->constrained('client_categories')->restrictOnDelete();
            $table->char('nik', 16)->nullable()->index();
            $table->date('birth_date')->nullable();
            $table->string('gender')->default('male');
            $table->text('address')->nullable();
            $table->foreignId('village_id')->nullable()->constrained('villages')->restrictOnDelete();
            $table->string('phone')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
