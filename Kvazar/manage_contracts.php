<?php
session_start();
require_once 'config/db_connect.php';

// Check authorization
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
    <title>Manage Contracts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/manage_contracts.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'properties/sidebar.php'; ?>
        
        <div id="content">
            <?php include 'properties/NavbarLogout.php'; ?>
            
            <div class="container">
                <div class="page-header">
                    <h2>Manage Contracts</h2>
                </div>

                <!-- Search and Filter Section -->
                <div class="filters-section">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search contracts...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="filters">
                        <select id="typeFilter">
                            <option value="">All Types</option>
                            <?php
                            $stmt = $pdo->query("SELECT Type_id, Type_name FROM list_contract_type ORDER BY Type_name");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='" . htmlspecialchars($row['Type_id']) . "'>" . 
                                     htmlspecialchars($row['Type_name']) . "</option>";
                            }
                            ?>
                        </select>
                        <select id="statusFilter">
                            <option value="">All Statuses</option>
                            <?php
                            $stmt = $pdo->query("SELECT Status_id, Status_name FROM list_contract_status ORDER BY Status_name");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='" . htmlspecialchars($row['Status_id']) . "'>" . 
                                     htmlspecialchars($row['Status_name']) . "</option>";
                            }
                            ?>
                        </select>
                        <div class="date-range">
                            <input type="date" id="dateFrom" placeholder="From">
                            <input type="date" id="dateTo" placeholder="To">
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab-btn active" data-tab="client">Client Contracts</button>
                    <button class="tab-btn" data-tab="courier">Courier Contracts</button>
                    <button class="tab-btn" data-tab="agent">Agent Contracts</button>
                </div>

                <!-- Contracts Table -->
                <div class="table-container">
                    <table id="contractsTable">
                        <thead>
                            <tr>
                                <th data-sort="contract_number">Contract Number <i class="fas fa-sort"></i></th>
                                <th data-sort="company_name">Company Name <i class="fas fa-sort"></i></th>
                                <th data-sort="contract_type">Type <i class="fas fa-sort"></i></th>
                                <th data-sort="contract_date">Date <i class="fas fa-sort"></i></th>
                                <th data-sort="status">Status <i class="fas fa-sort"></i></th>
                                <th data-sort="created_at">Created At <i class="fas fa-sort"></i></th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Move this section to the bottom of the content -->
                <div class="content-footer">
                    <div class="footer-buttons">
                        <a href="new_contract.php" class="btn-primary">
                            <i class="fas fa-plus"></i> New Contract
                        </a>
                        <button id="downloadContracts" class="btn-secondary">
                            <i class="fas fa-download"></i> Download Contracts
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Confirmation Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h3>Enter Action Password</h3>
            <input type="password" id="actionPassword" maxlength="4" placeholder="Enter 4-digit password">
            <div class="modal-buttons">
                <button id="confirmPassword" class="btn-primary">Confirm</button>
                <button id="cancelPassword" class="btn-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <script src="js/manage_contracts.js"></script>
    <script src="js/admin_dashboard.js"></script>
</body>
</html> 