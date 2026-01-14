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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['employee', 'employer'])->nullable()->after('email');
            $table->string('cv_path')->nullable()->after('user_type');
            $table->string('company_logo_path')->nullable()->after('cv_path');
            $table->string('email_verification_token')->nullable()->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'cv_path', 'company_logo_path', 'email_verification_token']);
        });
    }
};
