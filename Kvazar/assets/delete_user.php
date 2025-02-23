<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];

    try {
        // Fetch user details before deletion
        $stmt = $pdo->prepare("SELECT Full_name FROM Users WHERE User_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $fullName = $user['Full_name'];

        // Delete user from the database
        $stmt = $pdo->prepare("DELETE FROM Users WHERE User_id = ?");
        $stmt->execute([$userId]);

        // Log the delete action
        $logStmt = $pdo->prepare("INSERT INTO action_logs (user_id, action_type, table_name, description, ip_address) VALUES (?, 'Delete', 'Users', ?, ?)");
        $logStmt->execute([$_SESSION['user_id'], "Deleted user: $fullName", $_SERVER['REMOTE_ADDR']]);

        // Redirect back to manage_users.php
        header('Location: manage_users.php');
        exit();
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?> 