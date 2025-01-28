<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'logistic';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_massege =  $e->getMessage(); // Debug: Connection failure message
}
?>