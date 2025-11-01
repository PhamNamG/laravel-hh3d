#!/bin/bash
# Deploy Laravel + Vite to VPS - Quick Start Script
# Usage: bash deploy-vps.sh

set -e

echo "🚀 Laravel + Vite VPS Deployment Script"
echo "========================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running as root
if [ "$EUID" -eq 0 ]; then 
    echo -e "${RED}❌ Please do not run as root${NC}"
    exit 1
fi

# Get project directory
PROJECT_DIR=$(pwd)
echo -e "${GREEN}📁 Project directory: $PROJECT_DIR${NC}"
echo ""

# Function to check command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# 1. Check Prerequisites
echo "🔍 Checking prerequisites..."

if ! command_exists php; then
    echo -e "${RED}❌ PHP not installed${NC}"
    exit 1
fi
echo -e "${GREEN}✅ PHP $(php -v | head -n 1 | cut -d ' ' -f 2)${NC}"

if ! command_exists composer; then
    echo -e "${RED}❌ Composer not installed${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Composer $(composer -V | cut -d ' ' -f 3)${NC}"

if ! command_exists node; then
    echo -e "${RED}❌ Node.js not installed${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Node.js $(node -v)${NC}"

if ! command_exists npm; then
    echo -e "${RED}❌ NPM not installed${NC}"
    exit 1
fi
echo -e "${GREEN}✅ NPM $(npm -v)${NC}"

echo ""

# 2. Check .env
echo "🔐 Checking .env configuration..."
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  .env not found. Creating from .env.example...${NC}"
    cp .env.example .env
    echo -e "${YELLOW}⚠️  Please edit .env file before continuing!${NC}"
    read -p "Press Enter after you've configured .env..."
fi
echo -e "${GREEN}✅ .env exists${NC}"
echo ""

# 3. Install Composer Dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
echo -e "${GREEN}✅ Composer dependencies installed${NC}"
echo ""

# 4. Generate App Key (if not exists)
if grep -q "APP_KEY=$" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
    echo -e "${GREEN}✅ App key generated${NC}"
else
    echo -e "${GREEN}✅ App key already exists${NC}"
fi
echo ""

# 5. Run Migrations
echo "🗄️  Running database migrations..."
read -p "Run migrations? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
    echo -e "${GREEN}✅ Migrations completed${NC}"
fi
echo ""

# 6. Install NPM Dependencies
echo "📦 Installing NPM dependencies..."
npm install
echo -e "${GREEN}✅ NPM dependencies installed${NC}"
echo ""

# 7. Build Assets with Vite (CRITICAL!)
echo "⚡ Building production assets with Vite..."
npm run build
echo -e "${GREEN}✅ Assets built successfully${NC}"
echo ""

# 8. Verify Build
echo "🔍 Verifying Vite build..."
if [ -d "public/build/assets" ]; then
    ASSET_COUNT=$(ls -1 public/build/assets/*.{css,js} 2>/dev/null | wc -l)
    if [ $ASSET_COUNT -gt 0 ]; then
        echo -e "${GREEN}✅ Found $ASSET_COUNT asset files:${NC}"
        ls -lh public/build/assets/ | grep -E '\.(css|js)$'
    else
        echo -e "${RED}❌ No asset files found in public/build/assets/${NC}"
        exit 1
    fi
else
    echo -e "${RED}❌ public/build/assets/ directory not found${NC}"
    exit 1
fi
echo ""

# 9. Set Permissions
echo "🔐 Setting file permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache public/build 2>/dev/null || {
    echo -e "${YELLOW}⚠️  Could not change owner to www-data. Make sure web server has read/write access to:${NC}"
    echo "   - storage/"
    echo "   - bootstrap/cache/"
    echo "   - public/build/"
}
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

# 10. Clear & Cache
echo "🧹 Clearing and caching..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✅ Caches optimized${NC}"
echo ""

# 11. Final Checks
echo "✅ Deployment Summary"
echo "===================="
echo ""
echo "📂 Project: $PROJECT_DIR"
echo "🐘 PHP Version: $(php -v | head -n 1 | cut -d ' ' -f 2)"
echo "📦 Composer: Installed"
echo "⚡ Node.js: $(node -v)"
echo "📦 NPM: $(npm -v)"
echo ""
echo "🏗️  Vite Build Output:"
ls -lh public/build/assets/ | grep -E '\.(css|js)$' | awk '{print "   " $9 " - " $5}'
echo ""
echo "🎯 Next Steps:"
echo "   1. Configure Nginx/Apache to point to: $PROJECT_DIR/public"
echo "   2. Setup SSL certificate (Let's Encrypt)"
echo "   3. Test website in browser"
echo "   4. Check DevTools → Network tab → Should see:"
echo "      - app-[hash].css (~88KB gzipped)"
echo "      - app-[hash].js (~2KB gzipped)"
echo "      - vendor-[hash].js (~14KB gzipped)"
echo ""
echo "📚 Full documentation: DEPLOY-VPS-VITE.md"
echo ""
echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo ""
echo "🌐 To start Laravel dev server (testing only):"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo ""

