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

// Fetch clients from the database
$stmt = $pdo->prepare("SELECT * FROM Users LIMIT :limit OFFSET :offset");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total number of clients for pagination
$totalStmt = $pdo->query("SELECT COUNT(*) FROM Users");
$totalUsers = $totalStmt->fetchColumn();
$totalPages = ceil($totalUsers / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Agents</title>
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
                    <h1>Manage Users</h1>
                </div>
                <div class="mc-slider-controls">
                    <button id="mc-scroll-left" class="mc-slider-btn"><i class="fas fa-chevron-left"></i></button>
                    <button id="mc-scroll-right" class="mc-slider-btn"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="mc-table-container">
                    <table class="mc-clients-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Client ID</th>
                                <th>Position</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Login</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['User_id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Client_id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Position']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Phone']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Login']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Role']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Created_at']); ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="dropbtn"><i class="fas fa-ellipsis-v"></i></button>
                                            <div class="dropdown-content">
                                                <a href="#" class="edit" data-id="<?php echo $user['User_id']; ?>">Edit</a>
                                                <a href="#" class="delete" data-id="<?php echo $user['User_id']; ?>">Delete</a>
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

    <!-- Edit Modal editUserId, user_id, editFullName, full_name -->
    <div id="mc-editModal" class="mc-modal">
        <div class="mc-modal-content">
            <span class="mc-close">&times;</span>
            <h2>Edit Agent</h2>
            <form id="mc-editForm">
                <input type="hidden" id="editUserId" name="user_id">
                <div class="mc-form-group">
                    <label for="editFullName">Full Name</label>
                    <input type="text" id="editFullName" name="full_name">
                </div>
                <div class="mc-form-group">
                    <label for="editClientId">Client ID</label>
                    <input type="text" id="editClientId" name="client_id">
                </div>
                <div class="mc-form-group">
                    <label for="editPosition">Position</label>
                    <input type="text" id="editPosition" name="position">
                </div>
                <div class="mc-form-group">
                    <label for="editPhone">Phone</label>
                    <input type="text" id="editPhone" name="phone">
                </div>
                <div class="mc-form-group">
                    <label for="editEmail">Email</label>
                    <input type="text" id="editEmail" name="email">
                </div>
                <div class="mc-form-group">
                    <label for="editLogin">Login</label>
                    <input type="text" id="editLogin" name="login">
                </div>
                <div class="mc-form-group">
                    <label for="editRole">Role</label>
                    <input type="text" id="editRole" name="role">
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
            <p>Are you sure you want to delete this user?</p>
            <div class="mc-modal-buttons">
                <button id="mc-confirmDelete" class="mc-btn-submit">Yes, Delete</button>
                <button class="mc-close mc-btn-cancel">Cancel</button>
            </div>
        </div>
    </div>

    <script src="js/manage_users.js"></script>
    <script src="js/admin_dashboard.js"></script>

</body>
</html> 