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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('referral_number')->unique();
            $table->foreignId('rehabilitation_case_id')->constrained('rehabilitation_cases')->cascadeOnDelete();
            $table->foreignId('assessment_id')->constrained('assessments')->restrictOnDelete();
            $table->foreignId('referral_institution_id')->constrained('referral_institutions')->restrictOnDelete();
            $table->foreignId('officer_id')->constrained('users')->restrictOnDelete();
            $table->date('referral_date');
            $table->string('status')->default('draft')->index();
            $table->text('service_result')->nullable();
            $table->timestampTz('completed_at')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
