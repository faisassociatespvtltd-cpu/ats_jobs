<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scraped_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('scraped_jobs', 'parsed_data')) {
                $table->json('parsed_data')->nullable()->after('raw_data');
            }
        });

        // Add 'parsed' to enum if it's not already there
        // NOTE: We already added 'approved' in a previous migration, so we should include it here too if we're rewriting the enum
        DB::statement("ALTER TABLE scraped_jobs MODIFY COLUMN status ENUM('pending', 'reviewed', 'imported', 'rejected', 'approved', 'parsed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scraped_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('scraped_jobs', 'parsed_data')) {
                $table->dropColumn('parsed_data');
            }
        });

        DB::statement("ALTER TABLE scraped_jobs MODIFY COLUMN status ENUM('pending', 'reviewed', 'imported', 'rejected', 'approved') DEFAULT 'pending'");
    }
};
