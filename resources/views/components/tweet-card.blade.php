@props(['tweet'])

@php
    $isLiked = auth()->check() && $tweet->isLikedBy(auth()->user());
    $isOwner = auth()->check() && auth()->id() === $tweet->user_id;
@endphp

<article class="p-4 border-b border-[#2f3336] hover:bg-white/[0.02] transition-colors duration-150 flex space-x-3 group relative">
    <!-- Author Avatar -->
    <a href="{{ route('profile.show', ['username' => $tweet->user->username]) }}" class="shrink-0">
        <img 
            src="{{ $tweet->user->avatar_url }}" 
            alt="{{ $tweet->user->name }}" 
            class="w-10 h-10 rounded-full object-cover bg-neutral-800 hover:opacity-90 transition"
        >
    </a>

    <!-- Tweet Content Container -->
    <div class="flex-1 min-w-0">
        <!-- Tweet Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-1.5 min-w-0 truncate">
                <!-- Name -->
                <a href="{{ route('profile.show', ['username' => $tweet->user->username]) }}" class="font-bold text-white hover:underline text-[15px] truncate">
                    {{ $tweet->user->name }}
                </a>

                <!-- Verified Badge -->
                <svg class="w-4 h-4 text-[#1d9bf0] shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.79-4-4-4-.495 0-.965.084-1.4.238C14.55 2.475 13.18 1.6 11.6 1.6c-1.58 0-2.95.875-3.6 2.148-.435-.154-.905-.238-1.4-.238-2.21 0-4 1.79-4 4 0 .495.084.965.238 1.4C1.575 9.55.7 10.92.7 12.5c0 1.58.875 2.95 2.148 3.6-.154.435-.238.905-.238 1.4 0 2.21 1.79 4 4 4 .495 0 .965-.084 1.4-.238 1.25 1.873 2.62 2.748 4.2 2.748 1.58 0 2.95-.875 3.6-2.148.435.154.905.238 1.4.238 2.21 0 4-1.79 4-4 0-.495-.084-.965-.238-1.4 1.273-.65 2.148-2.02 2.148-3.6zm-12.8 4.7l-4.2-4.2 1.4-1.4 2.8 2.8 6.8-6.8 1.4 1.4-8.2 8.2z"/>
                </svg>

                <!-- Handle -->
                <a href="{{ route('profile.show', ['username' => $tweet->user->username]) }}" class="text-[#71767b] text-sm truncate hover:underline">
                    {{ '@'.$tweet->user->username }}
                </a>

                <!-- Time Separator & Relative Time -->
                <span class="text-[#71767b] text-sm shrink-0">·</span>
                <span class="text-[#71767b] text-sm shrink-0 hover:underline" title="{{ $tweet->created_at->toFormattedDateString() }}">
                    {{ $tweet->created_at->diffForHumans(short: true) }}
                </span>
            </div>

            <!-- Tweet Owner Options Menu -->
            @if($isOwner)
                <div class="relative shrink-0 ml-2">
                    <button 
                        type="button" 
                        onclick="this.nextElementSibling.classList.toggle('hidden')" 
                        class="text-[#71767b] hover:text-[#1d9bf0] hover:bg-[#1d9bf0]/10 p-1.5 rounded-full transition"
                        title="More options"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="5" cy="12" r="2"/>
                            <circle cx="12" cy="12" r="2"/>
                            <circle cx="19" cy="12" r="2"/>
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div class="hidden absolute right-0 top-full mt-1 w-36 bg-black border border-[#2f3336] rounded-xl shadow-xl overflow-hidden py-1 z-30">
                        <button 
                            type="button" 
                            onclick="this.parentElement.classList.add('hidden'); window.openEditTweetModal({{ $tweet->id }}, @js($tweet->message))" 
                            class="w-full text-left px-4 py-2.5 text-sm text-white hover:bg-white/10 flex items-center space-x-2 transition"
                        >
                            <svg class="w-4 h-4 text-[#1d9bf0]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            <span>Edit Tweet</span>
                        </button>
                        <button 
                            type="button" 
                            onclick="this.parentElement.classList.add('hidden'); window.openDeleteModal('{{ route('tweets.destroy', $tweet) }}')" 
                            class="w-full text-left px-4 py-2.5 text-sm text-[#f4212e] hover:bg-white/10 flex items-center space-x-2 transition"
                        >
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                            <span>Delete Tweet</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Tweet Message Body -->
        <div class="text-white text-[15px] leading-normal break-words mt-1">
            {!! $tweet->formattedMessage() !!}
        </div>

        <!-- Tweet Action Bar (Focused only on working features: Like, Share, Edit, Delete) -->
        <div class="flex items-center justify-between text-[#71767b] mt-3 max-w-[360px] text-xs">
            <!-- Like (Interactive AJAX Toggle with Bounce Animation) -->
            <button 
                type="button" 
                onclick="window.toggleLike({{ $tweet->id }}, this)" 
                class="flex items-center space-x-1.5 group/action transition {{ $isLiked ? 'text-[#f91880]' : 'text-[#71767b] hover:text-[#f91880]' }}" 
                title="Like"
            >
                <div class="p-2 rounded-full group-hover/action:bg-[#f91880]/10 transition">
                    <svg class="w-4 h-4 transition-transform" viewBox="0 0 24 24" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <span class="like-count font-medium">{{ $tweet->likes_count > 0 ? $tweet->likes_count : '' }}</span>
            </button>

            <!-- Share / Copy Link -->
            <button 
                type="button" 
                onclick="window.copyTweetLink('{{ url('/@'.$tweet->user->username) }}')" 
                class="flex items-center space-x-1.5 hover:text-[#1d9bf0] group/action transition" 
                title="Copy Link"
            >
                <div class="p-2 rounded-full group-hover/action:bg-[#1d9bf0]/10 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                </div>
            </button>

            @if($isOwner)
                <!-- Quick Edit Button -->
                <button 
                    type="button" 
                    onclick="window.openEditTweetModal({{ $tweet->id }}, @js($tweet->message))" 
                    class="flex items-center space-x-1 hover:text-[#1d9bf0] group/action transition" 
                    title="Edit Tweet"
                >
                    <div class="p-2 rounded-full group-hover/action:bg-[#1d9bf0]/10 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium">Edit</span>
                </button>

                <!-- Quick Delete Button -->
                <button 
                    type="button" 
                    onclick="window.openDeleteModal('{{ route('tweets.destroy', $tweet) }}')" 
                    class="flex items-center space-x-1 hover:text-[#f4212e] group/action transition" 
                    title="Delete Tweet"
                >
                    <div class="p-2 rounded-full group-hover/action:bg-[#f4212e]/10 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium">Delete</span>
                </button>
            @endif
        </div>
    </div>
</article>
