<?php

declare(strict_types=1);

use App\Enums\StoryChapter\StoryChapterStatusEnum;
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
        Schema::create('story_chapters', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('story_id')->constrained()->cascadeOnUpdate();
            $table->string('title');
            $table->text('overview');
            $table->string('status')->default(StoryChapterStatusEnum::AWAITING_PARAPHRASE_REQUEST->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_chapters');
    }
};
