<?php
require_once '../config/db_connect.php';

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['role'])) {
    $role = $_GET['role'];
    $table = '';

    // Whitelist table names to prevent SQL injection
    $validTables = [
        'client' => 'Clients',
        'courier' => 'Couriers',
        'agent' => 'Agents'
    ];

    $validColumn = [
        'client' => 'Client_id',
        'courier' => 'Courier_id',
        'agent' => 'Agent_id'
    ];

    if (array_key_exists($role, $validTables) && array_key_exists($role, $validColumn)) {
        $table = $validTables[$role];
        $column = $validColumn[$role];
        
        try {
            $stmt = $pdo->prepare("SELECT $column as id, Short_Company_name FROM {$table}");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode($results);
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Database error occurred']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid role specified']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No role specified']);
}
?> 