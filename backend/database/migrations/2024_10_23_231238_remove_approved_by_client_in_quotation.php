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
        Schema::table('quotation', function (Blueprint $table) {
            $table->dropColumn('is_approved_by_client');
        });

        Schema::table('quotation_details', function (Blueprint $table) {
            $table->boolean('is_approved_by_client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation', function (Blueprint $table) {
            $table->boolean('is_approved_by_client');
        });

        Schema::table('quotation_details', function (Blueprint $table) {
            $table->dropColumn('is_approved_by_client');
        });
    }
};
