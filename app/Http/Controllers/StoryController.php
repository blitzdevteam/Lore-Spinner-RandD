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
        return inertia('Stories/Show', [
            'story' => $story->toResource()
        ]);
    }
}
