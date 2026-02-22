<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Story;

final class StoryController extends Controller
{
    public function index()
    {
        return inertia('Stories/Index', [
            'stories' => []
        ]);
    }

    public function show(Story $story)
    {
        $story
            ->load([
                'category:id,title',
                'creator:id,first_name,last_name,username,avatar',
                'chapters' => function ($query): void {
                    $query
                        ->orderBy('position')
                        ->select(['id', 'story_id', 'title', 'status', 'teaser'])
                        ->withCount(['events']);
                },
            ])
            ->loadCount([
                'chapters',
                'comments'
            ]);

        return inertia('Stories/Show', [
            'story' => $story->toResource()
        ]);
    }
}
