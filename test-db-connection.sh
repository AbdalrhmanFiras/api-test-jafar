#!/bin/bash

echo "Testing database connection from container..."
echo "=============================================="

# Test 1: Ping database host
echo ""
echo "1. Testing ping to database host..."
ping -c 3 168.231.110.172

# Test 2: Check if port is accessible
echo ""
echo "2. Testing port 8968 accessibility..."
timeout 5 bash -c "</dev/tcp/168.231.110.172/8968" && echo "✅ Port 8968 is accessible" || echo "❌ Port 8968 is NOT accessible"

# Test 3: Test MySQL connection
echo ""
echo "3. Testing MySQL connection..."
mysql -h 168.231.110.172 -P 8968 -u mysql -p'NhUFwbcF6S7Noi7T1PvORJWM0J3FQCAhW7hOTvnmK5kbie1i9cnKNOcSH1juXokL' -e "SELECT 1;" 2>&1 && echo "✅ MySQL connection successful" || echo "❌ MySQL connection failed"

# Test 4: Test with PHP PDO
echo ""
echo "4. Testing with PHP PDO..."
php -r "
try {
    \$pdo = new PDO('mysql:host=168.231.110.172;port=8968;dbname=default', 'mysql', 'NhUFwbcF6S7Noi7T1PvORJWM0J3FQCAhW7hOTvnmK5kbie1i9cnKNOcSH1juXokL', [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo '✅ PHP PDO connection successful\n';
} catch (PDOException \$e) {
    echo '❌ PHP PDO connection failed: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "=============================================="
echo "Tests completed!"

