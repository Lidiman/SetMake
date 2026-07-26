<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('artist')->nullable();
            $table->string('genre')->nullable();
            $table->string('key', 10)->nullable();
            $table->unsignedSmallInteger('bpm')->nullable();
            $table->unsignedInteger('duration')->nullable()->comment('Duration in seconds');
            $table->string('difficulty')->nullable();
            $table->string('tuning')->nullable();
            $table->unsignedTinyInteger('capo')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->string('audio_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['title', 'artist']);
            $table->index('genre');
            $table->index('is_favorite');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
