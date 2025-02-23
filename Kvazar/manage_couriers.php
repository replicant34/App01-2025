<?php
session_start();
require_once 'config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Pagination setup
$limit = 10; // Number of entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Main query with LEFT JOIN
$query = "
    SELECT 
        c.*,
        cc.Contract_number,
        cc.Contract_date,
        t.Type_name as Contract_type
    FROM Couriers c
    LEFT JOIN (
        SELECT Courier_id, Contract_number, Contract_date, Contract_type
        FROM Courier_contracts
        WHERE Contract_id IN (
            SELECT MAX(Contract_id)
            FROM Courier_contracts
            GROUP BY Courier_id
        )
    ) cc ON c.Courier_id = cc.Courier_id
    LEFT JOIN list_contract_type t ON cc.Contract_type = t.Type_id
    ORDER BY c.created_at DESC
    LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$couriers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total number of clients for pagination
$totalStmt = $pdo->query("SELECT COUNT(*) FROM Couriers");
$totalCouriers = $totalStmt->fetchColumn();
$totalPages = ceil($totalCouriers / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Couriers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/manage_tables.css">
    <link rel="stylesheet" href="css/manage_clients.css">
    
</head>
<body>
    <div class="wrapper">
        <?php include 'properties/sidebar.php'; ?>
        <div id="content">
            <?php include 'properties/NavbarLogout.php'; ?>          
                
                <div class="mc-content-wrapper">
                    <div class="page-header">
                        <h1>Manage Couriers</h1>
                    </div>
                    <div class="mc-slider-controls">
                        <button id="mc-scroll-left" class="mc-slider-btn"><i class="fas fa-chevron-left"></i></button>
                        <button id="mc-scroll-right" class="mc-slider-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="mc-table-container">
                        <table class="mc-clients-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Full Name</th>
                                    <th>Short Name</th>
                                    <th>Contract Type</th>
                                    <th>Contract Number</th>
                                    <th>Contract Date</th>
                                    <th>INN</th>
                                    <th>KPP</th>
                                    <th>OGRN</th>
                                    <th>Physical_address</th>
                                    <th>Legal_address</th>
                                    <th>Bank_name</th>
                                    <th>BIK</th>
                                    <th>Sett</th>
                                    <th>Corr.</th>
                                    <th>Contact_person</th>
                                    <th>Contact_person_position</th>
                                    <th>Contact_person_phone</th>
                                    <th>Contact_person_email</th>
                                    <th>Head_position</th>
                                    <th>Head_name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($couriers as $courier): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($courier['Company_type']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Full_Company_name']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Short_Company_name']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Contract_type']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Contract_number']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Contract_date']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['INN']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['KPP']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['OGRN']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Physical_address']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Legal_address']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Bank_name']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['BIK']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Settlement_account']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Correspondent_account']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Contact_person']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Contact_person_position']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Contact_person_phone']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Contact_person_email']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Head_position']); ?></td>
                                        <td><?php echo htmlspecialchars($courier['Head_name']); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="dropbtn"><i class="fas fa-ellipsis-v"></i></button>
                                                <div class="dropdown-content">
                                                    <a href="#" class="edit" data-id="<?php echo $courier['Courier_id']; ?>">Edit</a>
                                                    <a href="#" class="delete" data-id="<?php echo $courier['Courier_id']; ?>">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mc-pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                    </div>

                    <button id="mc-download-csv" class="mc-btn-download">Download CSV</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="mc-editModal" class="mc-modal">
        <div class="mc-modal-content">
            <span class="mc-close">&times;</span>
            <h2>Edit Courier</h2>
            <form id="mc-editForm">
                <input type="hidden" id="editCourierId" name="courier_id">
                <div class="mc-form-group">
                    <label for="editCompanyType">Company Type</label>
                    <input type="text" id="editCompanyType" name="company_type">
                </div>
                <div class="mc-form-group">
                    <label for="editFullName">Full Company Name</label>
                    <input type="text" id="editFullName" name="full_company_name">
                </div>
                <div class="mc-form-group">
                    <label for="editShortName">Short Company Name</label>
                    <input type="text" id="editShortName" name="short_company_name">
                </div>
                <div class="mc-form-group">
                    <label for="editContractType">Contract Type</label>
                    <input type="text" id="editContractType" name="contract_type">
                </div>
                <div class="mc-form-group">
                    <label for="editContractNumber">Contract Number</label>
                    <input type="text" id="editContractNumber" name="contract_number">
                </div>
                <div class="mc-form-group">
                    <label for="editContractDate">Contract Date</label>
                    <input type="date" id="editContractDate" name="contract_date">
                </div>
                <div class="mc-form-group">
                    <label for="editINN">INN</label>
                    <input type="text" id="editINN" name="inn">
                </div>
                <div class="mc-form-group">
                    <label for="editKPP">KPP</label>
                    <input type="text" id="editKPP" name="kpp">
                </div>
                <div class="mc-form-group">
                    <label for="editOGRN">OGRN</label>
                    <input type="text" id="editOGRN" name="ogrn">
                </div>
                <div class="mc-form-group">
                    <label for="editPhysicalAddress">Physical Address</label>
                    <input type="text" id="editPhysicalAddress" name="physical_address">
                </div>
                <div class="mc-form-group">
                    <label for="editLegalAddress">Legal Address</label>
                    <input type="text" id="editLegalAddress" name="legal_address">
                </div>
                <div class="mc-form-group">
                    <label for="editBankName">Bank Name</label>
                    <input type="text" id="editBankName" name="bank_name">
                </div>
                <div class="mc-form-group">
                    <label for="editBIK">BIK</label>
                    <input type="text" id="editBIK" name="bik">
                </div>
                <div class="mc-form-group">
                    <label for="editSettlementAccount">Settlement Account</label>
                    <input type="text" id="editSettlementAccount" name="settlement_account">
                </div>
                <div class="mc-form-group">
                    <label for="editCorrespondentAccount">Correspondent Account</label>
                    <input type="text" id="editCorrespondentAccount" name="correspondent_account">
                </div>
                <div class="mc-form-group">
                    <label for="editContactPerson">Contact Person</label>
                    <input type="text" id="editContactPerson" name="contact_person">
                </div>
                <div class="mc-form-group">
                    <label for="editContactPersonPosition">Contact Person Position</label>
                    <input type="text" id="editContactPersonPosition" name="contact_person_position">
                </div>
                <div class="mc-form-group">
                    <label for="editContactPersonPhone">Contact Person Phone</label>
                    <input type="text" id="editContactPersonPhone" name="contact_person_phone">
                </div>
                <div class="mc-form-group">
                    <label for="editContactPersonEmail">Contact Person Email</label>
                    <input type="email" id="editContactPersonEmail" name="contact_person_email">
                </div>
                <div class="mc-form-group">
                    <label for="editHeadPosition">Head Position</label>
                    <input type="text" id="editHeadPosition" name="head_position">
                </div>
                <div class="mc-form-group">
                    <label for="editHeadName">Head Name</label>
                    <input type="text" id="editHeadName" name="head_name">
                </div>
                <button type="submit" class="mc-btn-submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="mc-deleteModal" class="mc-modal">
        <div class="mc-modal-content">
            <span class="mc-close">&times;</span>
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete this courier?</p>
            <div class="mc-modal-buttons">
                <button id="mc-confirmDelete" class="mc-btn-submit">Yes, Delete</button>
                <button class="mc-close mc-btn-cancel">Cancel</button>
            </div>
        </div>
    </div>

    <script src="js/manage_couriers.js"></script>
    <script src="js/admin_dashboard.js"></script>

</body>
</html> 