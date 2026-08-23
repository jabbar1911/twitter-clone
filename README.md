<div align="center">

# 𝕏 Twitter (X) Clone — Laravel 12 & Tailwind CSS

A clean, authentic, lightweight **Twitter (X) Clone** built with **Laravel 12**, **Blade Components**, **Tailwind CSS**, and **SQLite**.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%20%7C%208.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://sqlite.org)
[![Tests](https://img.shields.io/badge/Tests-23%20Passed%20(98%20Assertions)-success?style=for-the-badge&logo=pest)](https://pestphp.com)

</div>

---

## ✨ Features

- 🌑 **Authentic High-Contrast Dark UI**: Deep `#000000` pitch black background, `#16181c` surface cards, `#2f3336` borders, and `#1d9bf0` Twitter blue accents.
- 🐦 **Tweet Timeline & Dual Feeds**:
  - **"For You"** global feed vs. **"Following"** personalized chronological stream.
  - Live 280-character countdown counter.
  - Automatic boundary-safe parsing for `@mentions` and `#hashtags` linking directly to profile and search pages.
- 🔍 **Unified Search & Dedicated Explore Page**:
  - Search both **People** (`name`, `@username`, `bio`) and **Tweets** (`message`, `#hashtags`).
  - Dedicated `/explore` page featuring trending topics and quick-filter chips (`#laravel`, `#php`, `#webdev`, `#tailwindcss`, `#opensource`).
- 👥 **Interactive Follow / Unfollow**:
  - Signature Twitter hover effect: **"Following"** turns into red **"Unfollow"** on hover.
  - Instant AJAX updates without full-page reloads.
- ❤️ **Real-time Tweet Interactions**:
  - Interactive **Like** toggle with heart bounce pop animation and live counter.
  - One-tap **Copy Tweet Link** with instant toast notification.
- ✏️ **Author Tweet Management**:
  - **Edit Tweet** modal with live countdown.
  - **Delete Tweet** confirmation modal to prevent accidental deletes.
- ⚡ **1-Click Demo Logins**:
  - Pre-seeded one-click login buttons for `@taylorotwell`, `@laravel`, and `@demouser`.
- 📱 **Responsive 3-Column Layout**:
  - Left navigation, center feed, right search/trends sidebar, and mobile bottom navigation.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **Language**: PHP 8.3 / 8.4
- **Frontend**: Blade Components + Tailwind CSS
- **Interactions**: TypeScript / JavaScript (Vanilla + Fetch API)
- **Database**: SQLite (Zero configuration, single file)
- **Testing**: Pest / PHPUnit (23 automated tests, 98 assertions)

---

## 🚀 Quick Start (Local Setup)

### 1. Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM

### 2. Setup Commands
```bash
# 1. Clone the repository
git clone https://github.com/YOUR_USERNAME/twitter-clone.git
cd twitter-clone

# 2. Install PHP & JavaScript dependencies
composer install
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Run database migrations & seed demo accounts
php artisan migrate:fresh --seed

# 5. Build frontend assets
npm run build

# 6. Start local development server
php artisan serve
```

Navigate to: **`http://127.0.0.1:8000`**

---

## 🧪 Demo Accounts

| Name | Username | Email | Password |
| :--- | :--- | :--- | :--- |
| **Taylor Otwell** | `@taylorotwell` | `taylor@laravel.com` | `password` |
| **Laravel** | `@laravel` | `hello@laravel.com` | `password` |
| **Demo User** | `@demouser` | `demo@example.com` | `password` |

*(Or click any of the 1-click login buttons on the login page)*

---

## ☁️ Deploy to Render.com (Simple & Free with SQLite)

You can deploy this entire application on **[Render.com](https://render.com/)** using just **Render Web Service + SQLite** (No external database needed!).

### Step 1: Push Code to GitHub
```bash
git add .
git commit -m "Deploy to Render"
git push origin main
```

### Step 2: Create Web Service on Render
1. Go to **[dashboard.render.com](https://dashboard.render.com/)** $\to$ **New +** $\to$ **Web Service**.
2. Connect your GitHub repository.
3. Fill in the service settings:
   - **Name**: `twitter-clone`
   - **Language**: `PHP` / `Node`
   - **Build Command**:
     ```bash
     composer install --no-dev --optimize-autoloader && npm install && npm run build
     ```
   - **Start Command**:
     ```bash
     touch database/database.sqlite && php artisan migrate --force --seed && php artisan serve --host 0.0.0.0 --port $PORT
     ```

### Step 3: Add Environment Variables
Under **Environment Variables**, add:
| Key | Value |
| :--- | :--- |
| `APP_NAME` | `Twitter` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | *(Your `base64:...` key from local `.env`)* |
| `DB_CONNECTION` | `sqlite` |

Click **Create Web Service**, and your Twitter Clone will be live with free HTTPS in ~2 minutes!

---

## 🧪 Automated Tests

Run the test suite to verify all features:
```bash
php artisan test
```

```
   PASS  Tests\Feature\ExampleTest
  ✓ returns a successful response

   PASS  Tests\Feature\TwitterTest
  ✓ user can register with unique username
  ✓ user can login with email or username
  ✓ user can use one click demo login
  ✓ user can post a tweet
  ✓ tweet message cannot exceed 280 characters
  ✓ author can edit and update tweet
  ✓ unauthorized user cannot edit or update another users tweet
  ✓ author can delete their tweet
  ✓ unauthorized user cannot delete another users tweet
  ✓ user can like and unlike a tweet
  ✓ user can follow and unfollow another user
  ✓ user cannot follow themselves
  ✓ profile page is accessible via handle
  ✓ profile likes tab shows liked tweets
  ✓ user can update profile
  ✓ profile page returns 404 for non existent user
  ✓ formatted message parses mentions and hashtags with xss protection
  ✓ search filters tweets by keyword or hashtag
  ✓ following feed only shows tweets from followed users
  ✓ explore page is accessible and filters trending topics
  ✓ search matches users by name or username or bio

  Tests:    23 passed (98 assertions)
```

---

## 📄 License

Open-sourced software licensed under the [MIT license](LICENSE).
