#!/bin/bash
# =============================================================================
# Lunara E-Commerce - Deploy Script untuk VPS Ubuntu
# =============================================================================
# Jalankan: bash deploy/deploy.sh
# Script ini untuk deployment pertama kali DAN update selanjutnya.
# =============================================================================

set -e

# Konfigurasi - sesuaikan jika perlu
APP_DIR="/var/www/lunara"
REPO_URL="https://github.com/appangggg/lunara_e-commerce.git"
BRANCH="hosting"
PHP_VERSION="8.3"
WEB_USER="www-data"

# Warna output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log_info()  { echo -e "${BLUE}[INFO]${NC} $1"; }
log_ok()    { echo -e "${GREEN}[OK]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

# =============================================================================
# CEK APAKAH INI DEPLOYMENT PERTAMA ATAU UPDATE
# =============================================================================
if [ ! -d "$APP_DIR" ]; then
    IS_FIRST_DEPLOY=true
    log_info "Deployment pertama kali terdeteksi."
else
    IS_FIRST_DEPLOY=false
    log_info "Update deployment terdeteksi."
fi

# =============================================================================
# SETUP SERVER (HANYA DEPLOYMENT PERTAMA)
# =============================================================================
if [ "$IS_FIRST_DEPLOY" = true ]; then
    log_info "=== MEMULAI SETUP SERVER ==="
    
    # Update system
    log_info "Updating system packages..."
    sudo apt update && sudo apt upgrade -y
    
    # Install dependencies
    log_info "Installing required packages..."
    sudo apt install -y \
        nginx \
        mysql-server \
        git \
        curl \
        unzip \
        software-properties-common \
        certbot \
        python3-certbot-nginx \
        supervisor
    
    # Install PHP 8.3
    log_info "Installing PHP ${PHP_VERSION}..."
    sudo add-apt-repository -y ppa:ondrej/php
    sudo apt update
    sudo apt install -y \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-common \
        php${PHP_VERSION}-mysql \
        php${PHP_VERSION}-pgsql \
        php${PHP_VERSION}-sqlite3 \
        php${PHP_VERSION}-zip \
        php${PHP_VERSION}-gd \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-bcmath \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-readline \
        php${PHP_VERSION}-tokenizer \
        php${PHP_VERSION}-fileinfo \
        php${PHP_VERSION}-redis
    
    # Install Composer
    log_info "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    
    # Install Node.js (LTS)
    log_info "Installing Node.js LTS..."
    curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
    sudo apt install -y nodejs
    
    # Setup MySQL database
    log_info "Setting up MySQL database..."
    echo ""
    echo "============================================"
    echo "  SETUP DATABASE MYSQL"
    echo "============================================"
    echo ""
    read -p "Masukkan nama database (default: lunara_production): " DB_NAME
    DB_NAME=${DB_NAME:-lunara_production}
    
    read -p "Masukkan username database (default: lunara_user): " DB_USER
    DB_USER=${DB_USER:-lunara_user}
    
    read -sp "Masukkan password database: " DB_PASS
    echo ""
    
    sudo mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    sudo mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
    sudo mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';"
    sudo mysql -e "FLUSH PRIVILEGES;"
    log_ok "Database '${DB_NAME}' dan user '${DB_USER}' berhasil dibuat."
    
    # Clone repository
    log_info "Cloning repository..."
    sudo mkdir -p $APP_DIR
    sudo chown $USER:$USER $APP_DIR
    git clone -b $BRANCH $REPO_URL $APP_DIR
    
    # Setup Nginx
    log_info "Setting up Nginx..."
    sudo cp $APP_DIR/deploy/nginx/lunara.conf /etc/nginx/sites-available/lunara
    sudo ln -sf /etc/nginx/sites-available/lunara /etc/nginx/sites-enabled/lunara
    sudo rm -f /etc/nginx/sites-enabled/default
    sudo nginx -t && sudo systemctl reload nginx
    log_ok "Nginx configured."
    
    # Setup .env
    log_info "Setting up .env file..."
    cp $APP_DIR/.env.production.example $APP_DIR/.env
    
    # Auto-fill database credentials
    sed -i "s/\[GANTI_DENGAN_DB_USERNAME\]/${DB_USER}/" $APP_DIR/.env
    sed -i "s/\[GANTI_DENGAN_DB_PASSWORD\]/${DB_PASS}/" $APP_DIR/.env
    sed -i "s/lunara_production/${DB_NAME}/" $APP_DIR/.env
    
    log_warn "PENTING: Edit file .env untuk mengisi credential lainnya!"
    log_warn "Jalankan: nano ${APP_DIR}/.env"
    
    log_ok "=== SETUP SERVER SELESAI ==="
    echo ""
fi

# =============================================================================
# DEPLOYMENT / UPDATE
# =============================================================================
log_info "=== MEMULAI DEPLOYMENT ==="

cd $APP_DIR

# Maintenance mode
if [ "$IS_FIRST_DEPLOY" = false ]; then
    log_info "Enabling maintenance mode..."
    php artisan down --retry=60 || true
    
    # Pull latest code
    log_info "Pulling latest code..."
    git fetch origin $BRANCH
    git reset --hard origin/$BRANCH
fi

# Install PHP dependencies
log_info "Installing Composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies & build assets
log_info "Installing NPM dependencies & building assets..."
npm ci
npm run build

# Generate app key (hanya jika belum ada)
if ! grep -q "APP_KEY=base64:" $APP_DIR/.env; then
    log_info "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
log_info "Running database migrations..."
php artisan migrate --force

# Cache & Optimize
log_info "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Create storage symlink
log_info "Creating storage symlink..."
php artisan storage:link 2>/dev/null || true

# Set permissions
log_info "Setting file permissions..."
sudo chown -R $USER:$WEB_USER $APP_DIR
sudo find $APP_DIR -type f -exec chmod 644 {} \;
sudo find $APP_DIR -type d -exec chmod 755 {} \;
sudo chmod -R 775 $APP_DIR/storage
sudo chmod -R 775 $APP_DIR/bootstrap/cache

# Restart services
log_info "Restarting services..."
sudo systemctl restart php${PHP_VERSION}-fpm
sudo systemctl restart nginx

# Queue worker (via Supervisor)
if [ "$IS_FIRST_DEPLOY" = true ]; then
    log_info "Setting up Supervisor for queue worker..."
    sudo tee /etc/supervisor/conf.d/lunara-worker.conf > /dev/null <<EOF
[program:lunara-worker]
process_name=%(program_name)s_%(process_num)02d
command=php ${APP_DIR}/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=${USER}
numprocs=2
redirect_stderr=true
stdout_logfile=${APP_DIR}/storage/logs/worker.log
stopwaitsecs=3600
EOF
    sudo supervisorctl reread
    sudo supervisorctl update
else
    sudo supervisorctl restart lunara-worker:*
fi

# Disable maintenance mode
if [ "$IS_FIRST_DEPLOY" = false ]; then
    log_info "Disabling maintenance mode..."
    php artisan up
fi

echo ""
log_ok "============================================"
log_ok "  DEPLOYMENT BERHASIL! 🚀"
log_ok "============================================"
echo ""

if [ "$IS_FIRST_DEPLOY" = true ]; then
    echo -e "${YELLOW}LANGKAH SELANJUTNYA:${NC}"
    echo ""
    echo "1. Edit .env file:"
    echo "   nano ${APP_DIR}/.env"
    echo "   - Isi APP_URL dengan domain/IP Anda"
    echo "   - Isi GOOGLE_CLIENT_ID & SECRET"
    echo "   - Isi MIDTRANS_SERVER_KEY & CLIENT_KEY"
    echo ""
    echo "2. Update Nginx server_name:"
    echo "   sudo nano /etc/nginx/sites-available/lunara"
    echo "   - Ganti 'your-domain.com' dengan domain/IP Anda"
    echo "   sudo nginx -t && sudo systemctl reload nginx"
    echo ""
    echo "3. Setup SSL (jika punya domain):"
    echo "   sudo certbot --nginx -d your-domain.com -d www.your-domain.com"
    echo ""
    echo "4. Test akses di browser:"
    echo "   http://YOUR-SERVER-IP"
    echo ""
fi
