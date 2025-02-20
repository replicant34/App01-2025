<?php
session_start();
require_once 'config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $companyType = $_POST['company_type'];
    $fullCompanyName = $_POST['full_company_name'];
    $shortCompanyName = $_POST['short_company_name'];
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

    // Validate required fields
    if (empty($fullCompanyName)) {
        $error = 'Full Company Name is required';
    } else {
        // Insert client into database
        try {
            $stmt = $pdo->prepare("INSERT INTO Clients (Company_type, Full_Company_name, Short_Company_name, Contract_type, Contract_number, Contract_date, INN, KPP, OGRN, Physical_address, Legal_address, Bank_name, BIK, Settlement_account, Correspondent_account, Contact_person, Contact_person_position, Contact_person_phone, Contact_person_email, Head_position, Head_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$companyType, $fullCompanyName, $shortCompanyName, $contractType, $contractNumber, $contractDate, $inn, $kpp, $ogrn, $physicalAddress, $legalAddress, $bankName, $bik, $settlementAccount, $correspondentAccount, $contactPerson, $contactPersonPosition, $contactPersonPhone, $contactPersonEmail, $headPosition, $headName]);

            // Log the action
            $stmt = $pdo->prepare("INSERT INTO action_logs (user_id, action_type, table_name, description, ip_address) VALUES (?, 'INSERT', 'Clients', 'Added new client: $fullCompanyName', ?)");
            $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);

            $success = 'Client added successfully.';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Client</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/add_form.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'properties/sidebar.php'; ?>
        <!-- Page Content -->
        <div id="content">
            <?php include 'properties/NavbarLogout.php'; ?>
            
                <div class="container">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <div class="form-container">
                        <div class="form-header">
                            <h1>Add New Client</h1>
                        </div>

                        <form action="" method="POST">
                            <?php include 'properties/add_form.php'; ?>
                            <button type="submit" class="btn-submit">Add Client</button>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    
    <script src="js/admin_dashboard.js"></script>
    <script src="js/autocomplete_clients.js"></script>
</body>
</html> 