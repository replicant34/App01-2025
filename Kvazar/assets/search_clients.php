<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$userRole = $_SESSION['role'];
$userId = $_SESSION['user_id'];
$searchTerm = $_GET['term'] ?? '';

try {
    switch ($userRole) {
        case 'admin':
        case 'ceo':
            $stmt = $pdo->prepare("
                SELECT Client_id, Full_company_name 
                FROM Clients 
                WHERE Full_company_name LIKE ? 
                ORDER BY Full_company_name 
                LIMIT 10
            ");
            $stmt->execute(['%' . $searchTerm . '%']);
            break;
            
        case 'operator':
            $stmt = $pdo->prepare("
                SELECT DISTINCT c.Client_id, c.Full_company_name 
                FROM Clients c
                JOIN Operator_clients oc ON c.Client_id = oc.Client_id
                WHERE c.Full_company_name LIKE ? 
                AND oc.User_id = ?
                ORDER BY c.Full_company_name 
                LIMIT 10
            ");
            $stmt->execute(['%' . $searchTerm . '%', $userId]);
            break;
            
        case 'client':
            $stmt = $pdo->prepare("
                SELECT c.Client_id, c.Full_company_name 
                FROM Clients c
                JOIN Users u ON c.Client_id = u.Client_id
                WHERE u.User_id = ?
                AND c.Full_company_name LIKE ?
            ");
            $stmt->execute([$userId, '%' . $searchTerm . '%']);
            break;
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?> 