<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'blacklist_alliance');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// API Keys (Keep these secret!)
define('BLA_API_KEY', 'Pkcka4f2BbdHh2FhzJtx'); // Your actual BLA API key
define('TCPA_API_KEY', 'your_tcpa_api_key_here'); // Your TCPA API key

// Create database connection
function getDBConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("ERROR: Could not connect. " . $e->getMessage());
    }
}
?>
