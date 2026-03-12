<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class BookmarkController extends Controller
{
    public function toggle(Story $story): JsonResponse
    {
        $user = Auth::user();

        $result = $user->bookmarkedStories()->toggle($story->id);

        $isBookmarked = ! empty($result['attached']);

        return response()->json([
            'is_bookmarked' => $isBookmarked,
        ]);
    }
}
