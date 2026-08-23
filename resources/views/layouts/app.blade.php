<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark bg-black">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'X / Twitter' }}</title>

    <!-- Google Fonts Inter / Instrument Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.ts'])

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #000000;
            color: #e7e9ea;
        }
    </style>
</head>
<body class="bg-black text-[#e7e9ea] min-h-screen antialiased flex flex-col justify-between">
    <div class="flex min-h-screen justify-center w-full">
        <div class="flex w-full max-w-[1265px]">
            <!-- Left Sidebar -->
            <x-sidebar-left />

            <!-- Main Center Content -->
            <main class="flex-1 min-h-screen border-r border-[#2f3336] max-w-[600px] w-full pb-20 md:pb-0">
                @if(session('success'))
                    <div class="bg-[#1d9bf0]/15 text-[#1d9bf0] px-4 py-3 border-b border-[#1d9bf0]/30 flex items-center justify-between text-sm">
                        <span>{{ session('success') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" class="text-[#71767b] hover:text-white">&times;</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-[#f4212e]/15 text-[#f4212e] px-4 py-3 border-b border-[#f4212e]/30 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Right Sidebar -->
            <x-sidebar-right :whoToFollow="$whoToFollow ?? null" :search="$search ?? null" />
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <x-bottom-nav />

    <!-- Modals -->
    <x-tweet-edit-modal />
    <x-tweet-delete-modal />
    <x-compose-modal />
    <x-profile-edit-modal />
</body>
</html>
