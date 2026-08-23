<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetLikeController extends Controller
{
    /**
     * Toggle like/unlike for the authenticated user on a tweet.
     */
    public function toggle(Request $request, Tweet $tweet): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('login');
        }

        $isLiked = $tweet->likes()->where('user_id', $user->id)->exists();

        if ($isLiked) {
            $tweet->likes()->detach($user->id);
            $liked = false;
        } else {
            $tweet->likes()->attach($user->id);
            $liked = true;
        }

        $likesCount = $tweet->likes()->count();

        if ($request->wantsJson()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => $likesCount,
            ]);
        }

        return back();
    }
}
