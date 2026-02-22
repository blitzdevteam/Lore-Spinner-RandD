<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Creator;
use Inertia\Response;

final class CreatorController extends Controller
{
    public function index(): Response
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

    public function show(Creator $creator): Response
    {
        $creator->load([
            'stories:id,creator_id,category_id,title,teaser,slug,rating,status,published_at,created_at,updated_at',
            'stories.category:id,title',
        ]);

        return inertia('Creators/Show', [
            'creator' => $creator->toResource()
        ]);
    }
}
