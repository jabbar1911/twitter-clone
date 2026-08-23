<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user profile page.
     */
    public function show(Request $request, string $username): View
    {
        $user = User::where('username', strtolower($username))
            ->withCount(['tweets', 'followers', 'following', 'likes'])
            ->firstOrFail();

        $tab = $request->query('tab', 'tweets');

        if ($tab === 'likes') {
            $tweets = $user->likes()
                ->with(['user', 'likes'])
                ->withCount('likes')
                ->latest('tweet_likes.created_at')
                ->paginate(30)
                ->withQueryString();
        } else {
            $tweets = $user->tweets()
                ->with(['user', 'likes'])
                ->withCount('likes')
                ->latest()
                ->paginate(30)
                ->withQueryString();
        }

        $whoToFollowQuery = User::where('id', '!=', $user->id);
        if (Auth::check()) {
            $currentUser = Auth::user();
            $followingIds = $currentUser->following()->pluck('users.id');
            $whoToFollowQuery->where('id', '!=', $currentUser->id)
                ->whereNotIn('id', $followingIds);
        }
        $whoToFollow = $whoToFollowQuery->inRandomOrder()->take(3)->get();

        return view('profile.show', [
            'user' => $user,
            'tweets' => $tweets,
            'tab' => $tab,
            'whoToFollow' => $whoToFollow,
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user, 401);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:160'],
            'avatar' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'bio' => !empty($validated['bio']) ? trim($validated['bio']) : null,
            'avatar' => !empty($validated['avatar']) ? trim($validated['avatar']) : null,
        ]);

        return redirect()->route('profile.show', ['username' => $user->username])
            ->with('success', 'Profile updated successfully.');
    }
}
