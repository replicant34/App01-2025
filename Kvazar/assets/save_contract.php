<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $requiredFields = ['entity_type', 'entity_id', 'contract_type', 'contract_number', 'contract_date', 'contract_status'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }

        // Get form data
        $entityType = $_POST['entity_type'];
        $entityId = $_POST['entity_id'];
        $contractType = $_POST['contract_type'];
        $contractNumber = $_POST['contract_number'];
        $contractDate = $_POST['contract_date'];
        $contractStatus = $_POST['contract_status'];
        $createdBy = $_SESSION['user_id'];
        
        // Determine which table to use
        switch ($entityType) {
            case 'client':
                $tableName = 'Client_contracts';
                $entityIdColumn = 'Client_id';
                break;
            case 'courier':
                $tableName = 'Courier_contracts';
                $entityIdColumn = 'Courier_id';
                break;
            case 'agent':
                $tableName = 'Agent_contracts';
                $entityIdColumn = 'Agent_id';
                break;
            default:
                throw new Exception("Invalid entity type: {$entityType}");
        }
        
        // Insert the contract
        $stmt = $pdo->prepare("
            INSERT INTO {$tableName} 
            ({$entityIdColumn}, Contract_type, Contract_number, Contract_date, Contract_status, Created_by)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $success = $stmt->execute([
            $entityId,
            $contractType,
            $contractNumber,
            $contractDate,
            $contractStatus,
            $createdBy
        ]);

        if (!$success) {
            throw new Exception("Database insert failed");
        }
        
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        error_log("Error saving contract: " . $e->getMessage());
        echo json_encode([
            'error' => 'Error saving contract',
            'details' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?> 