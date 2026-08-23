<x-app-layout>
    <x-slot:title>
        Edit Tweet / X
    </x-slot:title>

    <header class="sticky top-0 bg-black/80 backdrop-blur-md border-b border-[#2f3336] z-10 px-4 py-3 flex items-center space-x-4">
        <a href="{{ route('home') }}" class="p-2 rounded-full hover:bg-white/10 text-white transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-xl font-bold text-white">Edit Tweet</h1>
    </header>

    <div class="p-4">
        <form method="POST" action="{{ route('tweets.update', $tweet) }}">
            @csrf
            @method('PUT')

            <div class="flex space-x-3">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 bg-neutral-800">
                <div class="flex-1">
                    <textarea 
                        name="message" 
                        rows="5" 
                        data-countdown="edit-page-char-count"
                        data-submit-btn="edit-page-submit-btn"
                        data-max="280"
                        maxlength="280"
                        required
                        class="w-full bg-transparent text-white placeholder-[#71767b] text-lg resize-none focus:outline-none border-none p-0 focus:ring-0 leading-relaxed"
                    >{{ old('message', $tweet->message) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-[#2f3336] mt-4">
                <span id="edit-page-char-count" class="text-xs font-medium text-[#71767b]">280</span>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="px-5 py-2 rounded-full border border-[#536471] text-white font-bold text-sm hover:bg-white/10 transition">
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        id="edit-page-submit-btn"
                        class="bg-[#1d9bf0] hover:bg-[#1a8cd8] text-white font-bold text-sm px-6 py-2 rounded-full transition"
                    >
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
