<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-black/95 backdrop-blur-md border-t border-[#2f3336] flex items-center justify-around py-3 px-4 z-40">
    <!-- Home -->
    <a href="{{ route('home') }}" class="text-white p-2">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="{{ request()->routeIs('home') && !request('search') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
    </a>

    <!-- Explore / Search -->
    <a href="{{ route('explore') }}" class="text-white p-2">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="{{ request()->routeIs('explore') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </a>

    <!-- Compose Button -->
    @auth
        <button type="button" onclick="window.openComposeModal()" class="bg-[#1d9bf0] text-white p-3 rounded-full shadow-lg -mt-5 hover:bg-[#1a8cd8] transition">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M8.8 7.2H5.6V4h3.2v3.2zm0 12.8H5.6v-3.2h3.2V20zm10-12.8h-3.2V4h3.2v3.2zm0 12.8h-3.2v-3.2h3.2V20zM3 2h18v20H3V2z"/>
            </svg>
        </button>
    @else
        <a href="{{ route('login') }}" class="bg-[#1d9bf0] text-white p-3 rounded-full shadow-lg -mt-5">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 4v16m8-8H4"/>
            </svg>
        </a>
    @endauth

    <!-- Profile or Login -->
    @auth
        <a href="{{ route('profile.show', ['username' => auth()->user()->username]) }}" class="text-white p-2">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-6 h-6 rounded-full object-cover">
        </a>
    @else
        <a href="{{ route('login') }}" class="text-white p-2 text-sm font-bold">
            Log in
        </a>
    @endauth
</nav>
