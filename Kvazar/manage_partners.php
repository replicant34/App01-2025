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
    <title>Manage Partners</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/manage_partners.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'properties/sidebar.php'; ?>
        
        <div id="content">
            <?php include 'properties/NavbarLogout.php'; ?>
            
            <div class="container">
                <div class="page-header">
                    <h2>Manage Partners</h2>
                </div>

                <!-- Search and Filter Section -->
                <div class="filters-section">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search partners...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="filters">
                        <select id="statusFilter">
                            <option value="">All Statuses</option>
                            <?php
                            $stmt = $pdo->query("SELECT Status_id, Status_name FROM list_partners_status ORDER BY Status_name");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='" . htmlspecialchars($row['Status_id']) . "'>" . 
                                     htmlspecialchars($row['Status_name']) . "</option>";
                            }
                            ?>
                        </select>
                        <select id="bankFilter">
                            <option value="">All Banks</option>
                            <?php
                            // Get unique bank names from all partner tables
                            $stmt = $pdo->query("
                                SELECT DISTINCT Bank_name FROM (
                                    SELECT Bank_name FROM Clients
                                    UNION
                                    SELECT Bank_name FROM Couriers
                                    UNION
                                    SELECT Bank_name FROM Agents
                                ) as banks WHERE Bank_name IS NOT NULL ORDER BY Bank_name
                            ");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='" . htmlspecialchars($row['Bank_name']) . "'>" . 
                                     htmlspecialchars($row['Bank_name']) . "</option>";
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
                    <button class="tab-btn active" data-tab="client">Clients</button>
                    <button class="tab-btn" data-tab="courier">Couriers</button>
                    <button class="tab-btn" data-tab="agent">Agents</button>
                </div>

                <!-- Partners Table -->
                <div class="table-container">
                    <table id="partnersTable">
                        <thead>
                            <tr id="tableHeaders">
                                <!-- Headers will be generated by JavaScript -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Footer Buttons -->
                <div class="content-footer">
                    <div class="footer-buttons">
                        <button id="columnSettingsBtn" class="btn-secondary">
                            <i class="fas fa-columns"></i> Column Settings
                        </button>
                        <button id="downloadPartners" class="btn-secondary">
                            <i class="fas fa-download"></i> Download Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Column Settings Modal -->
    <div id="columnSettingsModal" class="modal">
        <div class="modal-content">
            <h3>Column Visibility</h3>
            <div id="columnCheckboxes">
                <!-- Checkboxes will be added via JavaScript -->
            </div>
            <div class="modal-buttons">
                <button id="applyColumnSettings" class="btn-primary">Apply</button>
                <button id="cancelColumnSettings" class="btn-secondary">Cancel</button>
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

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Edit Partner</h3>
            <form id="editForm" class="edit-form">
                <input type="hidden" id="editId">
                <input type="hidden" id="editType">
                
                <div class="form-row">
                    <div class="form-column">
                        <h4>Basic Information</h4>
                        <div class="form-group">
                            <label for="editCompanyType">Company Type</label>
                            <input type="text" id="editCompanyType" name="Company_type" required>
                        </div>

                        <div class="form-group">
                            <label for="editFullName">Full Company Name</label>
                            <input type="text" id="editFullName" name="Full_Company_name" required>
                        </div>

                        <div class="form-group">
                            <label for="editShortName">Short Company Name</label>
                            <input type="text" id="editShortName" name="Short_Company_name" required>
                        </div>

                        <div class="form-group">
                            <label for="editStatus">Status</label>
                            <select id="editStatus" name="Status" required>
                                <?php
                                $stmt = $pdo->query("SELECT Status_id, Status_name, Status_color FROM list_partners_status ORDER BY Status_name");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='" . htmlspecialchars($row['Status_id']) . "' data-color='" . htmlspecialchars($row['Status_color']) . "'>" . 
                                         htmlspecialchars($row['Status_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editINN">INN</label>
                            <input type="text" id="editINN" name="INN" required>
                        </div>

                        <div class="form-group">
                            <label for="editKPP">KPP</label>
                            <input type="text" id="editKPP" name="KPP" required>
                        </div>

                        <div class="form-group">
                            <label for="editOGRN">OGRN</label>
                            <input type="text" id="editOGRN" name="OGRN" required>
                        </div>
                    </div>

                    <div class="form-column">
                        <h4>Address Information</h4>
                        <div class="form-group">
                            <label for="editPhysicalAddress">Physical Address</label>
                            <input type="text" id="editPhysicalAddress" name="Physical_address" required>
                        </div>

                        <div class="form-group">
                            <label for="editLegalAddress">Legal Address</label>
                            <input type="text" id="editLegalAddress" name="Legal_address" required>
                        </div>

                        <h4>Bank Information</h4>
                        <div class="form-group">
                            <label for="editBankName">Bank Name</label>
                            <input type="text" id="editBankName" name="Bank_name" required>
                        </div>

                        <div class="form-group">
                            <label for="editBIK">BIK</label>
                            <input type="text" id="editBIK" name="BIK" required>
                        </div>

                        <div class="form-group">
                            <label for="editSettlementAccount">Settlement Account</label>
                            <input type="text" id="editSettlementAccount" name="Settlement_account" required>
                        </div>

                        <div class="form-group">
                            <label for="editCorrespondentAccount">Correspondent Account</label>
                            <input type="text" id="editCorrespondentAccount" name="Correspondent_account" required>
                        </div>
                    </div>

                    <div class="form-column">
                        <h4>Contact Information</h4>
                        <div class="form-group">
                            <label for="editContactPerson">Contact Person</label>
                            <input type="text" id="editContactPerson" name="Contact_person" required>
                        </div>

                        <div class="form-group">
                            <label for="editContactPersonPosition">Contact Person Position</label>
                            <input type="text" id="editContactPersonPosition" name="Contact_person_position" required>
                        </div>

                        <div class="form-group">
                            <label for="editContactPersonPhone">Contact Person Phone</label>
                            <input type="text" id="editContactPersonPhone" name="Contact_person_phone" required>
                        </div>

                        <div class="form-group">
                            <label for="editContactPersonEmail">Contact Person Email</label>
                            <input type="email" id="editContactPersonEmail" name="Contact_person_email" required>
                        </div>

                        <div class="form-group">
                            <label for="editHeadPosition">Head Position</label>
                            <input type="text" id="editHeadPosition" name="Head_position" required>
                        </div>

                        <div class="form-group">
                            <label for="editHeadName">Head Name</label>
                            <input type="text" id="editHeadName" name="Head_name" required>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="submit" class="btn-primary">Save Changes</button>
                    <button type="button" class="btn-secondary" onclick="document.getElementById('editModal').style.display='none'">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Status Change Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <h3>Change Partner Status</h3>
            <div class="status-options">
                <?php
                $stmt = $pdo->query("SELECT Status_id, Status_name, Status_color FROM list_partners_status ORDER BY Status_name");
                while ($status = $stmt->fetch()) {
                    echo "<button class='status-option' data-status-id='{$status['Status_id']}' 
                        style='border-color: {$status['Status_color']}'>
                        <span class='status-dot' style='background-color: {$status['Status_color']}'></span>
                        {$status['Status_name']}
                    </button>";
                }
                ?>
            </div>
            <div class="modal-buttons">
                <button id="cancelStatusChange" class="btn-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <script src="js/manage_partners.js"></script>
    <script src="js/admin_dashboard.js"></script>
</body>
</html> 