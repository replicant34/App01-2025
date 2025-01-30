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
    </div>
    <div class='row'>
        <div class='column'>
        <!-- Main Content -->
           <div class="login-container">
                <form action="database/addUser.php" method="POST" id="loginForm" class="login-form">
                    <h2>New User</h2>
                    <div class="input-group">
                        <label for="email">First name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="First name" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Last name</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Last name" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="login-button"> <i class="fa fa-plus"></i> Submit</button>
                </form>
                <?php 
                    if (isset($_SESSION['response'])) {
                        $response_massage = $_SESSION['response']['message'];
                        $is_success = $_SESSION['response']['success'];
                ?>  
                    <!-- Styled Response Message -->
                    <div class="responseMessage">
                        <p class="responseMessage <?= $is_success ? 'responseMessage_success' : 'responseMessage_error' ?>">
                            <?= htmlspecialchars($response_massage) ?>
                        </p>
                    </div>
                <?php unset($_SESSION['response']); } ?>
        </div>
    </div>
<script src="./js/sideBar.js"> </script>
</body>
</html>