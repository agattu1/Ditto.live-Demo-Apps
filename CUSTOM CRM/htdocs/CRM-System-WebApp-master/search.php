<?php
header('Content-Type: application/json');

// MAMP MySQL configuration
$host = 'localhost';
$port = 3306; // Your confirmed MySQL port
$user = 'root';
$pass = 'root';
$db = 'crm_database';

// Enhanced connection with timeout
try {
    $conn = new mysqli($host, $user, $pass, $db, $port);
    
    // Set timeouts
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
    $conn->options(MYSQLI_OPT_READ_TIMEOUT, 10);
    
    if ($conn->connect_errno) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Verify connection
    if (!$conn->ping()) {
        throw new Exception("MySQL server is not responding");
    }

    // Rest of your search logic...
    $searchTerm = $_GET['q'] ?? '';
    if (empty($searchTerm)) {
        echo json_encode(['error' => 'Search term cannot be empty']);
        exit;
    }

    $searchTerm = $conn->real_escape_string($searchTerm);
    $results = [];

    // Search contacts
    $query = "SELECT * FROM contact WHERE 
            Contact_First LIKE '%$searchTerm%' OR 
            Contact_Last LIKE '%$searchTerm%' OR
            Company LIKE '%$searchTerm%' OR
            Email LIKE '%$searchTerm%' OR
            Phone LIKE '%$searchTerm%'";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $row['type'] = 'contact';
            $results[] = $row;
        }
        $result->free();
    }

    echo json_encode([
        'success' => true,
        'results' => $results
    ]);

} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) $conn->close();
}
?>