<div id="edit-tweet-modal" class="hidden fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div class="bg-black border border-[#2f3336] rounded-2xl max-w-lg w-full p-4 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-[#2f3336]">
            <h3 class="text-lg font-bold text-white">Edit Tweet</h3>
            <button type="button" onclick="window.closeEditTweetModal()" class="text-[#71767b] hover:text-white p-1 rounded-full">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        </div>

        <form id="edit-tweet-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="py-3">
                <textarea 
                    name="message" 
                    id="edit-tweet-message"
                    rows="4" 
                    data-countdown="edit-tweet-char-count"
                    data-submit-btn="edit-tweet-submit-btn"
                    data-max="280"
                    maxlength="280"
                    required
                    class="w-full bg-transparent text-white text-base resize-none focus:outline-none border-none p-0 focus:ring-0 leading-relaxed"
                ></textarea>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-[#2f3336]">
                <span id="edit-tweet-char-count" class="text-xs font-medium text-[#71767b]">280</span>
                <div class="flex items-center space-x-2">
                    <button 
                        type="button" 
                        onclick="window.closeEditTweetModal()" 
                        class="px-4 py-1.5 rounded-full border border-[#536471] text-white font-bold text-sm hover:bg-white/10 transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        id="edit-tweet-submit-btn"
                        class="bg-[#1d9bf0] hover:bg-[#1a8cd8] text-white font-bold text-sm px-4 py-1.5 rounded-full transition"
                    >
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
