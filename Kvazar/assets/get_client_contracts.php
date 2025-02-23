<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (isset($_GET['client_id'])) {
    $clientId = $_GET['client_id'];
    
    try {
        $stmt = $pdo->prepare("
            SELECT Contract_number, Contract_date 
            FROM Clients 
            WHERE Client_id = ?
        ");
        $stmt->execute([$clientId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Filter out empty values
        $contracts = array_filter([$result['Contract_number']], function($value) {
            return $value !== null && $value !== '';
        });
        
        $contractDate = $result['Contract_date'] ?? null;
        
        echo json_encode([
            'success' => true,
            'contracts' => array_values($contracts),
            'contract_date' => $contractDate
        ]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
} else {
    echo json_encode(['error' => 'No client ID provided']);
}
?> 