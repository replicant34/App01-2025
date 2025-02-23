<?php
require_once '../config/db_connect.php';

if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM Clients WHERE Client_id = ?");
    $stmt->execute([$clientId]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($client);
}
?>  