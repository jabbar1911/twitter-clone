<x-app-layout :whoToFollow="$whoToFollow">
    <x-slot:title>
        {{ "{$user->name} (@{$user->username}) / X" }}
    </x-slot:title>

    <!-- Top Sticky Header -->
    <header class="sticky top-0 bg-black/85 backdrop-blur-md border-b border-[#2f3336] z-30 px-4 py-2 flex items-center space-x-6">
        <a href="{{ route('home') }}" class="p-2 rounded-full hover:bg-white/10 text-white transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div class="truncate">
            <h1 class="text-xl font-extrabold text-white flex items-center gap-1 truncate">
                {{ $user->name }}
                <svg class="w-4 h-4 text-[#1d9bf0] shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.79-4-4-4-.495 0-.965.084-1.4.238C14.55 2.475 13.18 1.6 11.6 1.6c-1.58 0-2.95.875-3.6 2.148-.435-.154-.905-.238-1.4-.238-2.21 0-4 1.79-4 4 0 .495.084.965.238 1.4C1.575 9.55.7 10.92.7 12.5c0 1.58.875 2.95 2.148 3.6-.154.435-.238.905-.238 1.4 0 2.21 1.79 4 4 4 .495 0 .965-.084 1.4-.238 1.25 1.873 2.62 2.748 4.2 2.748 1.58 0 2.95-.875 3.6-2.148.435.154.905.238 1.4.238 2.21 0 4-1.79 4-4 0-.495-.084-.965-.238-1.4 1.273-.65 2.148-2.02 2.148-3.6zm-12.8 4.7l-4.2-4.2 1.4-1.4 2.8 2.8 6.8-6.8 1.4 1.4-8.2 8.2z"/>
                </svg>
            </h1>
            <p class="text-xs text-[#71767b] font-medium">{{ $user->tweets_count }} Tweets</p>
        </div>
    </header>

    <!-- Profile Header & Banner -->
    <div class="relative">
        <!-- Banner -->
        <div class="h-44 sm:h-52 bg-gradient-to-r from-[#1d9bf0]/30 via-[#1d9bf0]/10 to-neutral-900 border-b border-[#2f3336]"></div>

        <!-- Avatar & Actions Row -->
        <div class="px-4 pb-4">
            <div class="flex items-end justify-between -mt-16 sm:-mt-20 mb-4">
                <!-- Avatar with proper overlay z-index -->
                <div class="relative z-10">
                    <img 
                        src="{{ $user->avatar_url }}" 
                        alt="{{ $user->name }}" 
                        class="w-32 h-32 sm:w-36 sm:h-36 rounded-full border-4 border-black object-cover bg-neutral-800 shadow-lg"
                    >
                </div>

                <!-- Action Button (Edit Profile or Follow/Unfollow) -->
                <div class="pb-2">
                    @auth
                        @if(auth()->id() === $user->id)
                            <button 
                                type="button" 
                                onclick="window.openEditProfileModal()" 
                                class="border border-[#536471] hover:bg-white/10 text-white font-bold text-sm px-4 py-2 rounded-full transition"
                            >
                                Edit profile
                            </button>
                        @else
                            @php
                                $isFollowing = auth()->user()->isFollowing($user);
                            @endphp
                            <button 
                                type="button" 
                                onclick="window.toggleFollow({{ $user->id }}, this)" 
                                data-following="{{ $isFollowing ? 'true' : 'false' }}"
                                class="follow-btn font-bold text-sm px-5 py-2 rounded-full transition {{ $isFollowing ? 'bg-transparent text-white border border-[#536471]' : 'bg-white text-black hover:bg-[#e6e6e6]' }}"
                            >
                                {{ $isFollowing ? 'Following' : 'Follow' }}
                            </button>
                        @endif
                    @else
                        <a 
                            href="{{ route('login') }}" 
                            class="bg-white hover:bg-[#e6e6e6] text-black font-bold text-sm px-5 py-2 rounded-full transition inline-block"
                        >
                            Follow
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Profile Info -->
            <div class="space-y-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-white flex items-center gap-1.5">
                        {{ $user->name }}
                        <svg class="w-5 h-5 text-[#1d9bf0]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.79-4-4-4-.495 0-.965.084-1.4.238C14.55 2.475 13.18 1.6 11.6 1.6c-1.58 0-2.95.875-3.6 2.148-.435-.154-.905-.238-1.4-.238-2.21 0-4 1.79-4 4 0 .495.084.965.238 1.4C1.575 9.55.7 10.92.7 12.5c0 1.58.875 2.95 2.148 3.6-.154.435-.238.905-.238 1.4 0 2.21 1.79 4 4 4 .495 0 .965-.084 1.4-.238 1.25 1.873 2.62 2.748 4.2 2.748 1.58 0 2.95-.875 3.6-2.148.435.154.905.238 1.4.238 2.21 0 4-1.79 4-4 0-.495-.084-.965-.238-1.4 1.273-.65 2.148-2.02 2.148-3.6zm-12.8 4.7l-4.2-4.2 1.4-1.4 2.8 2.8 6.8-6.8 1.4 1.4-8.2 8.2z"/>
                        </svg>
                    </h2>
                    <p class="text-[#71767b] text-sm">{{ '@'.$user->username }}</p>
                </div>

                @if($user->bio)
                    <p class="text-white text-[15px] leading-relaxed whitespace-pre-line">{{ $user->bio }}</p>
                @endif

                <!-- Meta Details (Join Date) -->
                <div class="flex items-center text-[#71767b] text-sm space-x-4">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span>Joined {{ $user->created_at->format('F Y') }}</span>
                    </div>
                </div>

                <!-- Follower / Following Stats -->
                <div class="flex items-center space-x-5 text-sm pt-1">
                    <div class="flex items-center space-x-1">
                        <span class="font-bold text-white">{{ $user->following_count }}</span>
                        <span class="text-[#71767b]">Following</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span id="profile-follower-count" class="font-bold text-white">{{ $user->followers_count }}</span>
                        <span class="text-[#71767b]">Followers</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Tabs (Tweets vs Likes) -->
        <div class="flex border-b border-[#2f3336]">
            <!-- Tweets Tab -->
            <a 
                href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'tweets']) }}" 
                class="flex-1 text-center hover:bg-white/[0.05] transition-colors py-3.5 relative {{ $tab === 'tweets' ? 'font-bold text-white' : 'font-medium text-[#71767b]' }}"
            >
                <span class="text-sm">Tweets</span>
                @if($tab === 'tweets')
                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-14 h-1 bg-[#1d9bf0] rounded-full"></div>
                @endif
            </a>

            <!-- Likes Tab -->
            <a 
                href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'likes']) }}" 
                class="flex-1 text-center hover:bg-white/[0.05] transition-colors py-3.5 relative {{ $tab === 'likes' ? 'font-bold text-white' : 'font-medium text-[#71767b]' }}"
            >
                <span class="text-sm">Likes</span>
                @if($tab === 'likes')
                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-14 h-1 bg-[#1d9bf0] rounded-full"></div>
                @endif
            </a>
        </div>
    </div>

    <!-- Timeline of Tweets / Likes -->
    <div class="divide-y divide-[#2f3336]">
        @forelse($tweets as $tweet)
            <x-tweet-card :tweet="$tweet" />
        @empty
            <div class="p-8 text-center space-y-2">
                @if($tab === 'likes')
                    <h3 class="text-xl font-bold text-white">{{ '@'.$user->username }} hasn’t liked any Tweets</h3>
                    <p class="text-sm text-[#71767b]">When they do, those Tweets will show up here.</p>
                @else
                    <h3 class="text-xl font-bold text-white">{{ '@'.$user->username }} hasn’t posted</h3>
                    <p class="text-sm text-[#71767b]">When they post Tweets, they’ll show up here.</p>
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
