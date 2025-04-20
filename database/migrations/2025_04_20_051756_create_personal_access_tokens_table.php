<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('tokenable_type')->comment('Model class token is assigned to');
            $table->unsignedBigInteger('tokenable_id')->comment('ID of the model');
            $table->string('name')->comment('Human‑readable token name');
            $table->string('token', 64)
                  ->unique()
                  ->comment('Hashed token value');
            $table->text('abilities')
                  ->nullable()
                  ->comment('List of token abilities (scopes)');
            $table->timestamp('last_used_at')
                  ->nullable()
                  ->index('pat_last_used_at_index')
                  ->comment('When this token was last used');
            $table->timestamp('expires_at')
                  ->nullable()
                  ->index('pat_expires_at_index')
                  ->comment('When this token should expire');
            $table->timestamps();
            $table->index(
                ['tokenable_type', 'tokenable_id'],
                'pat_tokenable_type_id_index'
            );
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};