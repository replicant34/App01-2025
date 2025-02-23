<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agentId = $_POST['agent_id'];
    $companyType = $_POST['company_type'];
    $fullName = $_POST['full_company_name'];
    $shortName = $_POST['short_company_name'];
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
        $stmt = $pdo->prepare("UPDATE Agents SET Company_type = ?, Full_Company_name = ?, Short_Company_name = ?, INN = ?, KPP = ?, OGRN = ?, Physical_address = ?, Legal_address = ?, Bank_name = ?, BIK = ?, Settlement_account = ?, Correspondent_account = ?, Contact_person = ?, Contact_person_position = ?, Contact_person_phone = ?, Contact_person_email = ?, Head_position = ?, Head_name = ? WHERE Agent_id = ?");
        $stmt->execute([$companyType, $fullName, $shortName, $inn, $kpp, $ogrn, $physicalAddress, $legalAddress, $bankName, $bik, $settlementAccount, $correspondentAccount, $contactPerson, $contactPersonPosition, $contactPersonPhone, $contactPersonEmail, $headPosition, $headName, $agentId]);

        // Log the edit action
        $logStmt = $pdo->prepare("INSERT INTO action_logs (user_id, action_type, table_name, description, ip_address) VALUES (?, 'EDIT', 'Agents', 'Editted agent: $fullName', ?)");
        $logStmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);

        // Redirect back to manage_agents.php
        header('Location: manage_agents.php');
        exit();
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?> 