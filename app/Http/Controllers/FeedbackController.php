<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class FeedbackController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        Feedback::create([
            'user_id' => $request->user()?->id,
            'content' => $validated['content'],
            'page_url' => $request->header('referer'),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('flash', [
            'success' => ['Thanks for your feedback!'],
        ]);
    }
}
