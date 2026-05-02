#!/bin/bash

# Admin Dashboard Cleanup Script
# This script removes e-commerce related files from the admin dashboard

echo "========================================="
echo "Admin Dashboard Cleanup Script"
echo "Car Cleaning Booking System"
echo "========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to prompt for confirmation
confirm() {
    read -p "$1 (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        return 0
    else
        return 1
    fi
}

# Step 1: Backup
echo -e "${YELLOW}Step 1: Creating Backup${NC}"
if confirm "Do you want to create a git backup?"; then
    git add .
    git commit -m "Backup before admin cleanup"
    git tag backup-before-cleanup-$(date +%Y%m%d-%H%M%S)
    echo -e "${GREEN}✓ Backup created${NC}"
else
    echo -e "${RED}⚠ Skipping backup (NOT RECOMMENDED)${NC}"
fi
echo ""

# Step 2: Backup routes file
echo -e "${YELLOW}Step 2: Backing up routes file${NC}"
if [ -f "routes/dashboard.php" ]; then
    cp routes/dashboard.php routes/dashboard_backup_$(date +%Y%m%d-%H%M%S).php
    echo -e "${GREEN}✓ Routes backed up${NC}"
fi
echo ""

# Step 3: Delete unused controllers
echo -e "${YELLOW}Step 3: Deleting unused controllers${NC}"
if confirm "Delete e-commerce controllers?"; then
    
    # E-commerce controllers
    rm -f app/Http/Controllers/Dashboard/ProductsController.php
    rm -f app/Http/Controllers/Dashboard/ProductSettingsController.php
    rm -f app/Http/Controllers/Dashboard/ProductAvailabilityController.php
    rm -f app/Http/Controllers/Dashboard/ProductsFeatures.php
    rm -f app/Http/Controllers/Dashboard/MainCategoriesController.php
    rm -f app/Http/Controllers/Dashboard/MainCategoriesSettingsController.php
    rm -f app/Http/Controllers/Dashboard/ColorController.php
    rm -f app/Http/Controllers/Dashboard/DesignsController.php
    rm -f app/Http/Controllers/Dashboard/CompaniesController.php
    rm -f app/Http/Controllers/Dashboard/StoreFatuerController.php
    rm -f app/Http/Controllers/Dashboard/ShippingController.php
    rm -rf app/Http/Controllers/Dashboard/Shipping/
    rm -f app/Http/Controllers/Dashboard/ReturnOrderController.php
    rm -f app/Http/Controllers/Dashboard/BulkOrderController.php
    rm -f app/Http/Controllers/Dashboard/RepresentativesOrderController.php
    rm -f app/Http/Controllers/Dashboard/AdvertisementController.php
    rm -f app/Http/Controllers/Dashboard/HeaderTextController.php
    rm -f app/Http/Controllers/Dashboard/HeaderBanerController.php
    rm -f app/Http/Controllers/Dashboard/SendNewsToUsersController.php
    rm -f app/Http/Controllers/Dashboard/DiscountCodeControllerCopy.php
    
    echo -e "${GREEN}✓ Controllers deleted${NC}"
else
    echo -e "${YELLOW}⊘ Skipped controller deletion${NC}"
fi
echo ""

# Step 4: Delete unused models
echo -e "${YELLOW}Step 4: Deleting unused models${NC}"
if confirm "Delete e-commerce models?"; then
    
    rm -f app/Models/Product.php
    rm -f app/Models/ProductImage.php
    rm -f app/Models/ProductFeature.php
    rm -f app/Models/ProductAvailability.php
    rm -f app/Models/MainCategory.php
    rm -f app/Models/FirstSubCategory.php
    rm -f app/Models/SecSubCategory.php
    rm -f app/Models/MainCategorySetting.php
    rm -f app/Models/MainCategoryMainCategorySetting.php
    rm -f app/Models/Color.php
    rm -f app/Models/Design.php
    rm -f app/Models/Designs.php
    rm -f app/Models/Company.php
    rm -f app/Models/StoreFatuer.php
    rm -f app/Models/ShippingCompany.php
    rm -f app/Models/ShippingLocation.php
    rm -f app/Models/ShippingType.php
    rm -f app/Models/ShippingTypesAndPrice.php
    rm -f app/Models/ReturnProduct.php
    rm -f app/Models/BulkOrder.php
    rm -f app/Models/RepresentativesOrder.php
    rm -f app/Models/Advertisement.php
    rm -f app/Models/HeaderText.php
    rm -f app/Models/SendNewsToUser.php
    rm -f app/Models/Cart.php
    rm -f app/Models/OrderItem.php
    rm -f app/Models/OrderAddress.php
    
    echo -e "${GREEN}✓ Models deleted${NC}"
