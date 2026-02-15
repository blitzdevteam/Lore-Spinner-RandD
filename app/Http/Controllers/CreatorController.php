<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Creator;

final class CreatorController extends Controller
{
    public function index()
    {
        return inertia('Creators/Index', [
            'creators' => fn () => Creator::query()
                ->select([
                    'id', 'username', 'first_name', 'last_name', 'avatar'
                ])
                ->latest()
                ->get()
                ->toResourceCollection(),
        ]);
    }

    public function show(Creator $creator)
    {
        $creator->load([
            'stories:id,creator_id,category_id,title,rating,status,published_at,created_at,updated_at',
            'stories.category:id,title',
        ]);

        return inertia('Creators/Show', [
            'creator' => $creator->only(['id', 'first_name', 'last_name', 'avatar', 'stories'])
        ]);
    }
}
