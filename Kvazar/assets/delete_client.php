<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clientId = $_POST['client_id'];

    try {
        // Fetch client details before deletion
        $stmt = $pdo->prepare("SELECT Full_Company_name FROM Clients WHERE Client_id = ?");
        $stmt->execute([$clientId]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        $fullName = $client['Full_Company_name'];

        // Delete client from the database
        $stmt = $pdo->prepare("DELETE FROM Clients WHERE Client_id = ?");
        $stmt->execute([$clientId]);

        // Log the delete action
        $logStmt = $pdo->prepare("INSERT INTO action_logs (user_id, action_type, table_name, description, ip_address) VALUES (?, 'Delete', 'Clients', ?, ?)");
        $logStmt->execute([$_SESSION['user_id'], "Deleted client: $fullName", $_SERVER['REMOTE_ADDR']]);

        // Redirect back to manage_clients.php
        header('Location: manage_clients.php');
        exit();
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?> 