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
        // Using DB::statement because updating enum columns can be tricky with standard schema builder in some environments
        DB::statement("ALTER TABLE job_postings MODIFY COLUMN status ENUM('draft', 'active', 'closed', 'cancelled', 'inactive') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE job_postings MODIFY COLUMN status ENUM('draft', 'active', 'closed', 'cancelled') DEFAULT 'draft'");
    }
};
