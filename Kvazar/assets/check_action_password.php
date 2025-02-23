<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['action']) || !isset($data['password'])) {
        throw new Exception('Missing required fields');
    }

    // Get the correct password for this action
    $stmt = $pdo->prepare("SELECT Action_password FROM list_actions_passwords WHERE Action_id = ?");
    $stmt->execute([$data['action']]);
    $correctPassword = $stmt->fetchColumn();

    // Compare passwords
    $isValid = ($data['password'] === $correctPassword);

    echo json_encode([
        'success' => true,
        'isValid' => $isValid
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 