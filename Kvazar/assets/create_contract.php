<?php
session_start();
require_once '../config/db_connect.php';
require_once '../includes/log_action.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $requiredFields = ['entity_type', 'entity_id', 'contract_type', 
                      'contract_number', 'contract_date', 'contract_status'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Determine table and entity based on type
    switch ($data['entity_type']) {
        case 'client':
            $table = 'Client_contracts';
            $entityId = 'Client_id';
            $entityTable = 'Clients';
            break;
        case 'courier':
            $table = 'Courier_contracts';
            $entityId = 'Courier_id';
            $entityTable = 'Couriers';
            break;
        case 'agent':
            $table = 'Agent_contracts';
            $entityId = 'Agent_id';
            $entityTable = 'Agents';
            break;
        default:
            throw new Exception('Invalid entity type');
    }

    // Verify that the status exists
    $statusCheckStmt = $pdo->prepare("SELECT Status_name FROM list_contract_status WHERE Status_name = ?");
    $statusCheckStmt->execute([$data['contract_status']]);
    if (!$statusCheckStmt->fetch()) {
        throw new Exception('Invalid status');
    }

    // Start transaction
    $pdo->beginTransaction();

    // Create the contract
    $stmt = $pdo->prepare("
        INSERT INTO {$table} (
            {$entityId},
            Contract_number,
            Contract_type,
            Contract_date,
            Contract_status,
            Created_at,
            Created_by
        ) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?)
    ");

    $success = $stmt->execute([
        $data['entity_id'],
        $data['contract_number'],
        $data['contract_type'],
        $data['contract_date'],
        $data['contract_status'],
        $_SESSION['user_id']
    ]);

    if ($success) {
        // Get the company name for logging
        $companyStmt = $pdo->prepare("SELECT Full_company_name FROM {$entityTable} WHERE {$entityId} = ?");
        $companyStmt->execute([$data['entity_id']]);
        $companyName = $companyStmt->fetchColumn();

        // Get contract type name
        $typeStmt = $pdo->prepare("SELECT Type_name FROM list_contract_type WHERE Type_id = ?");
        $typeStmt->execute([$data['contract_type']]);
        $typeName = $typeStmt->fetchColumn();

        // Log the action
        $logDetails = json_encode([
            'action' => 'create_contract',
            'contract_number' => $data['contract_number'],
            'contract_id' => $pdo->lastInsertId(),
            'entity_type' => $data['entity_type'],
            'company_name' => $companyName,
            'contract_type' => $typeName,
            'contract_date' => $data['contract_date'],
            'status' => $data['contract_status']
        ]);

        logAction($pdo, $_SESSION['user_id'], 'create_contract', $logDetails);
        
        $pdo->commit();
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to create contract');
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Error creating contract: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 