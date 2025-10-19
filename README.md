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

## 📚 Documentation

- **[Configuration Guide](CONFIGURATION.md)** - Complete configuration documentation
- **[API Documentation](API_DOCUMENTATION.md)** - RESTful API reference
- **[Postman Testing](POSTMAN_TESTING.md)** - API testing guide

## 🐳 Docker Quick Start

```bash
# Start all services (MySQL + Mailpit)
docker-compose up -d

# Configure database in .env.local
DATABASE_URL="mysql://app:!ChangeMe!@database:3306/app?serverVersion=8.0&charset=utf8mb4"

# Run migrations
php bin/console doctrine:migrations:migrate

# Access Mailpit (email testing) at http://localhost:8025
```

## 🛠️ Development

### Asset Compilation

```bash
# Watch for changes (development)
npm run watch

# Build for production
npm run build
```

### Database Commands

```bash
# Create database
php bin/console doctrine:database:create

# Run migrations
php bin/console doctrine:migrations:migrate

# Create a new migration
php bin/console make:migration
```

## 🚀 Production Deployment

See [CONFIGURATION.md](CONFIGURATION.md#production-deployment) for detailed production deployment guide.

Quick checklist:
1. Set `APP_ENV=prod` and strong `APP_SECRET`
2. Build assets: `npm run build`
3. Optimize autoloader: `composer install --no-dev --optimize-autoloader`
4. Clear cache: `php bin/console cache:clear --env=prod`
5. Run migrations: `php bin/console doctrine:migrations:migrate`

## 🔐 Security

- Form-based authentication for web interface
- Token-based authentication for API
- CSRF protection enabled
- Role-based access control (USER, ADMIN)