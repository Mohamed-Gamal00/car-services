<?php
echo "=== Environment Variables Check ===\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'NOT SET') . "\n";
echo "DB_PORT: " . ($_ENV['DB_PORT'] ?? 'NOT SET') . "\n";
echo "DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? 'NOT SET') . "\n";
echo "DB_USERNAME: " . ($_ENV['DB_USERNAME'] ?? 'NOT SET') . "\n";
echo "DB_PASSWORD: " . ($_ENV['DB_PASSWORD'] ?? 'NOT SET') . "\n";
echo "\n";

// Load Laravel environment
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== After Loading .env ===\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'NOT SET') . "\n";
echo "DB_PORT: " . ($_ENV['DB_PORT'] ?? 'NOT SET') . "\n";
echo "DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? 'NOT SET') . "\n";
echo "DB_USERNAME: " . ($_ENV['DB_USERNAME'] ?? 'NOT SET') . "\n";
echo "DB_PASSWORD: " . ($_ENV['DB_PASSWORD'] ?? 'NOT SET') . "\n";