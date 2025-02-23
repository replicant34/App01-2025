<?php
session_start();
require_once 'config/db_connect.php';
require_once 'includes/form_helpers.php';

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'ceo', 'operator'])) {
    header('Location: index.php');
    exit();
}

$userRole = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contract</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/forms.css">
    <!-- Add Flatpickr for date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'properties/sidebar.php'; ?>
        
        <div id="content">
            <?php include 'properties/NavbarLogout.php'; ?>
            
            <div class="form-container">
                <div class="form-header">
                    <h1>New Contract</h1>
                </div>

                <form id="contractForm" method="POST" enctype="multipart/form-data" class="standard-form">
                    <!-- Entity Type Selection -->
                    <div class="input-group">
                        <label for="entityType">Entity Type</label>
                        <select id="entityType" name="entity_type" required>
                            <option value="">Select Type</option>
                            <option value="client">Client</option>
                            <option value="courier">Courier</option>
                            <option value="agent">Agent</option>
                        </select>
                    </div>

                    <!-- Entity Selection -->
                    <div class="input-group">
                        <label for="entityId">Select Company</label>
                        <select id="entityId" name="entity_id" required disabled>
                            <option value="">First select type</option>
                        </select>
                    </div>

                    <!-- Contract Type -->
                    <div class="input-group">
                        <label for="contractType">Contract Type</label>
                        <select id="contractType" name="contract_type" required disabled>
                            <option value="">Select Type</option>
                            <?php
                            try {
                                $stmt = $pdo->query("SELECT Type_id, Type_name FROM list_contract_type ORDER BY Type_name");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='" . htmlspecialchars($row['Type_id']) . "'>" . 
                                         htmlspecialchars($row['Type_name']) . "</option>";
                                }
                            } catch (PDOException $e) {
                                error_log("Error fetching contract types: " . $e->getMessage());
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Contract Number -->
                    <div class="input-group">
                        <label for="contractNumber">Contract Number</label>
                        <div class="input-with-button">
                            <input type="text" id="contractNumber" name="contract_number" required disabled>
                            <button type="button" id="generateNumber" class="btn-secondary" disabled>
                                <i class="fas fa-sync-alt"></i> Generate
                            </button>
                        </div>
                        <div id="contractNumberMessage" class="message"></div>
                    </div>

                    <!-- Contract Date -->
                    <div class="input-group">
                        <label for="contractDate">Contract Date</label>
                        <input type="text" id="contractDate" name="contract_date" required disabled>
                    </div>

                    <!-- Contract Status -->
                    <div class="input-group">
                        <label for="contractStatus">Status:</label>
                        <select id="contractStatus" name="contract_status" required>
                            <?php
                            $stmt = $pdo->query("SELECT Status_name, Status_color FROM list_contract_status ORDER BY Status_name");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='" . htmlspecialchars($row['Status_name']) . "' " .
                                     "data-color='" . htmlspecialchars($row['Status_color']) . "'>" . 
                                     htmlspecialchars($row['Status_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn-primary">Create Contract</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="js/new_contract.js"></script>
    <script src="js/admin_dashboard.js"></script>
</body>
</html> 