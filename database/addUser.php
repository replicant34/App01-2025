<?php
session_start();

$table_name = 'users';

// Collect POST data
$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    die("Error: Missing required fields."); // Debug: Check if all fields are set
}

// Encrypt the password
$encrypted = password_hash($password, PASSWORD_DEFAULT);

// Set created_at and updated_at to the current timestamp
$created_at = date('Y-m-d H:i:s');
$updated_at = $created_at;

include('connection.php'); // Include database connection

try {
    // Use prepared statement to insert data
    $stmt = $conn->prepare("INSERT INTO $table_name (first_name, last_name, email, password, created_at, updated_at) 
                            VALUES (:first_name, :last_name, :email, :password, :created_at, :updated_at)");
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $encrypted);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->bindParam(':updated_at', $updated_at);

    $stmt->execute();

    $response = [
        'success' => true,
        'message' => $first_name . ' ' . $last_name . ' Successfully added'
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../user-add.php');
?>