else
    echo -e "${YELLOW}⊘ Skipped model deletion${NC}"
fi
echo ""

# Step 5: Delete unused views
echo -e "${YELLOW}Step 5: Deleting unused views${NC}"
if confirm "Delete e-commerce views?"; then
    
    rm -rf resources/views/dashboard/products/
    rm -rf resources/views/dashboard/products_settings/
    rm -rf resources/views/dashboard/main_categories/
    rm -rf resources/views/dashboard/filters/
    rm -rf resources/views/dashboard/colors/
    rm -rf resources/views/dashboard/designs/
    rm -rf resources/views/dashboard/companies/
    rm -rf resources/views/dashboard/store_featuers/
    rm -rf resources/views/dashboard/shipping_companies/
    rm -rf resources/views/dashboard/shipping_types/
    rm -rf resources/views/dashboard/return_orders/
    rm -rf resources/views/dashboard/bulk_orders/
    rm -rf resources/views/dashboard/representatives_orders/
    rm -rf resources/views/dashboard/advertisements/
    rm -rf resources/views/dashboard/header_text/
    rm -rf resources/views/dashboard/header_banner/
    rm -rf resources/views/dashboard/send_news/
    
    echo -e "${GREEN}✓ Views deleted${NC}"
else
    echo -e "${YELLOW}⊘ Skipped view deletion${NC}"
fi
echo ""

# Step 6: Optional deletions
echo -e "${YELLOW}Step 6: Optional deletions${NC}"

if confirm "Delete CurrencyController? (Keep if multi-currency needed)"; then
    rm -f app/Http/Controllers/Dashboard/CurrencyController.php
    rm -f app/Models/Currency.php
    rm -rf resources/views/dashboard/currencies/
    echo -e "${GREEN}✓ Currency files deleted${NC}"
fi

if confirm "Delete RulesController? (Keep if using RBAC)"; then
    rm -f app/Http/Controllers/Dashboard/RulesController.php
    rm -f app/Models/Rule.php
    rm -f app/Models/RuleAbility.php
    rm -rf resources/views/dashboard/rules/
    echo -e "${GREEN}✓ Rules files deleted${NC}"
fi

if confirm "Delete HeaderBanner files? (Keep if using banners)"; then
    rm -f app/Models/HeaderBanner.php
    rm -rf resources/views/dashboard/header_banner/
    echo -e "${GREEN}✓ Header banner files deleted${NC}"
fi
echo ""

# Step 7: Replace routes file
echo -e "${YELLOW}Step 7: Replacing routes file${NC}"
if confirm "Replace routes/dashboard.php with cleaned version?"; then
    if [ -f "routes/dashboard_cleaned.php" ]; then
        cp routes/dashboard_cleaned.php routes/dashboard.php
        echo -e "${GREEN}✓ Routes file replaced${NC}"
    else
        echo -e "${RED}✗ dashboard_cleaned.php not found${NC}"
    fi
else
    echo -e "${YELLOW}⊘ Skipped routes replacement${NC}"
fi
echo ""

# Step 8: Clear caches
echo -e "${YELLOW}Step 8: Clearing caches${NC}"
if confirm "Clear all Laravel caches?"; then
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan clear-compiled
    composer dump-autoload
    echo -e "${GREEN}✓ Caches cleared${NC}"
else
    echo -e "${YELLOW}⊘ Skipped cache clearing${NC}"
fi
echo ""

# Step 9: Final commit
echo -e "${YELLOW}Step 9: Final commit${NC}"
if confirm "Create final commit?"; then
    git add .
    git commit -m "Admin dashboard cleanup - removed e-commerce functionality"
    echo -e "${GREEN}✓ Changes committed${NC}"
else
    echo -e "${YELLOW}⊘ Skipped final commit${NC}"
fi
echo ""

# Summary
echo "========================================="
echo -e "${GREEN}Cleanup Complete!${NC}"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Test admin login"
echo "2. Check all menu items"
echo "3. Test CRUD operations"
echo "4. Update sidebar navigation"
echo "5. Review and update documentation"
echo ""
echo "If you encounter issues, restore from backup:"
echo "  git checkout backup-before-cleanup-*"
echo ""
