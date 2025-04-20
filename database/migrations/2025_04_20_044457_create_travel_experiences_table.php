<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('place_id')->constrained()->onDelete('cascade');
            $table->boolean('has_traveled')->default(false);
            $table->string('travel_time')->nullable();
            $table->json('liked_points')->nullable();
            $table->json('disliked_points')->nullable();
            $table->json('suitable_for')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->text('extra_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_experiences');
    }
};
