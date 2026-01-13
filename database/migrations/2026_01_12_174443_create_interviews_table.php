<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->foreignId('job_posting_id')->constrained('job_postings')->onDelete('cascade');
            $table->dateTime('scheduled_at');
            $table->string('interview_type')->default('In-person'); // In-person, Video, Phone
            $table->string('location')->nullable();
            $table->text('meeting_link')->nullable();
            $table->foreignId('interviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->text('feedback')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
