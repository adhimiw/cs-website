#!/bin/sh
set -e

install_dependencies() {
    if [ -f "vendor/autoload.php" ]; then
        return
    fi

    echo "vendor directory not ready, running composer setup..."
    if [ -f "composer.lock" ]; then
        COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --optimize-autoloader --no-security-blocking --prefer-dist
    else
        COMPOSER_MEMORY_LIMIT=-1 composer update --no-interaction --optimize-autoloader --no-security-blocking --prefer-dist
    fi
}

LOCK_DIR="/var/www/html/.composer-setup.lock"
until mkdir "$LOCK_DIR" 2>/dev/null; do
    echo "Waiting for composer setup lock..."
    sleep 2
done
trap 'rmdir "$LOCK_DIR" 2>/dev/null || true' EXIT
install_dependencies
rmdir "$LOCK_DIR" 2>/dev/null || true
trap - EXIT

# Copy .env if it doesn't exist
if [ ! -f ".env" ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
fi

if ! grep -Eq '^APP_KEY=base64:[A-Za-z0-9+/=]{44}$' .env; then
    echo "Generating application key..."
    APP_KEY="$(php -r 'echo "base64:" . base64_encode(random_bytes(32));')"
    sed -i "s#^APP_KEY=.*#APP_KEY=${APP_KEY}#" .env
fi

echo "Preparing Laravel writable directories..."
mkdir -p storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache

# Wait for DB to be ready
echo "Waiting for database..."
until mysqladmin ping -h db -u climbsphere -psecret --ssl=0 --silent; do
    sleep 1
done
echo "Database is ready!"

if [ "$1" = "php-fpm" ]; then
    echo "Publishing Filament assets..."
    php artisan filament:assets

    echo "Running migrations..."
    php artisan migrate --force

    echo "Running database seeders..."
    php artisan db:seed --force
fi

echo "App startup complete!"

exec "$@"
