<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Response;

final class StoryController extends Controller
{
    public function index(): Response
    {
        return inertia('Stories/Index', [
            'stories' => [],
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
            ])
            ->loadCount([
                'chapters',
                'comments',
            ]);

        return inertia('Stories/Show', [
            'story' => $story->toResource(),
        ]);
    }
}
