<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT Contractors_id, Full_Company_name 
        FROM Contractors 
        LIMIT 1
    ");
    $stmt->execute();
    $contractor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'contractor' => $contractor ?: null
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?> 