<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Story;
use Inertia\Response;

final class IndexController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Index', [
            'creators' => fn() => Creator::query()
                ->select([
                    'id', 'username', 'first_name', 'last_name', 'avatar'
                ])
                ->withCount([
                    'stories'
                ])
                ->take(3)
                ->latest()
                ->get()
                ->toResourceCollection(),
            'stories' => fn() => Story::query()
                ->with([
                    'category:id,title',
                    'creator:id,first_name,last_name'
                ])
                ->select([
                    'id', 'category_id', 'creator_id', 'title', 'slug', 'teaser', 'status', 'rating', 'updated_at'
                ])
                ->withCount([
                    'chapters',
                    'comments'
                ])
                ->published()
                ->get()
                ->toResourceCollection(),
        ]);
    }
}
