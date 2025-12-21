<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\Writer;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        return inertia('Index', [
            'writers' => fn () => Writer::query()
                ->select(['id', 'first_name', 'last_name', 'avatar'])
                ->get(),
        ]);
    }
}
