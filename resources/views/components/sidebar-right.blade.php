@props(['whoToFollow' => null, 'search' => null])

<aside class="hidden lg:flex flex-col w-[350px] shrink-0 min-h-screen px-6 py-3 space-y-4">
    <!-- Sticky Search Input -->
    <div class="sticky top-0 bg-black pt-1 pb-2 z-10">
        <form action="{{ route('home') }}" method="GET" class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#71767b]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input 
                type="text" 
                name="search" 
                value="{{ $search ?? '' }}"
                placeholder="Search" 
                class="w-full bg-[#202327] text-white placeholder-[#71767b] text-sm rounded-full pl-11 pr-4 py-2.5 border border-transparent focus:border-[#1d9bf0] focus:bg-black focus:outline-none transition"
            >
            @if(!empty($search))
                <a href="{{ route('home') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#71767b] hover:text-white">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </a>
            @endif
        </form>
    </div>

    <!-- "What's happening" Trends Card -->
    <div class="bg-[#16181c] border border-[#2f3336] rounded-2xl overflow-hidden">
        <div class="px-4 py-3 border-b border-[#2f3336]/60">
            <h2 class="text-xl font-extrabold text-white">What’s happening</h2>
        </div>

        <div class="divide-y divide-[#2f3336]/40">
            <!-- Trend 1 -->
            <a href="{{ route('home', ['search' => '#laravel']) }}" class="block px-4 py-3 hover:bg-white/[0.03] transition">
                <div class="flex items-center justify-between text-xs text-[#71767b]">
                    <span>Technology · Trending</span>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                    </svg>
                </div>
                <div class="font-bold text-white text-[15px] mt-0.5">#laravel</div>
                <div class="text-xs text-[#71767b] mt-0.5">48.2K posts</div>
            </a>

            <!-- Trend 2 -->
            <a href="{{ route('home', ['search' => '#php']) }}" class="block px-4 py-3 hover:bg-white/[0.03] transition">
                <div class="flex items-center justify-between text-xs text-[#71767b]">
                    <span>Web Development · Trending</span>
                </div>
                <div class="font-bold text-white text-[15px] mt-0.5">#php</div>
                <div class="text-xs text-[#71767b] mt-0.5">32.6K posts</div>
            </a>

            <!-- Trend 3 -->
            <a href="{{ route('home', ['search' => '#tailwindcss']) }}" class="block px-4 py-3 hover:bg-white/[0.03] transition">
                <div class="flex items-center justify-between text-xs text-[#71767b]">
                    <span>Design & UI · Trending</span>
                </div>
                <div class="font-bold text-white text-[15px] mt-0.5">#tailwindcss</div>
                <div class="text-xs text-[#71767b] mt-0.5">19.4K posts</div>
            </a>

            <!-- Trend 4 -->
            <a href="{{ route('home', ['search' => '#opensource']) }}" class="block px-4 py-3 hover:bg-white/[0.03] transition">
                <div class="flex items-center justify-between text-xs text-[#71767b]">
                    <span>Developers · Trending</span>
                </div>
                <div class="font-bold text-white text-[15px] mt-0.5">#opensource</div>
                <div class="text-xs text-[#71767b] mt-0.5">14.1K posts</div>
            </a>
        </div>
    </div>

    <!-- "Who to follow" Widget -->
    @if(!empty($whoToFollow) && $whoToFollow->count() > 0)
        <div class="bg-[#16181c] border border-[#2f3336] rounded-2xl overflow-hidden">
            <div class="px-4 py-3 border-b border-[#2f3336]/60">
                <h2 class="text-xl font-extrabold text-white">Who to follow</h2>
            </div>

            <div class="divide-y divide-[#2f3336]/40">
                @foreach($whoToFollow as $suggestedUser)
                    <div class="px-4 py-3 flex items-center justify-between hover:bg-white/[0.03] transition">
                        <a href="{{ route('profile.show', ['username' => $suggestedUser->username]) }}" class="flex items-center space-x-3 min-w-0 pr-2">
                            <img src="{{ $suggestedUser->avatar_url }}" alt="{{ $suggestedUser->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 bg-neutral-800">
                            <div class="flex flex-col truncate">
                                <span class="font-bold text-white text-sm hover:underline truncate">{{ $suggestedUser->name }}</span>
                                <span class="text-xs text-[#71767b] truncate">{{ '@'.$suggestedUser->username }}</span>
                            </div>
                        </a>

                        @auth
                            @php
                                $isFollow = auth()->user()->isFollowing($suggestedUser);
                            @endphp
                            <button 
                                type="button"
                                onclick="window.toggleFollow({{ $suggestedUser->id }}, this)"
                                data-following="{{ $isFollow ? 'true' : 'false' }}"
                                class="follow-btn shrink-0 font-bold text-xs py-1.5 px-4 rounded-full transition {{ $isFollow ? 'bg-transparent text-white border border-[#536471]' : 'bg-white text-black hover:bg-[#e6e6e6]' }}"
                            >
                                {{ $isFollow ? 'Following' : 'Follow' }}
                            </button>
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

    <!-- Footer Meta Links -->
    <div class="px-4 text-xs text-[#71767b] space-y-1 leading-relaxed">
        <div class="flex flex-wrap gap-x-2 gap-y-1">
            <a href="#" class="hover:underline">Terms of Service</a>
            <a href="#" class="hover:underline">Privacy Policy</a>
            <a href="#" class="hover:underline">Cookie Policy</a>
            <a href="#" class="hover:underline">Accessibility</a>
            <a href="#" class="hover:underline">Ads info</a>
        </div>
        <div>&copy; {{ date('Y') }} X Corp. (Twitter Clone)</div>
    </div>
</aside>
