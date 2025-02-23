<?php
session_start();
require_once '../config/db_connect.php';
require_once '../includes/log_action.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !isset($data['type'])) {
        throw new Exception('Missing required fields');
    }

    // Determine table based on partner type
    switch ($data['type']) {
        case 'client':
            $table = 'Clients';
            $idColumn = 'Client_id';
            break;
        case 'courier':
            $table = 'Couriers';
            $idColumn = 'Courier_id';
            break;
        case 'agent':
            $table = 'Agents';
            $idColumn = 'Agent_id';
            break;
        default:
            throw new Exception('Invalid partner type');
    }

    // Get partner details for logging
    $stmt = $pdo->prepare("SELECT Full_Company_name FROM {$table} WHERE {$idColumn} = ?");
    $stmt->execute([$data['id']]);
    $partnerName = $stmt->fetchColumn();

    // Instead of deleting, update the status to indicate deletion
    $stmt = $pdo->prepare("UPDATE {$table} SET 
        Status = (SELECT Status_id FROM list_partners_status WHERE Status_name = 'Удален'),
        Is_deleted = 1,
        Deleted_at = CURRENT_TIMESTAMP,
        Deleted_by = ?
        WHERE {$idColumn} = ?");
    
    $success = $stmt->execute([$_SESSION['user_id'], $data['id']]);

    if ($success) {
        // Log the action
        $logDetails = json_encode([
            'action' => 'delete_partner',
            'partner_type' => $data['type'],
            'partner_id' => $data['id'],
            'partner_name' => $partnerName
        ]);
        
        logAction($pdo, $_SESSION['user_id'], 'delete_partner', $logDetails);
        
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to delete partner');
    }

} catch (Exception $e) {
    error_log("Error deleting partner: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 