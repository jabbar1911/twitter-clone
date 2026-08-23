<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow for the authenticated user on a target user.
     */
    public function toggle(Request $request, User $user): RedirectResponse|JsonResponse
    {
        $currentUser = Auth::user();

        if (! $currentUser) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('login');
        }

        if ($currentUser->id === $user->id) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'You cannot follow yourself.'], 422);
            }

            return back()->withErrors(['follow' => 'You cannot follow yourself.']);
        }

        $isFollowing = $currentUser->isFollowing($user);

        if ($isFollowing) {
            $currentUser->following()->detach($user->id);
            $following = false;
        } else {
            $currentUser->following()->attach($user->id);
            $following = true;
        }

        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        if ($request->wantsJson()) {
            return response()->json([
                'following' => $following,
                'followers_count' => $followersCount,
                'following_count' => $followingCount,
            ]);
        }

        return back();
    }
}
