@auth
    <div class="px-4 py-3 border-b border-[#2f3336] flex space-x-3">
        <!-- User Avatar -->
        <a href="{{ route('profile.show', ['username' => auth()->user()->username]) }}" class="shrink-0">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover bg-neutral-800 hover:opacity-90 transition">
        </a>

        <!-- Composer Form -->
        <form action="{{ route('tweets.store') }}" method="POST" class="flex-1 min-w-0" id="main-tweet-form">
            @csrf
            <div>
                <textarea 
                    name="message" 
                    id="main-tweet-message"
                    rows="3" 
                    data-countdown="main-char-count"
                    data-submit-btn="main-tweet-submit-btn"
                    data-max="280"
                    placeholder="What is happening?!" 
                    maxlength="280"
                    required
                    class="w-full bg-transparent text-white placeholder-[#71767b] text-lg resize-none focus:outline-none border-none p-0 focus:ring-0 leading-relaxed"
                >{{ old('message') }}</textarea>
            </div>

            <!-- Bottom Toolbar -->
            <div class="flex items-center justify-between pt-3 border-t border-[#2f3336]/60 mt-2">
                <div></div>

                <!-- Character counter & Post Button -->
                <div class="flex items-center space-x-3">
                    <span id="main-char-count" class="text-xs font-medium text-[#71767b]">280</span>
                    <button 
                        type="submit" 
                        id="main-tweet-submit-btn"
                        disabled
                        class="bg-[#1d9bf0] hover:bg-[#1a8cd8] disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-sm px-4 py-2 rounded-full transition duration-200"
                    >
                        Post
                    </button>
                </div>
            </div>
        </form>
    </div>
@else
    <div class="p-4 border-b border-[#2f3336] bg-[#16181c]/50 text-center">
        <p class="text-[#71767b] text-sm">
            <a href="{{ route('login') }}" class="text-[#1d9bf0] font-bold hover:underline">Log in</a> or 
            <a href="{{ route('register') }}" class="text-[#1d9bf0] font-bold hover:underline">Sign up</a> to join the conversation and post tweets.
        </p>
    </div>
@endauth
