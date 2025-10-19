# SKRT Social - Configuration Guide

## 📋 Table of Contents
- [Environment Setup](#environment-setup)
- [Database Configuration](#database-configuration)
- [Asset Management](#asset-management)
- [Security Configuration](#security-configuration)
- [Production Deployment](#production-deployment)
- [Docker Setup](#docker-setup)

---

## 🔧 Environment Setup

### Initial Configuration

1. **Copy environment template:**
   ```bash
   copy env.example .env.local
   ```

2. **Generate APP_SECRET:**
   ```bash
   php -r "echo bin2hex(random_bytes(32));"
   ```
   Copy the output and set it in `.env.local`:
   ```
   APP_SECRET=your_generated_secret_here
   ```

3. **Configure database** (see below)

### Environment Files Priority
Symfony loads environment variables in this order (latter overrides former):
1. `.env` - Default values (committed to git)
2. `.env.local` - Local overrides (NOT committed)
3. `.env.{environment}` - Environment-specific (e.g., `.env.prod`)
4. `.env.{environment}.local` - Local environment overrides

**Never commit `.env.local` or files containing secrets!**

---

## 🗄️ Database Configuration

### Local MySQL Setup

1. **Configure connection in `.env.local`:**
   ```env
   DATABASE_URL="mysql://root:password@127.0.0.1:3306/skrt_social?serverVersion=8.0.32&charset=utf8mb4"
   ```

2. **Create database:**
   ```bash
   php bin/console doctrine:database:create
   ```

3. **Run migrations:**
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

### Docker MySQL Setup

1. **Start Docker containers:**
   ```bash
   docker-compose up -d
   ```

2. **Configure in `.env.local`:**
   ```env
   DATABASE_URL="mysql://app:!ChangeMe!@database:3306/app?serverVersion=8.0&charset=utf8mb4"
   ```

3. **Run migrations:**
   ```bash
   docker-compose exec php bin/console doctrine:migrations:migrate
   ```

---

## 🎨 Asset Management

### Dual Asset System

SKRT Social uses **both** Webpack Encore and AssetMapper:

#### Webpack Encore (Custom JS/CSS)
- **Purpose**: Compiles custom JavaScript and CSS
- **Entry Points**: 
  - `app.js` - Main application JS
  - `post-actions.js` - Post interaction handlers
- **CSS**: Compiled from `assets/styles/app.css`

**Development:**
```bash
npm run watch
```

**Production Build:**
```bash
npm run build
```

#### AssetMapper (Stimulus/Turbo)
- **Purpose**: Manages ES6 module imports (Stimulus, Turbo)
- **Configuration**: `importmap.php`
- **No build step required** - modules loaded directly

### Loading Order in Templates

```twig
{% block javascripts %}
    {# 1. Load Stimulus/Turbo via importmap #}
    {{ importmap('app') }}
    
    {# 2. Load custom compiled JS via Webpack Encore #}
    {{ encore_entry_script_tags('app') }}
{% endblock %}
```

This order is **critical** - Stimulus must load before custom controllers.

---

## 🔐 Security Configuration

### Authentication Systems

#### Web Authentication (Form Login)
- **Login URL**: `/login`
- **Configuration**: `config/packages/security.yaml`
- **Authenticator**: `App\Security\FormLoginAuthenticator`
- **Features**: CSRF protection, custom success handler

#### API Authentication (Token-based)
- **Header**: `X-API-TOKEN: your-token-here`
- **Endpoints**: `/api/*`
- **Authenticator**: `App\Security\ApiTokenAuthenticator`
- **Stateless**: No session cookies

### Access Control

```yaml
# config/packages/security.yaml
access_control:
    - { path: ^/api/auth, roles: PUBLIC_ACCESS }  # Registration/Login
    - { path: ^/api, roles: ROLE_USER }           # All API
    - { path: ^/admin, roles: ROLE_ADMIN }        # Admin panel
    - { path: ^/, roles: ROLE_USER }              # All web pages
```

### Role Hierarchy
- `ROLE_ADMIN` → inherits `ROLE_USER`
- All authenticated users have `ROLE_USER`

### Password Hashing
- **Algorithm**: Auto (bcrypt or argon2, whichever is better)
- **Configuration**: Automatic via Symfony

---

## 📦 Production Deployment

### Pre-Deployment Checklist

1. **Set production environment:**
   ```env
   APP_ENV=prod
   APP_DEBUG=0
   ```

2. **Set strong APP_SECRET** (minimum 32 characters)

3. **Configure production database**

4. **Build assets:**
   ```bash
   npm run build
   composer install --no-dev --optimize-autoloader
   ```

5. **Clear and warm cache:**
   ```bash
   php bin/console cache:clear --env=prod
   php bin/console cache:warmup --env=prod
   ```

6. **Run migrations:**
   ```bash
   php bin/console doctrine:migrations:migrate --no-interaction
   ```

### Production Optimizations

#### Framework (`config/packages/prod/framework.yaml`)
- APCu caching enabled
- Session garbage collection optimized
- HTTP cache enabled
- Serializer/annotations cached

#### Logging (`config/packages/prod/monolog.yaml`)
- JSON formatted logs
- Separate logs for: main, security, deprecations, doctrine
- Buffered error logging (fingers_crossed handler)

#### Database (`config/packages/doctrine.yaml`)
- Query cache enabled
- Result cache enabled
- Second-level cache enabled (3600s lifetime)

#### Assets
- Versioned filenames for cache busting
- Minified and optimized
- HTTP/2 Link preloading enabled

### Performance Tips

1. **Enable OPcache** in `php.ini`:
   ```ini
   opcache.enable=1
   opcache.memory_consumption=256
   opcache.interned_strings_buffer=16
   opcache.max_accelerated_files=20000
   opcache.validate_timestamps=0  # In production only
   ```

2. **Use Redis/Memcached** for sessions and cache:
   ```env
   REDIS_URL=redis://localhost:6379
   SESSION_HANDLER_DSN=redis://localhost:6379
   ```

3. **Enable CDN** for static assets

4. **Database connection pooling**

---

## 🐳 Docker Setup

### Services

#### MySQL Database
- **Image**: `mysql:8.0`
- **Port**: `3306`
- **Default Database**: `app`
- **Default User**: `app`
- **Password**: `!ChangeMe!` (change in production!)

#### Mailpit (Email Testing)
- **Image**: `axllent/mailpit`
- **SMTP Port**: `1025`
- **Web UI**: `http://localhost:8025`

### Commands

**Start services:**
```bash
docker-compose up -d
```

**Stop services:**
```bash
docker-compose down
```

**View logs:**
```bash
docker-compose logs -f
```

**Access database:**
```bash
docker-compose exec database mysql -u app -p app
```

### Environment Variables

Configure in `.env.local`:
```env
MYSQL_VERSION=8.0
MYSQL_DATABASE=app
MYSQL_USER=app
MYSQL_PASSWORD=!ChangeMe!
MYSQL_ROOT_PASSWORD=root
```

---

## 📝 Configuration File Reference

### Core Configuration
| File | Purpose |
|------|---------|
| `.env` | Default environment variables |
| `.env.local` | Local overrides (not committed) |
| `config/services.yaml` | Service container configuration |
| `config/routes.yaml` | Routing configuration |

### Package Configuration
| File | Purpose |
|------|---------|
| `config/packages/security.yaml` | Authentication & authorization |
| `config/packages/doctrine.yaml` | Database ORM configuration |
| `config/packages/framework.yaml` | Core Symfony framework |
| `config/packages/vich_uploader.yaml` | File upload configuration |
| `config/packages/webpack_encore.yaml` | Asset compilation |

### Build Configuration
| File | Purpose |
|------|---------|
| `webpack.config.js` | Webpack Encore build configuration |
| `importmap.php` | ES6 module imports (Stimulus/Turbo) |
| `package.json` | Node.js dependencies |
| `composer.json` | PHP dependencies |

### Docker
| File | Purpose |
|------|---------|
| `compose.yaml` | Main Docker services |
| `compose.override.yaml` | Development overrides |

---

## 🆘 Troubleshooting

### Cache Issues
```bash
php bin/console cache:clear
php bin/console cache:warmup
```

### Asset Build Errors
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Database Connection Issues
1. Check `DATABASE_URL` in `.env.local`
2. Verify MySQL is running
3. Check credentials and database exists

### Permission Errors
```bash
chmod -R 755 var/
chmod -R 755 public/images/
```

---

## 📚 Additional Resources

- [Symfony Documentation](https://symfony.com/doc/current/index.html)
- [Webpack Encore Documentation](https://symfony.com/doc/current/frontend.html)
- [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html)
- [API Documentation](./API_DOCUMENTATION.md)

---

**Need help?** Check the [README.md](./README.md) for quick start guide.

