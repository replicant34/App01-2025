<?php
    session_start();
    if(!isset($_SESSION['user'])) header('location: index.php');
    $_SESSION['table'] = 'users';
    $user = $_SESSION['user']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account Page</title>
    <link rel="stylesheet" href="./css/dashboard.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="page-container">
        <!-- Navigation Button ☰ -->
        <?php include('partials/sidebar-navigation.php') ?>
        <!-- Logout Button -->
        <?php include('partials/logoutBtn.php') ?>
        <!-- Sidebar -->
        <?php include('partials/sidebar.php') ?>

        <!-- Main Content -->
        <main class="main-content">
            <div id="history" class="content-section active">
                <h1>History of Orders</h1>
                <p>Your previous orders will be displayed here.</p>
            </div>
            <div id="new-order" class="content-section">
                <h1>New Order</h1>
                <p>Please fill out the form below to place a new order.</p>
                <form id="new-order-form">
                    <!-- Input fields -->
                    <button type="submit" class="form-submit-button">Submit Order</button>
                </form>
            </div>
            <div id="documents" class="content-section">
                <h1>Documents</h1>
                <p>Your documents will be shown here.</p>
            </div>
            <div id="add-user" class="content-section">
                <div class="form-frame">
                    <h1 href="/user_add.php">Add User</h1>
                </div>
            </div>
        </main>
    </div>

<script src="./js/sideBar.js"> </script>

</body>
</html>