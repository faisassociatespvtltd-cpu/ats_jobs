<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('company_name');
            $table->string('location');
            $table->string('job_type')->default('Full-time'); // Full-time, Part-time, Contract, etc.
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('experience_level')->nullable(); // Entry, Mid, Senior, etc.
            $table->date('posted_date');
            $table->date('closing_date')->nullable();
            $table->enum('status', ['draft', 'active', 'closed', 'cancelled'])->default('draft');
            $table->foreignId('posted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('requirements')->nullable();
            $table->json('benefits')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
