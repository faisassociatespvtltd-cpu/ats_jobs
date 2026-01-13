<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scraped_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('other'); // whatsapp, linkedin, facebook, other
            $table->string('title');
            $table->text('description');
            $table->string('company_name')->nullable();
            $table->string('location')->nullable();
            $table->string('salary')->nullable();
            $table->string('job_type')->nullable();
            $table->string('source_url')->nullable();
            $table->text('raw_data')->nullable(); // JSON of raw scraped data
            $table->enum('status', ['pending', 'reviewed', 'imported', 'rejected'])->default('pending');
            $table->boolean('is_imported')->default(false);
            $table->foreignId('imported_to_job_id')->nullable()->constrained('job_postings')->onDelete('set null');
            $table->timestamp('scraped_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraped_jobs');
    }
};
