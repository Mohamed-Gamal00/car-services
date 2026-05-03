#!/bin/bash

# Payment Flow Test Script
# This script helps test the payment flow fixes

echo "==================================="
echo "Payment Flow Test Helper"
echo "==================================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${RED}Error: .env file not found${NC}"
    exit 1
fi

# Get base URL from .env or use default
BASE_URL="http://127.0.0.1:8001/api/v1"

echo -e "${YELLOW}Base URL: ${BASE_URL}${NC}"
echo ""

# Function to test endpoint
test_endpoint() {
    local method=$1
    local endpoint=$2
    local data=$3
    local token=$4
    
    echo -e "${YELLOW}Testing: ${method} ${endpoint}${NC}"
    
    if [ -n "$token" ]; then
        if [ -n "$data" ]; then
            curl -X ${method} "${BASE_URL}${endpoint}" \
                -H "Content-Type: application/json" \
                -H "Accept: application/json" \
                -H "Authorization: Bearer ${token}" \
                -d "${data}" \
                -w "\nHTTP Status: %{http_code}\n" \
                -s
        else
            curl -X ${method} "${BASE_URL}${endpoint}" \
                -H "Content-Type: application/json" \
                -H "Accept: application/json" \
                -H "Authorization: Bearer ${token}" \
                -w "\nHTTP Status: %{http_code}\n" \
                -s
        fi
    else
        if [ -n "$data" ]; then
            curl -X ${method} "${BASE_URL}${endpoint}" \
                -H "Content-Type: application/json" \
                -H "Accept: application/json" \
                -d "${data}" \
                -w "\nHTTP Status: %{http_code}\n" \
                -s
        else
            curl -X ${method} "${BASE_URL}${endpoint}" \
                -H "Content-Type: application/json" \
                -H "Accept: application/json" \
                -w "\nHTTP Status: %{http_code}\n" \
                -s
        fi
    fi
    
    echo ""
    echo "-----------------------------------"
    echo ""
}

# Check database connection
echo -e "${GREEN}1. Checking database connection...${NC}"
php artisan db:show 2>/dev/null || echo "Database connection check skipped"
echo ""

# Check payments table structure
echo -e "${GREEN}2. Checking payments table structure...${NC}"
php artisan db:table payments 2>/dev/null || echo "Table structure check skipped"
echo ""

# Show recent payments
echo -e "${GREEN}3. Recent payments (last 5):${NC}"
mysql -u$(grep DB_USERNAME .env | cut -d '=' -f2) \
      -p$(grep DB_PASSWORD .env | cut -d '=' -f2) \
      $(grep DB_DATABASE .env | cut -d '=' -f2) \
      -e "SELECT id, user_id, payment_id, reference, status, amount, created_at FROM payments ORDER BY id DESC LIMIT 5;" 2>/dev/null || echo "Database query skipped"
echo ""

# Show user packages
echo -e "${GREEN}4. Recent user packages (last 5):${NC}"
mysql -u$(grep DB_USERNAME .env | cut -d '=' -f2) \
      -p$(grep DB_PASSWORD .env | cut -d '=' -f2) \
      $(grep DB_DATABASE .env | cut -d '=' -f2) \
      -e "SELECT id, user_id, package_id, status, reference, remaining_washes, expiry_date FROM user_packages ORDER BY id DESC LIMIT 5;" 2>/dev/null || echo "Database query skipped"
echo ""

# Interactive testing
echo -e "${YELLOW}==================================="
echo "Interactive API Testing"
echo "===================================${NC}"
echo ""
echo "To test the payment flow:"
echo ""
echo "1. Login to get token:"
echo "   curl -X POST ${BASE_URL}/login \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"phone\":\"YOUR_PHONE\",\"password\":\"YOUR_PASSWORD\"}'"
echo ""
echo "2. Get available packages:"
echo "   curl -X GET ${BASE_URL}/packages \\"
echo "     -H 'Authorization: Bearer YOUR_TOKEN'"
echo ""
echo "3. Subscribe to package:"
echo "   curl -X POST ${BASE_URL}/subscribe \\"
echo "     -H 'Authorization: Bearer YOUR_TOKEN' \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"package_id\":1,\"payment_method\":\"creditcard\"}'"
echo ""
echo "4. Renew package:"
echo "   curl -X POST ${BASE_URL}/renewal-subscribe \\"
echo "     -H 'Authorization: Bearer YOUR_TOKEN' \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"package_id\":1,\"payment_method\":\"creditcard\"}'"
echo ""

# Check for duplicate payments
echo -e "${GREEN}5. Checking for duplicate payment_ids:${NC}"
mysql -u$(grep DB_USERNAME .env | cut -d '=' -f2) \
      -p$(grep DB_PASSWORD .env | cut -d '=' -f2) \
      $(grep DB_DATABASE .env | cut -d '=' -f2) \
      -e "SELECT payment_id, COUNT(*) as count FROM payments GROUP BY payment_id HAVING count > 1;" 2>/dev/null || echo "Database query skipped"
echo ""

# Check for empty references
echo -e "${GREEN}6. Checking for empty references:${NC}"
mysql -u$(grep DB_USERNAME .env | cut -d '=' -f2) \
      -p$(grep DB_PASSWORD .env | cut -d '=' -f2) \
      $(grep DB_DATABASE .env | cut -d '=' -f2) \
      -e "SELECT COUNT(*) as empty_references FROM payments WHERE reference = '' OR reference IS NULL;" 2>/dev/null || echo "Database query skipped"
echo ""

echo -e "${GREEN}Test script completed!${NC}"
