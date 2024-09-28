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
        Schema::create('tenant', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('administrator');
            $table->foreign('administrator')->references('id')->on('users')->onDelete('cascade');
            $table->string('address');
            $table->integer('number_of_employees')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->json('services_offered')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant');
    }
};
