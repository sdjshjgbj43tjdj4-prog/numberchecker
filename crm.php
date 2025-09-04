<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust as needed for your domain

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['phone'])) {
        $phoneNumber = filter_var($input['phone'], FILTER_SANITIZE_STRING);
        
        if (!preg_match('/^\d{10}$/', $phoneNumber)) {
            echo json_encode(['error' => 'Invalid phone number format']);
            exit;
        }
        
        try {
            $pdo = getDBConnection();
            
            $stmt = $pdo->prepare("SELECT * FROM crm_data WHERE phone = :phone");
            $stmt->execute([':phone' => $phoneNumber]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'No CRM data found for this phone number']);
            }
            
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Phone number not provided']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
