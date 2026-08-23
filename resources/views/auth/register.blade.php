<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark bg-black">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign up for X / Twitter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background-color: #000000; color: #e7e9ea; }
    </style>
</head>
<body class="bg-black text-[#e7e9ea] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-black border border-[#2f3336] rounded-3xl p-8 shadow-2xl space-y-6">
        <!-- Logo -->
        <div class="flex justify-center">
            <a href="{{ route('home') }}">
                <x-x-logo class="w-10 h-10 text-white hover:opacity-80 transition" />
            </a>
        </div>

        <div class="text-center space-y-1">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Join X today</h1>
            <p class="text-sm text-[#71767b]">Connect, tweet, and explore what's happening</p>
        </div>

        @if($errors->any())
            <div class="bg-[#f4212e]/10 border border-[#f4212e]/30 text-[#f4212e] px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div class="border border-[#2f3336] rounded-xl px-3 py-2 focus-within:border-[#1d9bf0] focus-within:ring-1 focus-within:ring-[#1d9bf0] transition">
                <label class="block text-xs text-[#71767b]">Display Name</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                    maxlength="50"
                    placeholder="e.g. Jane Doe"
                    class="w-full bg-transparent text-white text-base focus:outline-none border-none p-0 mt-0.5"
                >
            </div>

            <!-- Username -->
            <div class="border border-[#2f3336] rounded-xl px-3 py-2 focus-within:border-[#1d9bf0] focus-within:ring-1 focus-within:ring-[#1d9bf0] transition">
                <label class="block text-xs text-[#71767b]">Username (handle)</label>
                <div class="flex items-center">
                    <span class="text-[#71767b] mr-0.5 text-base">@</span>
                    <input 
                        type="text" 
                        name="username" 
                        value="{{ old('username') }}" 
                        required 
                        maxlength="30"
                        placeholder="janedoe"
                        class="w-full bg-transparent text-white text-base focus:outline-none border-none p-0 mt-0.5"
                    >
                </div>
            </div>

            <!-- Email -->
            <div class="border border-[#2f3336] rounded-xl px-3 py-2 focus-within:border-[#1d9bf0] focus-within:ring-1 focus-within:ring-[#1d9bf0] transition">
                <label class="block text-xs text-[#71767b]">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    placeholder="jane@example.com"
                    class="w-full bg-transparent text-white text-base focus:outline-none border-none p-0 mt-0.5"
                >
            </div>

            <!-- Password -->
            <div class="border border-[#2f3336] rounded-xl px-3 py-2 focus-within:border-[#1d9bf0] focus-within:ring-1 focus-within:ring-[#1d9bf0] transition">
                <label class="block text-xs text-[#71767b]">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="Minimum 6 characters"
                    class="w-full bg-transparent text-white text-base focus:outline-none border-none p-0 mt-0.5"
                >
            </div>

            <!-- Confirm Password -->
            <div class="border border-[#2f3336] rounded-xl px-3 py-2 focus-within:border-[#1d9bf0] focus-within:ring-1 focus-within:ring-[#1d9bf0] transition">
                <label class="block text-xs text-[#71767b]">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    placeholder="Re-enter password"
                    class="w-full bg-transparent text-white text-base focus:outline-none border-none p-0 mt-0.5"
                >
            </div>

            <!-- Submit -->
            <button 
                type="submit" 
                class="w-full bg-[#1d9bf0] hover:bg-[#1a8cd8] text-white font-bold py-3 rounded-full text-base transition shadow-md mt-4"
            >
                Create account
            </button>
        </form>

        <div class="text-center pt-2">
            <p class="text-sm text-[#71767b]">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-[#1d9bf0] font-bold hover:underline">Log in</a>
            </p>
        </div>
    </div>
</body>
</html>
