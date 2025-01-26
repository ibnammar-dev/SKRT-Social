# 🚀 SKRT Social

A modern social media platform for sharing moments and connecting with friends. ✨

## 🤔 What is SKRT Social?

SKRT Social is a minimalist social platform focused on meaningful interactions. Share updates, photos, and stay connected with your community. 🌟

## 🏁 Getting Started

### 📋 Prerequisites
- PHP 8.2 or higher
- Composer
- Symfony CLI
- MySQL/MariaDB
- Git

### 🛠️ Installation

1. Open your terminal and clone the repository:
   ```bash
   git clone https://github.com/yourusername/skrt-social.git
   cd skrt-social
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Configure your environment:
   ```bash
   cp .env .env.local
   ```
   Then edit `.env.local` with your database credentials

4. Create and setup the database:
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. Start the Symfony development server:
   ```bash
   symfony server:start
   ```

6. Visit `http://localhost:8000` in your browser 🎉