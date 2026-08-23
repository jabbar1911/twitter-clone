<x-app-layout :whoToFollow="$whoToFollow" :search="$search">
    <x-slot:title>
        {{ !empty($search) ? 'Search: '.$search.' / X' : 'Explore / X' }}
    </x-slot:title>

    <!-- Top Sticky Search Header -->
    <header class="sticky top-0 bg-black/85 backdrop-blur-md border-b border-[#2f3336] z-20 px-4 py-2.5">
        <form action="{{ route('explore') }}" method="GET" class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#71767b]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input 
                type="text" 
                name="search" 
                value="{{ $search ?? '' }}"
                placeholder="Search keywords, @handles, #hashtags..." 
                class="w-full bg-[#202327] text-white placeholder-[#71767b] text-sm rounded-full pl-11 pr-10 py-2.5 border border-transparent focus:border-[#1d9bf0] focus:bg-black focus:outline-none transition"
                autofocus
            >
            @if(!empty($search))
                <a href="{{ route('explore') }}" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#71767b] hover:text-white" title="Clear search">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </a>
            @endif
        </form>
    </header>

    @if(empty($search))
        <!-- Explore Featured Hero & Trending Topics -->
        <div class="border-b border-[#2f3336]">
            <!-- Hero Topic Banner -->
            <a href="{{ route('explore', ['search' => '#laravel']) }}" class="block relative h-48 sm:h-56 bg-gradient-to-tr from-[#1d9bf0]/40 via-purple-900/30 to-neutral-900 overflow-hidden group">
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition duration-300"></div>
                <div class="absolute bottom-4 left-4 right-4 text-white">
                    <span class="text-xs font-semibold uppercase tracking-wider text-[#1d9bf0] bg-black/60 px-2.5 py-1 rounded-md">Trending in Tech</span>
                    <h2 class="text-xl sm:text-2xl font-black mt-2 leading-snug group-hover:underline">Laravel 12 Ecosystem & Modern Web Development</h2>
                    <p class="text-xs text-[#e7e9ea]/80 mt-1">48.2K posts · Trending with #laravel, #php, #webdev</p>
                </div>
            </a>

            <!-- Quick Filter Chips -->
            <div class="flex items-center space-x-2 px-4 py-3 overflow-x-auto no-scrollbar text-xs font-bold">
                <a href="{{ route('explore', ['search' => '#laravel']) }}" class="px-3.5 py-1.5 rounded-full bg-[#202327] hover:bg-[#2f3336] text-white transition shrink-0">#laravel</a>
                <a href="{{ route('explore', ['search' => '#php']) }}" class="px-3.5 py-1.5 rounded-full bg-[#202327] hover:bg-[#2f3336] text-white transition shrink-0">#php</a>
                <a href="{{ route('explore', ['search' => '#tailwindcss']) }}" class="px-3.5 py-1.5 rounded-full bg-[#202327] hover:bg-[#2f3336] text-white transition shrink-0">#tailwindcss</a>
                <a href="{{ route('explore', ['search' => '#opensource']) }}" class="px-3.5 py-1.5 rounded-full bg-[#202327] hover:bg-[#2f3336] text-white transition shrink-0">#opensource</a>
                <a href="{{ route('explore', ['search' => '#webdev']) }}" class="px-3.5 py-1.5 rounded-full bg-[#202327] hover:bg-[#2f3336] text-white transition shrink-0">#webdev</a>
            </div>
        </div>
    @else
        <!-- Search Results Count Info -->
        <div class="px-4 py-3 bg-[#16181c]/60 border-b border-[#2f3336] flex items-center justify-between text-sm">
            <span class="text-[#71767b]">Results for <strong class="text-white">"{{ $search }}"</strong></span>
            <span class="text-xs text-[#1d9bf0] font-medium">{{ $tweets->total() }} posts found</span>
        </div>

        @if($matchedUsers->count() > 0)
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

    <!-- Tweets Stream -->
    <div class="divide-y divide-[#2f3336]">
        @forelse($tweets as $tweet)
            <x-tweet-card :tweet="$tweet" />
        @empty
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-[#1d9bf0]/10 text-[#1d9bf0] flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">No results found</h3>
                <p class="text-sm text-[#71767b]">Try searching for different keywords or explore trending hashtags above.</p>
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
