<?php
require_once '../config/db_connect.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="users.csv"');

$output = fopen('php://output', 'w');

// Update the CSV headers to include all columns
fputcsv($output, [
    'User ID', 'Full Name', 'Client ID', 'Position', 'Phone', 'Email', 'Login', 'Role', 'Created At'
]);

// Fetch all columns from the Users table
$stmt = $pdo->query("SELECT User_id, Full_name, Client_id, Position, Phone, Email, Login, Role, Created_at FROM Users");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
?> 