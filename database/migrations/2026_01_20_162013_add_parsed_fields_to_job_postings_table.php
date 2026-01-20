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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->text('responsibilities')->nullable()->after('required_skills');
            $table->text('qualifications')->nullable()->after('responsibilities');
            $table->string('salary_range')->nullable()->after('salary_max');
            $table->json('hard_skills')->nullable()->after('requirements');
            $table->json('soft_skills')->nullable()->after('hard_skills');
            $table->timestamp('parsed_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn([
                'responsibilities',
                'qualifications',
                'salary_range',
                'hard_skills',
                'soft_skills',
                'parsed_at',
            ]);
        });
    }
};
