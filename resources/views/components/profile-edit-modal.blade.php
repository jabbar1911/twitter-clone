@auth
<div id="edit-profile-modal" class="hidden fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div class="bg-black border border-[#2f3336] rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl relative max-h-[90vh] flex flex-col">
        <!-- Header -->
        <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col h-full">
            @csrf
            @method('PUT')

            <div class="flex items-center justify-between px-4 py-3 border-b border-[#2f3336] bg-black/80 backdrop-blur-md sticky top-0 z-10">
                <div class="flex items-center space-x-4">
                    <button type="button" onclick="window.closeEditProfileModal()" class="text-[#71767b] hover:text-white p-1 rounded-full">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                    </button>
                    <h3 class="text-lg font-bold text-white">Edit profile</h3>
                </div>
                <button 
                    type="submit" 
                    class="bg-white hover:bg-[#e6e6e6] text-black font-bold text-sm px-4 py-1.5 rounded-full transition"
                >
                    Save
                </button>
            </div>

            <!-- Body -->
            <div class="p-4 space-y-5 overflow-y-auto">
                <!-- Banner Area -->
                <div class="h-32 bg-[#1d9bf0]/20 rounded-xl relative border border-[#2f3336] flex items-center justify-center">
                    <span class="text-xs text-[#71767b] font-medium">Header Banner</span>
                </div>

                <!-- Avatar Preview & Input -->
                <div class="-mt-12 ml-3 flex items-end space-x-3">
                    <img 
                        src="{{ auth()->user()->avatar_url }}" 
                        alt="{{ auth()->user()->name }}" 
                        class="w-20 h-20 rounded-full border-4 border-black object-cover bg-neutral-800"
                    >
                </div>

                <!-- Avatar URL Field -->
                <div class="border border-[#2f3336] rounded-lg p-2 focus-within:border-[#1d9bf0] transition">
                    <label class="block text-xs text-[#71767b]">Avatar URL (optional)</label>
                    <input 
                        type="url" 
                        name="avatar" 
                        value="{{ old('avatar', auth()->user()->avatar) }}" 
                        placeholder="https://example.com/avatar.jpg"
                        class="w-full bg-transparent text-white text-sm focus:outline-none border-none p-0 mt-1"
                    >
                </div>

                <!-- Name Field -->
                <div class="border border-[#2f3336] rounded-lg p-2 focus-within:border-[#1d9bf0] transition">
                    <label class="block text-xs text-[#71767b]">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', auth()->user()->name) }}" 
                        maxlength="50"
                        required
                        class="w-full bg-transparent text-white text-base focus:outline-none border-none p-0 mt-1"
                    >
                </div>

                <!-- Bio Field -->
                <div class="border border-[#2f3336] rounded-lg p-2 focus-within:border-[#1d9bf0] transition">
                    <div class="flex justify-between items-center text-xs text-[#71767b]">
                        <label>Bio</label>
                        <span id="profile-bio-char-count">160</span>
                    </div>
                    <textarea 
                        name="bio" 
                        id="profile-bio"
                        rows="3" 
                        maxlength="160"
                        data-countdown="profile-bio-char-count"
                        data-max="160"
                        class="w-full bg-transparent text-white text-sm focus:outline-none border-none p-0 mt-1 resize-none"
                    >{{ old('bio', auth()->user()->bio) }}</textarea>
                </div>
            </div>
        </form>
    </div>
</div>
@endauth
