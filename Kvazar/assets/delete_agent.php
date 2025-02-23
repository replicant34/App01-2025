<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agentId = $_POST['agent_id'];

    try {
        // Fetch client details before deletion
        $stmt = $pdo->prepare("SELECT Full_Company_name FROM Agents WHERE Agent_id = ?");
        $stmt->execute([$agentId]);
        $agent = $stmt->fetch(PDO::FETCH_ASSOC);
        $fullName = $agent['Full_Company_name'];

        // Delete client from the database
        $stmt = $pdo->prepare("DELETE FROM Agents WHERE Agent_id = ?");
        $stmt->execute([$agentId]);

        // Log the delete action
        $logStmt = $pdo->prepare("INSERT INTO action_logs (user_id, action_type, table_name, description, ip_address) VALUES (?, 'Delete', 'Agents', ?, ?)");
        $logStmt->execute([$_SESSION['user_id'], "Deleted agent: $fullName", $_SERVER['REMOTE_ADDR']]);

        // Redirect back to manage_agents.php
        header('Location: manage_agents.php');
        exit();
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?> 