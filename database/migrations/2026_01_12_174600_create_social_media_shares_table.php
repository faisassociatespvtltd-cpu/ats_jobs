<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_media_shares', function (Blueprint $table) {
            $table->id();
            $table->string('shareable_type'); // job_posting, blog, etc.
            $table->unsignedBigInteger('shareable_id');
            $table->string('platform'); // twitter, facebook, linkedin, etc.
            $table->foreignId('shared_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('share_url')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('shared_at');
            $table->timestamps();
            $table->index(['shareable_type', 'shareable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_media_shares');
    }
};
