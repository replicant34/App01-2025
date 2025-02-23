<?php
require_once '../config/db_connect.php';

if (isset($_GET['id'])) {
    $agentId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM Agents WHERE Agent_id = ?");
    $stmt->execute([$agentId]);
    $agent = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($agent);
}
?>  