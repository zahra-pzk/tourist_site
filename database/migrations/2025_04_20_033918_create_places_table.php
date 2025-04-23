<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('عنوان مکان');
            $table->text('description')->nullable()->comment('توضیحات مکان');
            $table->foreignId('province_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('شناسه استان');
            $table->string('image_url')->nullable()->comment('نشانی تصویر');
            $table->string('google_street_view_url')
                  ->nullable()
                  ->comment('URL نمای خیابان گوگل');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
