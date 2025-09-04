<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust as needed for your domain

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['phone'])) {
    $phoneNumber = filter_var($_GET['phone'], FILTER_SANITIZE_STRING);
    
    if (!preg_match('/^\d{10}$/', $phoneNumber)) {
        echo json_encode(['error' => 'Invalid phone number format']);
        exit;
    }
    
    // Fetch data from Black List Alliance API using server-side key
    $apiKey = BLA_API_KEY; // From config - not visible to client
    $apiUrl = "https://api.blacklistalliance.net/lookup?key=$apiKey&ver=v3&resp=json&phone=$phoneNumber";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        
        // Save to database
        saveToDatabase($phoneNumber, $data);
        
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Failed to fetch data from BLA API']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function saveToDatabase($phoneNumber, $data) {
    try {
        $pdo = getDBConnection();
        
        $stmt = $pdo->prepare("INSERT INTO bla_lookups (phone_number, response_data, lookup_date) 
                               VALUES (:phone_number, :response_data, NOW()) 
                               ON DUPLICATE KEY UPDATE response_data = :response_data, lookup_date = NOW()");
        
        $stmt->execute([
            ':phone_number' => $phoneNumber,
            ':response_data' => json_encode($data)
        ]);
        
    } catch(PDOException $e) {
        // Log error but don't break the API response
        error_log("Database error: " . $e->getMessage());
    }
}
?>
