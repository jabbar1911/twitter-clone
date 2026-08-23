<?php

namespace Database\Seeders;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create realistic demo users
        $taylor = User::create([
            'name' => 'Taylor Otwell',
            'username' => 'taylorotwell',
            'email' => 'taylor@laravel.com',
            'password' => Hash::make('password'),
            'bio' => 'Creator of @laravel. Building tools and ecosystems for web artisans.',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        $laravel = User::create([
            'name' => 'Laravel',
            'username' => 'laravel',
            'email' => 'hello@laravel.com',
            'password' => Hash::make('password'),
            'bio' => 'The PHP Framework for Web Artisans. Created by @taylorotwell. Fast, expressive, and fun.',
            'avatar' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        $demouser = User::create([
            'name' => 'Demo User',
            'username' => 'demouser',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'bio' => 'Exploring the new Laravel 12 Twitter clone! Building modern web apps with #laravel #php #tailwindcss',
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        // 2. Establish follows
        $demouser->following()->attach([$taylor->id, $laravel->id]);
        $taylor->following()->attach([$laravel->id, $demouser->id]);
        $laravel->following()->attach([$taylor->id]);

        // 3. Create realistic tweets
        $t1 = Tweet::create([
            'user_id' => $taylor->id,
            'message' => 'Just pushed some fresh updates to @laravel! Loving the developer velocity in #laravel 12 and the seamless Blade + Tailwind experience.',
            'created_at' => now()->subHours(5),
        ]);

        $t2 = Tweet::create([
            'user_id' => $taylor->id,
            'message' => 'Simplicity is prerequisite for reliability. Keep your codebase clean and expressive. #php #webdev',
            'created_at' => now()->subHours(3),
        ]);

        $t3 = Tweet::create([
            'user_id' => $laravel->id,
            'message' => 'Laravel 12 is officially here! 🚀 Packed with performance improvements, cleaner architecture, and powerful developer tools. #laravel #php #opensource',
            'created_at' => now()->subHours(4),
        ]);

        $t4 = Tweet::create([
            'user_id' => $laravel->id,
            'message' => 'Shoutout to all the amazing contributors making the PHP ecosystem thrive! Big thanks to @taylorotwell and the global community. #community #webdev',
            'created_at' => now()->subHours(2),
        ]);

        $t5 = Tweet::create([
            'user_id' => $demouser->id,
            'message' => 'Setting up my new profile on this Twitter clone! Loving the pure black dark mode and real-time interactions. #laravel #tailwindcss',
            'created_at' => now()->subHour(),
        ]);

        $t6 = Tweet::create([
            'user_id' => $demouser->id,
            'message' => 'Checking out the latest updates from @taylorotwell and @laravel. Super excited for what is coming next in #webdev!',
            'created_at' => now()->subMinutes(20),
        ]);

        // 4. Seed Likes
        $t3->likes()->attach([$taylor->id, $demouser->id]);
        $t1->likes()->attach([$laravel->id, $demouser->id]);
        $t5->likes()->attach([$taylor->id, $laravel->id]);
        $t4->likes()->attach([$taylor->id, $demouser->id]);
        $t6->likes()->attach([$taylor->id]);
    }
}
