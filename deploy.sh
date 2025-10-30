#!/bin/bash

# ================================================
# Laravel HH3D - Deploy Script
# ================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo ""
echo -e "${BLUE}🚀 Starting deployment...${NC}"
echo "========================================"

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Are you in the project root?${NC}"
    exit 1
fi

# Step 1: Enable maintenance mode
echo -e "${YELLOW}[1/8] Enabling maintenance mode...${NC}"
php artisan down || true

# Step 2: Pull latest code (if using git)
if [ -d ".git" ]; then
    echo -e "${YELLOW}[2/8] Pulling latest code from Git...${NC}"
    git pull origin main
else
    echo -e "${YELLOW}[2/8] Skipping Git pull (not a Git repository)${NC}"
fi

# Step 3: Install/update dependencies
echo -e "${YELLOW}[3/8] Installing dependencies...${NC}"
composer install --optimize-autoloader --no-dev

# Step 4: Clear all caches
echo -e "${YELLOW}[4/8] Clearing caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Step 5: Rebuild caches
echo -e "${YELLOW}[5/8] Rebuilding caches...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 6: Run migrations (optional - uncomment if needed)
# echo -e "${YELLOW}[6/8] Running migrations...${NC}"
# php artisan migrate --force

# Step 7: Set correct permissions
echo -e "${YELLOW}[7/8] Setting permissions...${NC}"
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Step 8: Restart services
echo -e "${YELLOW}[8/8] Restarting services...${NC}"
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

# Disable maintenance mode
echo -e "${YELLOW}Disabling maintenance mode...${NC}"
php artisan up

echo ""
echo -e "${GREEN}================================================${NC}"
echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo -e "${GREEN}================================================${NC}"
echo ""
echo -e "${BLUE}Website is now live! 🎉${NC}"
echo ""

# Show useful info
echo -e "${YELLOW}Useful commands:${NC}"
echo "View logs: tail -f storage/logs/laravel.log"
echo "Clear cache: php artisan optimize:clear"
echo "Restart: sudo systemctl restart nginx php8.2-fpm"
echo ""

