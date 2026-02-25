<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Comment\CommentStatusEnum;
use App\Models\Story;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

final class StoryController extends Controller
{
    public function index(): Response
    {
        return inertia('Stories/Index', [
            'stories' => Story::query()
                ->with([
                    'category:id,title',
                    'creator:id,first_name,last_name,username,avatar',
                ])
                ->select([
                    'id', 'category_id', 'creator_id', 'title', 'slug', 'teaser', 'status', 'rating', 'updated_at',
                ])
                ->withCount([
                    'chapters',
                    'comments',
                ])
                ->published()
                ->latest()
                ->get()
                ->toResourceCollection(),
        ]);
    }

    public function show(Story $story): Response
    {
        $story
            ->load([
                'category:id,title',
                'creator:id,first_name,last_name,username,avatar',
                'chapters' => function (HasMany $query): void {
                    $query
                        ->orderBy('position')
                        ->select(['id', 'story_id', 'title', 'status', 'teaser'])
                        ->withCount(['events']);
                },
                'comments' => function (MorphMany $query): void {
                    $query
                        ->with('author')
                        ->where('status', CommentStatusEnum::APPROVED)
                        ->latest()
                        ->select(['id', 'author_type', 'author_id', 'commentable_type', 'commentable_id', 'content', 'created_at']);
                },
            ])
            ->loadCount([
                'chapters',
                'comments',
            ]);

        $existingGameId = null;
        if (Auth::check()) {
            $existingGameId = Auth::user()->games()->where('story_id', $story->id)->value('id');
        }

        return inertia('Stories/Show', [
            'story' => $story->toResource(),
            'existingGameId' => $existingGameId,
        ]);
    }
}
