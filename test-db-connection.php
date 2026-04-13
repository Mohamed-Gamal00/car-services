<?php
/**
 * Quick Database Connection Test
 * Run this file to test your database credentials
 * 
 * Usage: php test-db-connection.php
 */

// Load environment variables
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "===========================================\n";
echo "  Quick Clean - Database Connection Test  \n";
echo "===========================================\n\n";

// Get credentials from .env
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? '3306';
$database = $_ENV['DB_DATABASE'] ?? '';
$username = $_ENV['DB_USERNAME'] ?? '';
$password = $_ENV['DB_PASSWORD'] ?? '';

echo "Testing connection with:\n";
echo "  Host: $host\n";
echo "  Port: $port\n";
echo "  Database: $database\n";
echo "  Username: $username\n";
echo "  Password: " . (empty($password) ? '(empty)' : str_repeat('*', strlen($password))) . "\n\n";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ SUCCESS! Database connection established.\n\n";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version");
    $result = $stmt->fetch();
    echo "MySQL Version: " . $result['version'] . "\n\n";
    
    // Check if database exists and has tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "Found " . count($tables) . " tables in database:\n";
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
        echo "\n⚠️  WARNING: Database already has tables!\n";
        echo "Running 'migrate:fresh' will DELETE all existing data.\n";
        echo "Make sure to backup your data first!\n\n";
    } else {
        echo "✅ Database is empty and ready for migration.\n\n";
    }
    
    echo "You can now run:\n";
    echo "  php artisan migrate:fresh --seed\n\n";
    
} catch (PDOException $e) {
    echo "❌ ERROR: Could not connect to database!\n\n";
    echo "Error Message: " . $e->getMessage() . "\n\n";
    
    echo "Common solutions:\n";
    echo "1. Check if MySQL is running\n";
    echo "2. Verify database name exists\n";
    echo "3. Check username and password are correct\n";
    echo "4. Ensure user has permissions on the database\n\n";
    
    echo "To create the database, run in MySQL:\n";
    echo "  CREATE DATABASE $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n\n";
    
    echo "To grant permissions:\n";
    echo "  GRANT ALL PRIVILEGES ON $database.* TO '$username'@'$host';\n";
    echo "  FLUSH PRIVILEGES;\n\n";
    
    exit(1);
}

echo "===========================================\n";
echo "  Test Complete\n";
echo "===========================================\n";
