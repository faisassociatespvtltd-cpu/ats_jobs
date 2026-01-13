<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labour_laws', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('law'); // law, article, book, qa
            $table->string('title');
            $table->text('content');
            $table->string('country')->nullable();
            $table->string('category')->nullable();
            $table->string('author')->nullable();
            $table->string('source')->nullable();
            $table->date('published_date')->nullable();
            $table->string('isbn')->nullable(); // For books
            $table->text('question')->nullable(); // For Q&A
            $table->text('answer')->nullable(); // For Q&A
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labour_laws');
    }
};
