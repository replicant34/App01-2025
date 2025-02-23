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
    $companyType = $_POST['company_type'];
    $fullName = $_POST['full_company_name'];
    $shortName = $_POST['short_company_name'];
    $contractType = $_POST['contract_type'];
    $contractNumber = $_POST['contract_number'];
    $contractDate = $_POST['contract_date'];
    $inn = $_POST['inn'];
    $kpp = $_POST['kpp'];
    $ogrn = $_POST['ogrn'];
    $physicalAddress = $_POST['physical_address'];
    $legalAddress = $_POST['legal_address'];
    $bankName = $_POST['bank_name'];
    $bik = $_POST['bik'];
    $settlementAccount = $_POST['settlement_account'];
    $correspondentAccount = $_POST['correspondent_account'];
    $contactPerson = $_POST['contact_person'];
    $contactPersonPosition = $_POST['contact_person_position'];
    $contactPersonPhone = $_POST['contact_person_phone'];
    $contactPersonEmail = $_POST['contact_person_email'];
    $headPosition = $_POST['head_position'];
    $headName = $_POST['head_name'];

    try {
        $stmt = $pdo->prepare("UPDATE Couriers SET Company_type = ?, Full_Company_name = ?, Short_Company_name = ?, Contract_type = ?, Contract_number = ?, Contract_date = ?, INN = ?, KPP = ?, OGRN = ?, Physical_address = ?, Legal_address = ?, Bank_name = ?, BIK = ?, Settlement_account = ?, Correspondent_account = ?, Contact_person = ?, Contact_person_position = ?, Contact_person_phone = ?, Contact_person_email = ?, Head_position = ?, Head_name = ? WHERE Courier_id = ?");
        $stmt->execute([$companyType, $fullName, $shortName, $contractType, $contractNumber, $contractDate, $inn, $kpp, $ogrn, $physicalAddress, $legalAddress, $bankName, $bik, $settlementAccount, $correspondentAccount, $contactPerson, $contactPersonPosition, $contactPersonPhone, $contactPersonEmail, $headPosition, $headName, $courierId]);

        // Log the edit action
        $logStmt = $pdo->prepare("INSERT INTO action_logs (user_id, action_type, table_name, description, ip_address) VALUES (?, 'EDIT', 'Couriers', 'Editted courier: $fullName', ?)");
        $logStmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);

        // Redirect back to manage_couriers.php
        header('Location: manage_couriers.php');
        exit();
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?> 