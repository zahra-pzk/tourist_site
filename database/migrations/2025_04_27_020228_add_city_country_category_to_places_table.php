<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->foreignId('city_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('cascade')
                  ->after('province_id');

            $table->foreignId('country_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('cascade')
                  ->after('city_id');

            $table->string('category')
                  ->nullable()
                  ->after('country_id');
        });
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropForeign(['country_id']);
            $table->dropColumn(['city_id', 'country_id', 'category']);
        });
    }
};
