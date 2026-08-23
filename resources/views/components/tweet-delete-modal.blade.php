<div id="delete-tweet-modal" class="hidden fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div class="bg-black border border-[#2f3336] rounded-2xl max-w-xs w-full p-6 shadow-2xl space-y-4">
        <h3 class="text-xl font-bold text-white leading-snug">Delete Tweet?</h3>
        <p class="text-sm text-[#71767b] leading-normal">
            This can’t be undone and it will be removed from your profile, the timeline of any accounts that follow you, and from search results.
        </p>

        <form id="delete-tweet-form" method="POST" action="" class="flex flex-col space-y-2.5 pt-2">
            @csrf
            @method('DELETE')
            <button 
                type="submit" 
                class="w-full bg-[#f4212e] hover:bg-[#dc1e29] text-white font-bold py-2.5 px-4 rounded-full text-sm transition"
            >
                Delete
            </button>
            <button 
                type="button" 
                onclick="window.closeDeleteModal()" 
                class="w-full border border-[#536471] hover:bg-white/10 text-white font-bold py-2.5 px-4 rounded-full text-sm transition"
            >
                Cancel
            </button>
        </form>
    </div>
</div>
