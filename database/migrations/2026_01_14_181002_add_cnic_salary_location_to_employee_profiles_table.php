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
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->string('cnic')->nullable()->after('whatsapp_number');
            $table->decimal('expected_salary', 10, 2)->nullable()->after('cnic');
            $table->string('location')->nullable()->after('expected_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropColumn(['cnic', 'expected_salary', 'location']);
        });
    }
};
