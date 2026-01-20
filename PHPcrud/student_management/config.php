<?php
/**
 * Database Configuration File
 * Update these values according to your MySQL server settings
 */

// Database connection settings
define('DB_HOST', 'localhost');      // Database host (usually 'localhost')
define('DB_USER', 'root');           // Database username (default is 'root')
define('DB_PASS', 'Sharingan_82');               // Database password (empty by default in XAMPP/WAMP)
define('DB_NAME', 'student_management'); // Database name

/**
 * Create database connection using MySQLi
 * @return mysqli|false Returns mysqli object on success, false on failure
 */
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

/**
 * Alternative: Using PDO (commented out by default)
 * Uncomment if you prefer PDO over MySQLi
 */
/*
function getDBConnectionPDO() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
*/
?>

