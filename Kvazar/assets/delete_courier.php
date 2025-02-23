<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courierId = $_POST['courier_id'];

    try {
        // Fetch client details before deletion
        $stmt = $pdo->prepare("SELECT Full_Company_name FROM Couriers WHERE Courier_id = ?");
        $stmt->execute([$courierId]);
        $courier = $stmt->fetch(PDO::FETCH_ASSOC);
        $fullName = $courier['Full_Company_name'];

        // Delete client from the database
        $stmt = $pdo->prepare("DELETE FROM Couriers WHERE Courier_id = ?");
        $stmt->execute([$courierId]);

        // Log the delete action
        $logStmt = $pdo->prepare("INSERT INTO action_logs (user_id, action_type, table_name, description, ip_address) VALUES (?, 'Delete', 'Couriers', ?, ?)");
        $logStmt->execute([$_SESSION['user_id'], "Deleted courier: $fullName", $_SERVER['REMOTE_ADDR']]);

        // Redirect back to manage_couriers.php
        header('Location: manage_couriers.php');
        exit();
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?> 