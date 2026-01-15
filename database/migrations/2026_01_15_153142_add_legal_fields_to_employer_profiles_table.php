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
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->string('license_number')->nullable()->after('company_size');
            $table->string('registration_number')->nullable()->after('license_number');
            $table->string('tax_number')->nullable()->after('registration_number');
            $table->string('company_type')->nullable()->after('tax_number');
            $table->unsignedSmallInteger('founded_year')->nullable()->after('company_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'license_number',
                'registration_number',
                'tax_number',
                'company_type',
                'founded_year',
            ]);
        });
    }
};
