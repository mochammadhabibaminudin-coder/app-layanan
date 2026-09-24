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
        Schema::create('information_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('program')->index();
            $table->foreignId('service_type_id')->nullable()->constrained('service_types')->nullOnDelete();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('procedure')->nullable();
            $table->string('service_hours')->nullable();
            $table->string('location')->nullable();
            $table->string('contact')->nullable();
            $table->string('publish_status')->default('draft')->index();
            $table->timestampTz('published_at')->nullable()->index();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information_pages');
    }
};
