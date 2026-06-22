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
        Schema::create('drops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('label')->nullable(); // e.g., "Live Now: Protocol 001"
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('status')->default('upcoming'); // e.g., 'live', 'upcoming', 'archived'
            $table->string('date_label')->nullable(); // e.g., "28.06"
            $table->string('price_label')->nullable(); // e.g., "Rp 1.250.000"
            $table->integer('units_left')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drops');
    }
};
