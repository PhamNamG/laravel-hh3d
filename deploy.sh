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
echo -e "${YELLOW}[1/10] Enabling maintenance mode...${NC}"
php artisan down || true

# Step 2: Pull latest code (if using git)
if [ -d ".git" ]; then
    echo -e "${YELLOW}[2/10] Pulling latest code from Git...${NC}"
    git pull origin main
else
    echo -e "${YELLOW}[2/10] Skipping Git pull (not a Git repository)${NC}"
fi

# Step 3: Install/update Composer dependencies
echo -e "${YELLOW}[3/10] Installing Composer dependencies...${NC}"
composer install --optimize-autoloader --no-dev

# Step 4: Install NPM dependencies
echo -e "${YELLOW}[4/10] Installing NPM dependencies...${NC}"
npm install

# Step 5: Build Vite assets (IMPORTANT!)
echo -e "${YELLOW}[5/10] Building production assets with Vite...${NC}"
npm run build

# Verify build
if [ -d "public/build/assets" ]; then
    ASSET_COUNT=$(ls -1 public/build/assets/*.{css,js} 2>/dev/null | wc -l)
    echo -e "${GREEN}✅ Built $ASSET_COUNT asset files${NC}"
else
    echo -e "${RED}❌ Warning: public/build/assets/ not found${NC}"
fi

# Step 6: Clear all caches
echo -e "${YELLOW}[6/10] Clearing caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Step 7: Rebuild caches
echo -e "${YELLOW}[7/10] Rebuilding caches...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 8: Run migrations (optional - uncomment if needed)
# echo -e "${YELLOW}[8/10] Running migrations...${NC}"
# php artisan migrate --force

# Step 9: Set correct permissions
echo -e "${YELLOW}[9/10] Setting permissions...${NC}"
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
sudo chmod -R 775 public/build

# Step 10: Restart services
echo -e "${YELLOW}[10/10] Restarting services...${NC}"
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

# Show Vite build info
echo -e "${YELLOW}📦 Vite Assets Built:${NC}"
if [ -d "public/build/assets" ]; then
    ls -lh public/build/assets/ | grep -E '\.(css|js)$' | awk '{print "   " $9 " - " $5}'
fi
echo ""

# Show useful info
echo -e "${YELLOW}Useful commands:${NC}"
echo "View logs: tail -f storage/logs/laravel.log"
echo "Clear cache: php artisan optimize:clear"
echo "Restart: sudo systemctl restart nginx php8.2-fpm"
echo "Rebuild assets: npm run build"
echo ""

