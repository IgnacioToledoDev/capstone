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

        Schema::create('owner_historical', function (Blueprint $table) {
            $table->id();
            $table->date('start_date_owner');
            $table->date('end_date_owner')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->unsignedBigInteger('car_id')->nullable();
            $table->foreign('car_id')->references('id')->on('cars');
            $table->timestamps();
        });

        Schema::create('mechanic_maintenance', function (Blueprint $table) {
            $table->float('mechanic_stake');
            $table->date('assign_date');
            $table->unsignedBigInteger('mechanic_id')->nullable();
            $table->foreign('mechanic_id')->references('id')->on('users');
            $table->unsignedBigInteger('maintenance_id')->nullable();
            $table->foreign('maintenance_id')->references('id')->on('maintenances');
            $table->timestamps();
        });

        Schema::create('car_model', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('car_brands');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('model');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->foreign('model_id')->references('id')->on('car_model');
        });

        Schema::create('quotation', function (Blueprint $table) {
            $table->id();
            $table->date('approve_date_client')->nullable();
           $table->integer('amount_services');
           $table->integer('total_price');
           $table->boolean('status');
           $table->boolean('is_approved_by_client');
            $table->timestamps();
        });

        Schema::create('maintenance_details', function (Blueprint $table) {
            $table->unsignedBigInteger('maintenance_id')->nullable();
            $table->foreign('maintenance_id')->references('id')->on('maintenances');
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotation');
            $table->timestamps();
        });

        Schema::create('quotation_details', function (Blueprint $table) {
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotation');
            $table->integer('total_services');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services');
            $table->timestamps();
        });

        Schema::create('contact_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('reminder', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_sending')->default(false);
            $table->unsignedBigInteger('contact_type');
            $table->foreign('contact_type')->references('id')->on('contact_type');
            $table->timestamps();
        });

        Schema::create('reservation', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedBigInteger('car_id')->nullable();
            $table->foreign('car_id')->references('id')->on('cars');
            $table->date('date_reservation');
            $table->boolean('is_approved_by_mechanic')->nullable()->default(null);
            $table->boolean('has_reminder')->nullable()->default(true);
            $table->unsignedBigInteger('reminder_id')->nullable();
            $table->foreign('reminder_id')->references('id')->on('reminder');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('mechanic_score')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['model_id']);
            $table->dropColumn('model_id');

            $table->string('model')->nullable();
        });

        // Eliminar las tablas creadas
        Schema::dropIfExists('owner_historical');
        Schema::dropIfExists('mechanic_maintenance');
        Schema::dropIfExists('car_model');
        Schema::dropIfExists('quotation');
        Schema::dropIfExists('maintenance_details');
        Schema::dropIfExists('quotation_details');
        Schema::dropIfExists('reservation');
        Schema::dropIfExists('contact_type');
        Schema::dropIfExists('reminder');

        Schema::table('users', function (Blueprint $table) {
            // Eliminar la columna 'mechanic_score'
            $table->dropColumn('mechanic_score');
        });
    }

};
