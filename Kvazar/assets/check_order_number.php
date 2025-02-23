<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (isset($_GET['order_number'])) {
    $orderNumber = $_GET['order_number'];
    
    try {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM Orders 
            WHERE Order_number = ?
        ");
        $stmt->execute([$orderNumber]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'isUnique' => $result['count'] == 0
        ]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
} else {
    echo json_encode(['error' => 'No order number provided']);
}
?> 