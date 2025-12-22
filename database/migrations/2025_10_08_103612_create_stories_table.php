<?php

declare(strict_types=1);

use App\Enums\Story\StoryStatusEnum;
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
        Schema::create('stories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('writer_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->string('title');
            $table->text('overview');
            $table->string('status')->default(StoryStatusEnum::DRAFT->value);
            $table->string('rating');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
