<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark bg-black">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in to X / Twitter</title>
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
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Sign in to X</h1>
            <p class="text-sm text-[#71767b]">Stay updated with the latest in tech & Laravel</p>
        </div>

        <!-- 1-Click Demo Logins -->
        <div class="space-y-2.5 pt-2">
            <p class="text-xs font-semibold text-[#71767b] uppercase tracking-wider text-center">⚡ Quick 1-Click Demo Login</p>
            <div class="grid grid-cols-3 gap-2">
                <form method="POST" action="{{ route('demo-login', ['username' => 'taylorotwell']) }}">
                    @csrf
                    <button type="submit" class="w-full bg-[#16181c] hover:bg-[#202327] border border-[#2f3336] hover:border-[#1d9bf0] text-white text-xs font-bold py-2.5 px-2 rounded-xl transition flex flex-col items-center">
                        <span class="truncate">Taylor</span>
                        <span class="text-[10px] text-[#71767b] font-normal">@taylorotwell</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('demo-login', ['username' => 'laravel']) }}">
                    @csrf
                    <button type="submit" class="w-full bg-[#16181c] hover:bg-[#202327] border border-[#2f3336] hover:border-[#1d9bf0] text-white text-xs font-bold py-2.5 px-2 rounded-xl transition flex flex-col items-center">
                        <span class="truncate">Laravel</span>
                        <span class="text-[10px] text-[#71767b] font-normal">@laravel</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('demo-login', ['username' => 'demouser']) }}">
                    @csrf
                    <button type="submit" class="w-full bg-[#16181c] hover:bg-[#202327] border border-[#2f3336] hover:border-[#1d9bf0] text-white text-xs font-bold py-2.5 px-2 rounded-xl transition flex flex-col items-center">
                        <span class="truncate">Demo User</span>
                        <span class="text-[10px] text-[#71767b] font-normal">@demouser</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex items-center my-4">
            <hr class="flex-1 border-[#2f3336]">
            <span class="px-3 text-xs text-[#71767b] font-medium">or with credentials</span>
            <hr class="flex-1 border-[#2f3336]">
        </div>

        @if($errors->any())
            <div class="bg-[#f4212e]/10 border border-[#f4212e]/30 text-[#f4212e] px-4 py-3 rounded-xl text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email or Username -->
            <div class="border border-[#2f3336] rounded-xl px-3 py-2 focus-within:border-[#1d9bf0] focus-within:ring-1 focus-within:ring-[#1d9bf0] transition">
                <label class="block text-xs text-[#71767b]">Email or @username</label>
                <input 
                    type="text" 
                    name="login" 
                    value="{{ old('login') }}" 
                    required 
                    autofocus
                    autocomplete="username"
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
                    autocomplete="current-password"
                    class="w-full bg-transparent text-white text-base focus:outline-none border-none p-0 mt-0.5"
                >
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs text-[#71767b] pt-1">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-[#202327] border-[#2f3336] text-[#1d9bf0] focus:ring-0">
                    <span>Remember me</span>
                </label>
            </div>

            <!-- Submit -->
            <button 
                type="submit" 
                class="w-full bg-white hover:bg-[#e6e6e6] text-black font-bold py-3 rounded-full text-base transition shadow-md mt-2"
            >
                Log in
            </button>
        </form>

        <div class="text-center pt-2">
            <p class="text-sm text-[#71767b]">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-[#1d9bf0] font-bold hover:underline">Sign up</a>
            </p>
        </div>
    </div>
</body>
</html>
