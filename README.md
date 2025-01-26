# 🎧 Podtasty - Social Media for Podcast Lovers

Welcome to Podtasty, where podcast enthusiasts unite! Share your favorite podcast moments, discuss episodes, and connect with fellow listeners in a vibrant community.

## 🚀 Features

- User authentication system
- Post creation and sharing
- Image upload capabilities
- Modern, responsive UI
- Secure user management
- Interactive feed system

## 🛠 Tech Stack

- Symfony 6.4
- PHP 8.2+
- MySQL/MariaDB
- Bootstrap 5
- Webpack Encore
- VichUploader Bundle

## 🏃‍♂️ Quick Start

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL/MariaDB
- Symfony CLI (recommended)

### Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/podtasty.git
cd podtasty
```

2. Install PHP dependencies
```bash
composer install
```

3. Install JavaScript dependencies
```bash
npm install
```

4. Configure your database in `.env.local`
```bash
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/podtasty?serverVersion=8.0"
```

5. Create database and run migrations
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

6. Build assets
```bash
npm run build
```

7. Start the Symfony development server
```bash
symfony serve -d
```

Visit `http://localhost:8000` and you're ready to go! 🎉

## 🎯 Contributing

Contributions are welcome! Feel free to submit a Pull Request.

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🎭 And finally... a developer joke

Why do podcast developers prefer dark mode?

Because light attracts bugs! 🪲

---
Made with ❤️ and probably too much coffee ☕ 