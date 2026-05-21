# Project Deployment and Configuration Guide

Domain: https://peru-crocodile-804063.hostingersite.com
Repository: https://github.com/adhimiw/cs-website.git

This guide explains how to build the React frontend, deploy the Laravel backend, initialize the database, and configure API and email integration in a Hostinger shared-hosting environment.

---

## 1. Prerequisites

Ensure the following are prepared:

* Hostinger account with SSH access enabled.
* Git available on the Hostinger terminal.
* GitHub authentication configured on the Hostinger terminal if the repository is private.
* Node.js latest LTS installed on your local machine.
* Composer and PHP 8.3+ available on the hosting server.

---

## 2. Server SSH and Directory Setup

Connect to your Hostinger shell and prepare the folders:

```bash
# Configure SSH known hosts for GitHub if not already done
mkdir -p ~/.ssh
chmod 700 ~/.ssh
ssh-keyscan github.com >> ~/.ssh/known_hosts
chmod 600 ~/.ssh/known_hosts

# Navigate to the domain folder and create the site directory
cd ~/domains/peru-crocodile-804063.hostingersite.com/
mkdir -p site
cd site

# Clone the repository
git clone https://github.com/adhimiw/cs-website.git .

# If the repository is private and SSH auth is configured, use this instead:
# git clone git@github.com:adhimiw/cs-website.git .
```

---

## 3. Build and Upload Frontend Assets

The React frontend configuration has its `package.json` in the project root, while the Laravel backend sits inside the `backend` subdirectory.

On your local machine, build and transfer the SPA distribution:

```bash
# 1. Run local build
npm install
npm run build

# 2. Upload build output directly into the backend public folder on Hostinger
scp -P 65002 -r dist/assets dist/images dist/index.html dist/favicon.svg dist/icons.svg u244089748@145.79.210.59:/home/u244089748/domains/peru-crocodile-804063.hostingersite.com/site/backend/public/

# 3. Fix uploaded static asset permissions on Hostinger
ssh -p 65002 u244089748@145.79.210.59 "chmod -R u=rwX,go=rX /home/u244089748/domains/peru-crocodile-804063.hostingersite.com/site/backend/public/assets /home/u244089748/domains/peru-crocodile-804063.hostingersite.com/site/backend/public/images"
```

---

## 4. Define Hostinger Environment Paths

Verify or export the following directories in your Hostinger SSH session:

```bash
HOSTINGER_USER=u244089748
DOMAIN_ROOT="/home/$HOSTINGER_USER/domains/peru-crocodile-804063.hostingersite.com"
SITE_DIR="$DOMAIN_ROOT/site"
BACKEND_DIR="$SITE_DIR/backend"
PUBLIC_DIR="$BACKEND_DIR/public"
PUBLIC_HTML="$DOMAIN_ROOT/public_html"
```

---

## 5. Web Server Symlink Deployment

Create the symlink from `public_html` to the backend public folder:

```bash
cd "$DOMAIN_ROOT"

# Back up old directory if it exists
if [ -e "$PUBLIC_HTML" ] || [ -L "$PUBLIC_HTML" ]; then
  BACKUP_NAME="public_html_backup_$(date +%F_%H%M%S)"
  mv "$PUBLIC_HTML" "$BACKUP_NAME"
  echo "Old public_html backed up as: $BACKUP_NAME"
fi

# Create symlink pointing to the backend public folder
ln -s "$PUBLIC_DIR" "$PUBLIC_HTML"
echo "Symlink created: public_html -> $PUBLIC_DIR"
```

---

## 6. Configure `.htaccess`

Create the production `.htaccess` inside `$PUBLIC_DIR` to support both Laravel APIs and React client-side routing:

```bash
cat > "$PUBLIC_DIR/.htaccess" <<'HTA'
<IfModule mod_rewrite.c>
    DirectoryIndex index.html index.php

    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Serve existing public files/folders directly
    RewriteCond %{REQUEST_FILENAME} -f [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^ - [L]

    # Route backend modules to Laravel router
    RewriteCond %{REQUEST_URI} ^/(api|admin|sanctum|storage|livewire|livewire-[^/]+|filament|analytics)(/|$) [NC]
    RewriteRule ^ index.php [L]

    # Fallback to React SPA router for all other client routes
    RewriteRule ^ index.html [L]
</IfModule>
HTA
```

---

## 7. Backend Environment (`.env`) Configuration

Create the production environment file inside the backend directory:

```bash
cd "$BACKEND_DIR"
cp .env.example .env
php artisan key:generate --show
nano .env
```

Paste and update the following settings. Do not commit this file.

```env
APP_NAME="ClimbSphere"
APP_ENV=production
APP_KEY=base64:replace_with_generated_app_key
APP_DEBUG=false
APP_URL=https://peru-crocodile-804063.hostingersite.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<hostinger_database_name>
DB_USERNAME=<hostinger_database_user>
DB_PASSWORD=<hostinger_database_password>

CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# SMTP Mail Settings
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=<smtp_mailbox>
MAIL_PASSWORD=<smtp_password>
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="<smtp_mailbox>"
MAIL_FROM_NAME="ClimbSphere Support"
MAIL_ADMIN_RECIPIENT="<admin_recipient_email>"

# AI Lead Capture Agent Configuration
AI_PROVIDER=openai
GROQ_API_KEY=<groq_api_key>
GROQ_BASE_URL=https://api.groq.com/openai/v1
```

---

## 8. Initialize Dependencies, Storage, and Seeding

Perform the configuration setup and seed initial database content:

```bash
cd "$BACKEND_DIR"

# Hostinger CLI may default to PHP 8.2 even when the website is set to PHP 8.3.
# Use the PHP 8.3 binary explicitly for Laravel 13 commands.
PHP_BIN=/opt/alt/php83/usr/bin/php

# Install production dependencies
$PHP_BIN "$(which composer)" install --no-dev --optimize-autoloader --no-interaction

# Publish Filament and Livewire browser assets
$PHP_BIN artisan filament:assets
$PHP_BIN artisan livewire:publish --assets

# Create database tables
$PHP_BIN artisan migrate --force

# Seed dynamic site pages, services, testimonials, blogs, and settings
$PHP_BIN artisan db:seed --force

# Create symbolic link for public upload assets
$PHP_BIN artisan storage:link

# Set storage folder permissions
chmod -R 775 storage bootstrap/cache
```

---

## 9. Create Filament Administrative User

To log into the admin panel at `https://peru-crocodile-804063.hostingersite.com/admin`:

```bash
/opt/alt/php83/usr/bin/php artisan make:filament-user
```

Follow the prompts to enter the name, email, and password.

---

## 10. Performance Optimization

Clear and cache config and routing definitions for optimized request processing:

```bash
/opt/alt/php83/usr/bin/php artisan config:clear
/opt/alt/php83/usr/bin/php artisan route:clear
/opt/alt/php83/usr/bin/php artisan view:clear

/opt/alt/php83/usr/bin/php artisan config:cache
/opt/alt/php83/usr/bin/php artisan view:cache
```

Do not run `route:cache` on this shared-hosting setup until the admin panel is verified. Livewire serves JavaScript through Laravel routes, and route caching can cause Livewire JavaScript requests to return a 404 HTML page.

---

Deployment completed:

* Public portal: https://peru-crocodile-804063.hostingersite.com
* Control center: https://peru-crocodile-804063.hostingersite.com/admin
