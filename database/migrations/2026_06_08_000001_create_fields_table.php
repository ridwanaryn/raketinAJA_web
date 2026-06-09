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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('sport_type'); // padel, tennis, badminton
            $table->decimal('price_per_hour', 8, 2);
            $table->integer('capacity');
            $table->boolean('is_indoor')->default(false);
            $table->json('features')->nullable(); // stored as json array
            $table->text('description')->nullable();
            $table->string('location');
            $table->text('image_url')->nullable();
            $table->string('status')->default('active'); // active, maintenance, cleaning
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
