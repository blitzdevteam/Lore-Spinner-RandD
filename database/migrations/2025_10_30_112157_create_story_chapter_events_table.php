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
        Schema::create('story_chapter_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_chapter_id')->constrained()->cascadeOnUpdate();
            $table->string('title');
            $table->text('text');
            $table->text('objectives');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_chapter_events');
    }
};
