<?php
require_once '../config/db_connect.php';

if (isset($_GET['id'])) {
    $courierId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM Couriers WHERE Courier_id = ?");
    $stmt->execute([$courierId]);
    $courier = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($courier);
}
?>  