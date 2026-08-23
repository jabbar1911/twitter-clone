<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TweetController extends Controller
{
    /**
     * Display a listing of tweets (Home feed with tabs & search).
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'for-you');
        $search = $request->query('search');

        $query = Tweet::with(['user', 'likes'])
            ->withCount('likes')
            ->latest();

        if ($tab === 'following') {
            if (Auth::check()) {
                $followingIds = Auth::user()->following()->pluck('users.id');
                $query->whereIn('user_id', $followingIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $matchedUsers = collect();
        if ($search) {
            $cleanSearch = ltrim(trim($search), '@');
            $matchedUsers = User::where('username', 'like', '%'.$cleanSearch.'%')
                ->orWhere('name', 'like', '%'.$cleanSearch.'%')
                ->orWhere('bio', 'like', '%'.$cleanSearch.'%')
                ->take(4)
                ->get();

            $query->where(function ($q) use ($search, $cleanSearch) {
                $q->where('message', 'like', '%'.$search.'%')
                  ->orWhere('message', 'like', '%'.$cleanSearch.'%');
            });
        }

        $tweets = $query->paginate(30)->withQueryString();

        // Suggested users for "Who to follow" sidebar
        $whoToFollowQuery = User::query();
        if (Auth::check()) {
            $currentUser = Auth::user();
            $followingIds = $currentUser->following()->pluck('users.id');
            $whoToFollowQuery->where('id', '!=', $currentUser->id)
                ->whereNotIn('id', $followingIds);
        }
        $whoToFollow = $whoToFollowQuery->inRandomOrder()->take(3)->get();

        return view('tweets.index', [
            'tweets' => $tweets,
            'tab' => $tab,
            'search' => $search,
            'matchedUsers' => $matchedUsers,
            'whoToFollow' => $whoToFollow,
        ]);
    }

    /**
     * Display the Explore page with trends, search, and top tweets.
     */
    public function explore(Request $request): View
    {
        $search = $request->query('search');

        $query = Tweet::with(['user', 'likes'])
            ->withCount('likes')
            ->latest();

        $matchedUsers = collect();
        if ($search) {
            $cleanSearch = ltrim(trim($search), '@');
            $matchedUsers = User::where('username', 'like', '%'.$cleanSearch.'%')
                ->orWhere('name', 'like', '%'.$cleanSearch.'%')
                ->orWhere('bio', 'like', '%'.$cleanSearch.'%')
                ->take(5)
                ->get();

            $query->where(function ($q) use ($search, $cleanSearch) {
                $q->where('message', 'like', '%'.$search.'%')
                  ->orWhere('message', 'like', '%'.$cleanSearch.'%');
            });
        }

        $tweets = $query->paginate(30)->withQueryString();

        $whoToFollowQuery = User::query();
        if (Auth::check()) {
            $currentUser = Auth::user();
            $followingIds = $currentUser->following()->pluck('users.id');
            $whoToFollowQuery->where('id', '!=', $currentUser->id)
                ->whereNotIn('id', $followingIds);
        }
        $whoToFollow = $whoToFollowQuery->inRandomOrder()->take(3)->get();

        return view('explore', [
            'tweets' => $tweets,
            'search' => $search,
            'matchedUsers' => $matchedUsers,
            'whoToFollow' => $whoToFollow,
        ]);
    }

    /**
     * Store a newly created tweet.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:280'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tweets', 'public');
        }

        $tweet = Auth::user()->tweets()->create([
            'message' => trim($validated['message']),
            'image' => $imagePath,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tweet posted successfully.',
                'tweet' => $tweet->load('user'),
            ]);
        }

        return redirect()->route('home')->with('success', 'Your Tweet was sent.');
    }

    /**
     * Show the form for editing the specified tweet.
     */
    public function edit(Tweet $tweet): View
    {
        abort_if($tweet->user_id !== Auth::id(), 403, 'Unauthorized action.');

        return view('tweets.edit', [
            'tweet' => $tweet,
        ]);
    }

    /**
     * Update the specified tweet.
     */
    public function update(Request $request, Tweet $tweet): RedirectResponse|JsonResponse
    {
        abort_if($tweet->user_id !== Auth::id(), 403, 'Unauthorized action.');

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:280'],
        ]);

        $tweet->update([
            'message' => trim($validated['message']),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tweet updated.',
                'tweet' => $tweet,
            ]);
        }

        return redirect()->route('home')->with('success', 'Tweet updated successfully.');
    }

    /**
     * Remove the specified tweet.
     */
    public function destroy(Request $request, Tweet $tweet): RedirectResponse|JsonResponse
    {
        abort_if($tweet->user_id !== Auth::id(), 403, 'Unauthorized action.');

        $tweet->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tweet deleted.',
            ]);
        }

        return back()->with('success', 'Tweet was deleted.');
    }
}
