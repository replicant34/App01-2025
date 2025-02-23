<?php
session_start();
require_once 'config/db_connect.php';
require_once 'includes/form_helpers.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$userRole = $_SESSION['role'];
$userId = $_SESSION['user_id'];

// Get available clients based on user role
function getAvailableClients($pdo, $userRole, $userId) {
    switch ($userRole) {
        case 'admin':
        case 'ceo':
            $stmt = $pdo->query("SELECT Client_id, Full_company_name FROM Clients ORDER BY Full_company_name");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        case 'operator':
            // Get clients assigned to operator
            $stmt = $pdo->prepare("
                SELECT DISTINCT c.Client_id, c.Full_company_name 
                FROM Clients c
                JOIN Operator_clients oc ON c.Client_id = oc.Client_id
                WHERE oc.User_id = ?
                ORDER BY c.Full_company_name
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        case 'client':
            // Get only client's own company
            $stmt = $pdo->prepare("
                SELECT c.Client_id, c.Full_company_name 
                FROM Clients c
                JOIN Users u ON c.Client_id = u.Client_id
                WHERE u.User_id = ?
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        default:
            return [];
    }
}

$availableClients = getAvailableClients($pdo, $userRole, $userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/order_form.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'properties/sidebar.php'; ?>
        
        <div id="content">
            <?php include 'properties/NavbarLogout.php'; ?>
            
            <div class="form-container">
                <div class="form-header">
                    <h1>New Order</h1>
                </div>

                <!-- Tabs -->
                <div class="form-tabs">
                    <button class="tab-button active" data-tab="order-info">Order Information</button>
                    <button class="tab-button" data-tab="tab2">Tab 2</button>
                    <button class="tab-button" data-tab="tab3">Tab 3</button>
                    <button class="tab-button" data-tab="tab4">Tab 4</button>
                </div>

                <form id="orderForm" method="POST">
                    <!-- Order Information Tab -->
                    <div class="tab-content active" id="order-info">
                        <div class="form-columns">
                            <div class="form-column">
                                <div class="form-group">
                                    <label for="clientSearch">Client</label>
                                    <?php if (canEditField('client', $userRole)): ?>
                                        <div class="search-select-container">
                                            <div class="search-input-wrapper">
                                                <input type="text" 
                                                       id="clientSearch" 
                                                       class="search-input" 
                                                       placeholder="Search client..."
                                                       autocomplete="off">
                                                <select id="client" 
                                                        name="client_id" 
                                                        class="form-select" 
                                                        required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($availableClients as $client): ?>
                                                        <option value="<?php echo htmlspecialchars($client['Client_id']); ?>">
                                                            <?php echo htmlspecialchars($client['Full_company_name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="readonly-field">
                                            <?php 
                                            if (!empty($availableClients)) {
                                                echo htmlspecialchars($availableClients[0]['Full_company_name']);
                                            }
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="contract">Contract Number</label>
                                    <div class="contract-container">
                                        <input type="text" 
                                               id="contract" 
                                               name="contract_number" 
                                               class="form-input" 
                                               readonly 
                                               required>
                                        <div class="contract-message"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="contract_date">Contract Date</label>
                                    <div class="contract-date-container">
                                        <input type="text" 
                                               id="contract_date" 
                                               name="contract_date" 
                                               class="form-input" 
                                               readonly 
                                               required>
                                        <div class="contract-date-message"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="contractor">Contractor</label>
                                    <div class="contractor-container">
                                        <input type="text" 
                                               id="contractor" 
                                               name="contractor_name" 
                                               class="form-input" 
                                               readonly>
                                        <input type="hidden" 
                                               id="contractor_id" 
                                               name="contractor_id">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="order_number">Order Number</label>
                                    <div class="order-number-container">
                                        <input type="text" 
                                               id="order_number" 
                                               name="order_number" 
                                               class="form-input" 
                                               value="<?php echo generateOrderNumber(); ?>"
                                               <?php if (!in_array($userRole, ['admin', 'ceo'])): ?>readonly<?php endif; ?>
                                               required>
                                        <div class="order-number-message"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Other columns will be added here -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/order_form.js"></script>
    <script src="js/admin_dashboard.js"></script>
</body>
</html> 