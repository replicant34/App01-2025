<?php
require_once 'config/db_connect.php';

$term = $_GET['term'];
$column = $_GET['column'];

$validColumns = ['Full_name', 'Client_id', 'Position', 'Phone', 'Email', 'Login', 'Role'];

if (in_array($column, $validColumns)) {
    $stmt = $pdo->prepare("SELECT DISTINCT $column FROM Users WHERE $column LIKE ? LIMIT 10");
    $stmt->execute(["%$term%"]);
    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($results);
}
?> 