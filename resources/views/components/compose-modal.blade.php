@auth
<div id="compose-modal" class="hidden fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 items-start justify-center p-4 pt-16">
    <div class="bg-black border border-[#2f3336] rounded-2xl max-w-lg w-full p-4 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-[#2f3336]">
            <button type="button" onclick="window.closeComposeModal()" class="text-[#71767b] hover:text-white p-1 rounded-full">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
            <span class="text-xs font-bold text-[#1d9bf0]">New Tweet</span>
        </div>

        <form action="{{ route('tweets.store') }}" method="POST" class="mt-3">
            @csrf
            <div class="flex space-x-3">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 bg-neutral-800">
                <div class="flex-1">
                    <textarea 
                        name="message" 
                        id="modal-tweet-message"
                        rows="4" 
                        data-countdown="modal-char-count"
                        data-submit-btn="modal-tweet-submit-btn"
                        data-max="280"
                        placeholder="What is happening?!" 
                        maxlength="280"
                        required
                        class="w-full bg-transparent text-white placeholder-[#71767b] text-base resize-none focus:outline-none border-none p-0 focus:ring-0 leading-relaxed"
                    ></textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-[#2f3336] mt-3">
                <div></div>
                <div class="flex items-center space-x-3">
                    <span id="modal-char-count" class="text-xs font-medium text-[#71767b]">280</span>
                    <button 
                        type="submit" 
                        id="modal-tweet-submit-btn"
                        disabled
                        class="bg-[#1d9bf0] hover:bg-[#1a8cd8] disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-sm px-4 py-2 rounded-full transition"
                    >
                        Post
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endauth
