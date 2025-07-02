<?php
// MAMP MySQL configuration - PORT 3306
$host = 'localhost';
$port = 3306; // Your actual MySQL port
$user = 'root';
$pass = 'root';

// Test connection with error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "Trying to connect to MySQL...<br>";
    $conn = new mysqli($host, $user, $pass, '', $port);
    
    if ($conn->connect_errno) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "✅ Successfully connected to MySQL server!<br><br>";
    
    // Verify database exists
    if ($conn->select_db('crm_database')) {
        echo "✅ Database 'crm_database' accessible!";
    } else {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $conn->close();
} catch (Exception $e) {
    die("<div style='color:red'>❌ Error: " . $e->getMessage() . "</div>");
}
?>