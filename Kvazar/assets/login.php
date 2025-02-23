<?php
session_start();
require_once 'config/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE Login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['Password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['User_id'];
            $_SESSION['full_name'] = $user['Full_name'];
            $_SESSION['role'] = $user['Role'];
            
            // Log successful authentication
            $stmt = $pdo->prepare("INSERT INTO auth_logs (user_id, status, ip_address, user_agent) VALUES (?, 'SUCCESS', ?, ?)");
            $stmt->execute([$user['User_id'], $ip_address, $user_agent]);

            // Redirect based on role
            switch($user['Role']) {
                case 'admin':
                    header('Location: admin_dashboard.php');
                    break;
                case 'client':
                    header('Location: client_dashboard.php');
                    break;
                case 'courier':
                    header('Location: courier_dashboard.php');
                    break;
                case 'agent':
                    header('Location: agent_dashboard.php');
                    break;
            }
            exit();
        } else {
            // Log failed authentication
            $stmt = $pdo->prepare("INSERT INTO auth_logs (status, ip_address, user_agent) VALUES ('FAILURE', ?, ?)");
            $stmt->execute([$ip_address, $user_agent]);

            $error = 'Invalid login credentials';
        }
    } catch(PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}
?> 