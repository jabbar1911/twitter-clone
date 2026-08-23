<aside class="w-[68px] xl:w-[275px] shrink-0 h-screen sticky top-0 flex flex-col justify-between p-2 xl:px-4 xl:py-3 border-r border-[#2f3336] z-20">
    <div class="flex flex-col items-center xl:items-start space-y-1">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="p-3 rounded-full hover:bg-white/10 text-white transition-colors duration-200 inline-flex items-center justify-center">
            <x-x-logo class="w-8 h-8" />
        </a>

        <!-- Nav Links -->
        <nav class="flex flex-col space-y-1 w-full mt-2">
            <!-- Home -->
            <a href="{{ route('home') }}" class="flex items-center space-x-4 p-3 rounded-full hover:bg-white/10 transition-colors duration-200 group {{ request()->routeIs('home') && !request('search') ? 'font-bold' : 'font-medium' }}">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="{{ request()->routeIs('home') && !request('search') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="hidden xl:inline text-xl">Home</span>
            </a>

            <!-- Explore / Search -->
            <a href="{{ route('explore') }}" class="flex items-center space-x-4 p-3 rounded-full hover:bg-white/10 transition-colors duration-200 group {{ request()->routeIs('explore') ? 'font-bold' : 'font-medium' }}">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="{{ request()->routeIs('explore') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="hidden xl:inline text-xl">Explore</span>
            </a>

            @auth
                <!-- Profile -->
                <a href="{{ route('profile.show', ['username' => auth()->user()->username]) }}" class="flex items-center space-x-4 p-3 rounded-full hover:bg-white/10 transition-colors duration-200 group {{ request()->is('@'.auth()->user()->username.'*') ? 'font-bold' : 'font-medium' }}">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="{{ request()->is('@'.auth()->user()->username.'*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="hidden xl:inline text-xl">Profile</span>
                </a>
            @endauth
        </nav>

        <!-- Post / Tweet Action Button -->
        <div class="w-full mt-4">
            @auth
                <button type="button" onclick="window.openComposeModal()" class="w-full bg-[#1d9bf0] hover:bg-[#1a8cd8] text-white font-bold py-3.5 px-4 rounded-full transition duration-200 flex items-center justify-center shadow-md">
                    <!-- Icon for collapsed view -->
                    <svg class="w-6 h-6 xl:hidden" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M8.8 7.2H5.6V4h3.2v3.2zm0 12.8H5.6v-3.2h3.2V20zm10-12.8h-3.2V4h3.2v3.2zm0 12.8h-3.2v-3.2h3.2V20zM3 2h18v20H3V2z"/>
                    </svg>
                    <!-- Text for expanded view -->
                    <span class="hidden xl:inline text-[17px]">Post</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="w-full bg-[#1d9bf0] hover:bg-[#1a8cd8] text-white font-bold py-3.5 px-4 rounded-full transition duration-200 flex items-center justify-center text-[17px]">
                    <span class="hidden xl:inline">Log in</span>
                    <svg class="w-6 h-6 xl:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                </a>
            @endauth
        </div>
    </div>

    <!-- Bottom User Section -->
    <div class="w-full relative mb-2">
        @auth
            <div class="group relative">
                <button type="button" onclick="document.getElementById('user-menu-dropdown').classList.toggle('hidden')" class="w-full flex items-center justify-between p-2.5 rounded-full hover:bg-white/10 transition-colors duration-200 cursor-pointer">
                    <div class="flex items-center space-x-3 min-w-0">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 bg-neutral-800">
                        <div class="hidden xl:flex flex-col text-left truncate leading-tight">
                            <span class="font-bold text-white text-sm truncate flex items-center gap-1">
                                {{ auth()->user()->name }}
                            </span>
                            <span class="text-[#71767b] text-xs truncate">{{ '@'.auth()->user()->username }}</span>
                        </div>
                    </div>
                    <svg class="hidden xl:block w-5 h-5 text-[#71767b]" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="5" cy="12" r="2"/>
                        <circle cx="12" cy="12" r="2"/>
                        <circle cx="19" cy="12" r="2"/>
                    </svg>
                </button>

                <!-- Dropdown -->
                <div id="user-menu-dropdown" class="hidden absolute bottom-full left-0 mb-2 w-64 bg-black border border-[#2f3336] rounded-2xl shadow-xl overflow-hidden py-2 z-50">
                    <a href="{{ route('profile.show', ['username' => auth()->user()->username]) }}" class="flex items-center px-4 py-3 text-sm text-white hover:bg-white/10 transition">
                        View Profile ({{ '@'.auth()->user()->username }})
                    </a>
                    <hr class="border-[#2f3336] my-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm font-bold text-[#f4212e] hover:bg-white/10 transition">
                            Log out {{ '@'.auth()->user()->username }}
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="flex flex-col space-y-2">
                <a href="{{ route('login') }}" class="hidden xl:flex items-center justify-center border border-[#536471] hover:bg-white/10 text-white font-bold py-2.5 px-4 rounded-full text-sm transition">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="hidden xl:flex items-center justify-center bg-white hover:bg-[#e6e6e6] text-black font-bold py-2.5 px-4 rounded-full text-sm transition">
                    Sign up
                </a>
            </div>
        @endauth
    </div>
</aside>
