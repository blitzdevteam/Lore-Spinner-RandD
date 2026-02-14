<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Writer;

final class WriterController extends Controller
{
    public function show(Writer $writer)
    {
        $writer->load([
            'stories:id,category_id,title,rating,status,published_at,created_at,updated_at',
            'stories.category:id,title',
        ]);
        dd($writer);

        return inertia('Writer/Show', [
            'writer' => $writer->only(['id', 'first_name', 'last_name', 'avatar'])
        ]);
    }
}
