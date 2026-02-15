<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Writer;

final class WriterController extends Controller
{
    public function index()
    {
        return inertia('Writers/Index', [
            'writers' => fn () => Writer::query()
                ->select([
                    'id', 'username', 'first_name', 'last_name', 'avatar'
                ])
                ->latest()
                ->get()
                ->toResourceCollection(),
        ]);
    }

    public function show(Writer $writer)
    {
        $writer->load([
            'stories:id,writer_id,category_id,title,rating,status,published_at,created_at,updated_at',
            'stories.category:id,title',
        ]);

        return inertia('Writers/Show', [
            'writer' => $writer->only(['id', 'first_name', 'last_name', 'avatar', 'stories'])
        ]);
    }
}
