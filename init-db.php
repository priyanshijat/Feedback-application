<?php
/**
 * Database Initialization Script
 * This script creates the necessary database and tables
 */
 
// Database credentials
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'feedback_db';
$port = getenv('DB_PORT') ?: '3306';
 
// Create connection (without database first)
$mysqli = new mysqli($host, $user, $password, '', $port);
 
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
 
// Create database if not exists
$create_db = "CREATE DATABASE IF NOT EXISTS $database";
if ($mysqli->query($create_db) === FALSE) {
    die("Error creating database: " . $mysqli->error);
}
 
echo "✓ Database '$database' created/verified successfully!\n";
 
// Select database
$mysqli->select_db($database);
 
// Create feedback table
$create_table = "CREATE TABLE IF NOT EXISTS feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at)
)";
 
if ($mysqli->query($create_table) === FALSE) {
    die("Error creating table: " . $mysqli->error);
}
 
echo "✓ Table 'feedback' created/verified successfully!\n";
 
// Close connection
$mysqli->close();
 
echo "\n✅ Database initialization completed successfully!\n";
?>
 