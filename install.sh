#!/bin/bash

# ================================================
# Laravel HH3D - Auto Install Script for Ubuntu
# ================================================

set -e

echo "🚀 Starting Laravel HH3D Installation..."
echo "========================================"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   echo -e "${RED}This script must be run as root${NC}" 
   exit 1
fi

# Get user input
read -p "Enter your domain name (e.g., hhkungfu.com): " DOMAIN
read -p "Enter your email for SSL certificate: " EMAIL
read -p "Enter database name [laravel_hh3d]: " DB_NAME
DB_NAME=${DB_NAME:-laravel_hh3d}
read -p "Enter database username [laravel_user]: " DB_USER
DB_USER=${DB_USER:-laravel_user}
read -sp "Enter database password: " DB_PASS
echo ""

echo ""
echo -e "${GREEN}Configuration:${NC}"
echo "Domain: $DOMAIN"
echo "Email: $EMAIL"
echo "Database: $DB_NAME"
echo "DB User: $DB_USER"
echo ""
read -p "Is this correct? (y/n) " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    exit 1
fi

# Update system
echo -e "${YELLOW}[1/10] Updating system...${NC}"
apt update && apt upgrade -y

# Install basic packages
echo -e "${YELLOW}[2/10] Installing basic packages...${NC}"
apt install -y software-properties-common curl git unzip

# Install PHP 8.2
echo -e "${YELLOW}[3/10] Installing PHP 8.2...${NC}"
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common \
    php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring \
    php8.2-curl php8.2-xml php8.2-bcmath php8.2-intl

# Install Composer
echo -e "${YELLOW}[4/10] Installing Composer...${NC}"
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install MySQL
echo -e "${YELLOW}[5/10] Installing MySQL...${NC}"
apt install -y mysql-server

# Configure MySQL
echo -e "${YELLOW}[6/10] Configuring MySQL...${NC}"
mysql -e "CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# Install Nginx
echo -e "${YELLOW}[7/10] Installing Nginx...${NC}"
apt install -y nginx

# Setup firewall
echo -e "${YELLOW}[8/10] Configuring firewall...${NC}"
ufw allow 'Nginx Full'
ufw allow OpenSSH
echo "y" | ufw enable

# Setup project directory
echo -e "${YELLOW}[9/10] Setting up project...${NC}"
mkdir -p /var/www/hhkungfu
cd /var/www/hhkungfu

# You need to upload your project files here
echo -e "${YELLOW}Please upload your project files to: /var/www/hhkungfu${NC}"
echo -e "${YELLOW}Press Enter after uploading files...${NC}"
read

# Install dependencies
if [ -f "composer.json" ]; then
    composer install --optimize-autoloader --no-dev
fi

# Setup .env
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
    
    # Update .env
    sed -i "s|APP_URL=.*|APP_URL=https://$DOMAIN|g" .env
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_NAME|g" .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USER|g" .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|g" .env
    sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|g" .env
    sed -i "s|APP_ENV=.*|APP_ENV=production|g" .env
fi

# Set permissions
chown -R www-data:www-data /var/www/hhkungfu
chmod -R 755 /var/www/hhkungfu
chmod -R 775 /var/www/hhkungfu/storage
chmod -R 775 /var/www/hhkungfu/bootstrap/cache

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create Nginx config
echo -e "${YELLOW}[10/10] Configuring Nginx...${NC}"
cat > /etc/nginx/sites-available/hhkungfu <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name $DOMAIN www.$DOMAIN;
    
    root /var/www/hhkungfu/public;
    index index.php index.html;

    charset utf-8;

    access_log /var/log/nginx/hhkungfu-access.log;
    error_log /var/log/nginx/hhkungfu-error.log;

    client_max_body_size 20M;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }
}
EOF

# Enable site
ln -sf /etc/nginx/sites-available/hhkungfu /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test and restart Nginx
nginx -t
systemctl restart nginx

# Install SSL
echo -e "${YELLOW}Installing SSL certificate...${NC}"
apt install -y certbot python3-certbot-nginx
certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email $EMAIL

# Optimize PHP-FPM
cat >> /etc/php/8.2/fpm/php.ini <<EOF

; Custom OPcache settings
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
EOF

systemctl restart php8.2-fpm

echo ""
echo -e "${GREEN}================================================${NC}"
echo -e "${GREEN}✅ Installation Complete!${NC}"
echo -e "${GREEN}================================================${NC}"
echo ""
echo -e "Website: ${GREEN}https://$DOMAIN${NC}"
echo -e "Project directory: ${GREEN}/var/www/hhkungfu${NC}"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Upload your project files if not already done"
echo "2. Run: cd /var/www/hhkungfu && composer install"
echo "3. Configure .env file if needed"
echo "4. Test website: https://$DOMAIN"
echo ""
echo -e "${YELLOW}Useful commands:${NC}"
echo "- Restart services: systemctl restart nginx php8.2-fpm"
echo "- View Laravel logs: tail -f /var/www/hhkungfu/storage/logs/laravel.log"
echo "- View Nginx logs: tail -f /var/log/nginx/hhkungfu-error.log"
echo ""
echo -e "${GREEN}Happy deploying! 🚀${NC}"

