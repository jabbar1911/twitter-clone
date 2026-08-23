<x-app-layout :whoToFollow="$whoToFollow" :search="$search">
    <x-slot:title>
        {{ !empty($search) ? 'Search: '.$search.' / X' : 'Home / X' }}
    </x-slot:title>

    <!-- Top Sticky Header -->
    <header class="sticky top-0 bg-black/80 backdrop-blur-md border-b border-[#2f3336] z-10">
        @if(!empty($search))
            <!-- Search Results Header -->
            <div class="px-4 py-3 flex items-center space-x-4">
                <a href="{{ route('home') }}" class="p-2 rounded-full hover:bg-white/10 text-white transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-white">Search results</h1>
                    <p class="text-xs text-[#71767b] font-medium">{{ $search }}</p>
                </div>
            </div>
        @else
            <!-- Feed Title & Tab Bar -->
            <div class="px-4 pt-3 pb-1 hidden md:block">
                <h1 class="text-xl font-bold text-white">Home</h1>
            </div>

            <!-- Sticky Tab Bar ("For you" vs "Following") -->
            <div class="flex border-t border-[#2f3336] md:border-t-0">
                <!-- "For you" Tab -->
                <a 
                    href="{{ route('home', ['tab' => 'for-you']) }}" 
                    class="flex-1 text-center hover:bg-white/[0.05] transition-colors py-3.5 relative {{ $tab === 'for-you' ? 'font-bold text-white' : 'font-medium text-[#71767b]' }}"
                >
                    <span class="text-sm">For you</span>
                    @if($tab === 'for-you')
                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-14 h-1 bg-[#1d9bf0] rounded-full"></div>
                    @endif
                </a>

                <!-- "Following" Tab -->
                <a 
                    href="{{ route('home', ['tab' => 'following']) }}" 
                    class="flex-1 text-center hover:bg-white/[0.05] transition-colors py-3.5 relative {{ $tab === 'following' ? 'font-bold text-white' : 'font-medium text-[#71767b]' }}"
                >
                    <span class="text-sm">Following</span>
                    @if($tab === 'following')
                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-16 h-1 bg-[#1d9bf0] rounded-full"></div>
                    @endif
                </a>
            </div>
        @endif
    </header>

    <!-- Tweet Composer Box (only on main home tabs) -->
    @if(empty($search))
        <x-tweet-composer />
    @else
        @if(!empty($matchedUsers) && $matchedUsers->count() > 0)
            <!-- Matching People Section -->
            <div class="border-b border-[#2f3336] bg-[#16181c]/40">
                <div class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#71767b]">People</div>
                <div class="divide-y divide-[#2f3336]/60">
                    @foreach($matchedUsers as $matchedUser)
                        <div class="p-4 flex items-center justify-between hover:bg-white/[0.03] transition">
                            <a href="{{ route('profile.show', ['username' => $matchedUser->username]) }}" class="flex items-center space-x-3 min-w-0 pr-3">
                                <img src="{{ $matchedUser->avatar_url }}" alt="{{ $matchedUser->name }}" class="w-12 h-12 rounded-full object-cover shrink-0 bg-neutral-800">
                                <div class="flex flex-col truncate">
                                    <span class="font-bold text-white text-[15px] hover:underline truncate">{{ $matchedUser->name }}</span>
                                    <span class="text-xs text-[#71767b] truncate">{{ '@'.$matchedUser->username }}</span>
                                    @if($matchedUser->bio)
                                        <p class="text-xs text-[#e7e9ea]/90 mt-0.5 line-clamp-1 truncate">{{ $matchedUser->bio }}</p>
                                    @endif
                                </div>
                            </a>

                            @auth
                                @if(auth()->id() !== $matchedUser->id)
                                    @php
                                        $isFollow = auth()->user()->isFollowing($matchedUser);
                                    @endphp
                                    <button 
                                        type="button"
                                        onclick="window.toggleFollow({{ $matchedUser->id }}, this)"
                                        data-following="{{ $isFollow ? 'true' : 'false' }}"
                                        class="follow-btn shrink-0 font-bold text-xs py-1.5 px-4 rounded-full transition {{ $isFollow ? 'bg-transparent text-white border border-[#536471]' : 'bg-white text-black hover:bg-[#e6e6e6]' }}"
                                    >
                                        {{ $isFollow ? 'Following' : 'Follow' }}
                                    </button>
                                @endif
                            @else
                                <a 
                                    href="{{ route('login') }}" 
                                    class="shrink-0 bg-white hover:bg-[#e6e6e6] text-black font-bold text-xs py-1.5 px-4 rounded-full transition"
                                >
                                    Follow
                                </a>
                            @endauth
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <!-- Tweets Timeline Stream -->
    <div class="divide-y divide-[#2f3336]">
        @forelse($tweets as $tweet)
            <x-tweet-card :tweet="$tweet" />
        @empty
            <div class="p-8 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-[#1d9bf0]/10 text-[#1d9bf0] flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.643 4.937c-.835.37-1.732.62-2.675.733.962-.576 1.7-1.49 2.048-2.578-.9.534-1.897.922-2.958 1.13-.85-.904-2.06-1.47-3.4-1.47-2.572 0-4.658 2.086-4.658 4.66 0 .364.042.718.12 1.06-3.873-.195-7.304-2.05-9.602-4.868-.4.69-.63 1.49-.63 2.342 0 1.616.823 3.043 2.072 3.878-.764-.025-1.482-.234-2.11-.583v.06c0 2.257 1.605 4.14 3.737 4.568-.392.106-.803.162-1.227.162-.3 0-.593-.028-.877-.082.593 1.85 2.313 3.198 4.352 3.234-1.595 1.25-3.604 1.995-5.786 1.995-.376 0-.747-.022-1.112-.065 2.062 1.323 4.51 2.093 7.14 2.093 8.57 0 13.255-7.098 13.255-13.254 0-.2-.005-.402-.014-.602.91-.658 1.7-1.477 2.323-2.41z"/>
                    </svg>
                </div>

                @if(!empty($search))
                    <h3 class="text-xl font-bold text-white">No results for "{{ $search }}"</h3>
                    <p class="text-sm text-[#71767b]">Try searching for something else, like a hashtag or keyword.</p>
                @elseif($tab === 'following')
                    @auth
                        <h3 class="text-xl font-bold text-white">Welcome to your timeline!</h3>
                        <p class="text-sm text-[#71767b]">When you follow people, you'll see their Tweets here. Check out the suggestions on the right to get started.</p>
                    @else
                        <h3 class="text-xl font-bold text-white">Don’t miss what’s happening</h3>
                        <p class="text-sm text-[#71767b]">People on Twitter are the first to know. Log in to follow accounts and see their tweets here.</p>
                        <div class="pt-2">
                            <a href="{{ route('login') }}" class="inline-block bg-[#1d9bf0] hover:bg-[#1a8cd8] text-white font-bold text-sm px-6 py-2.5 rounded-full transition">Log in</a>
                        </div>
                    @endauth
                @else
                    <h3 class="text-xl font-bold text-white">No tweets yet</h3>
                    <p class="text-sm text-[#71767b]">Be the first to share something with the world!</p>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tweets->hasPages())
        <div class="p-4 border-t border-[#2f3336]">
            {{ $tweets->links() }}
        </div>
    @endif
</x-app-layout>
