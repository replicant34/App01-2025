<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>

    <!-- Add critical CSS inline to prevent FOUC -->
    <style>
        body {
            opacity: 0;
            transition: opacity 0.3s ease;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #74ebd5, #9face6);
            background-attachment: fixed;
        }
        .page-container {
            visibility: hidden;
        }
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #74ebd5, #9face6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>

    <!-- Load CSS files before the content -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <!-- Add loading indicator -->
    <div class="loading">
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <?php
        session_start();
        if(!isset($_SESSION['user'])) header('location: index.php');
        $_SESSION['table'] = 'users';
        $user = $_SESSION['user'];
    ?>

    <div class="page-container">
        <!-- Navigation Button ☰ -->
        <?php include('partials/sidebar-navigation.php') ?>
        <!-- Logout Button -->
        <?php include('partials/logoutBtn.php') ?>
        <!-- Sidebar -->
        <?php include('partials/sidebar.php') ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="login-container">
                <form action="database/addUser.php" method="POST" id="loginForm" class="login-form">
                    <h2>New User</h2>
                    <div class="input-group">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="First name" required>
                    </div>
                    <div class="input-group">
                        <label for="last_name">Last name</label>
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
                        $response_message = $_SESSION['response']['message'];
                        $is_success = $_SESSION['response']['success'];
                ?>
                    <!-- Styled Response Message -->
                    <div class="responseMessage">
                        <p class="responseMessage <?= $is_success ? 'responseMessage_success' : 'responseMessage_error' ?>">
                            <?= htmlspecialchars($response_message) ?>
                        </p>
                    </div>
                <?php unset($_SESSION['response']); } ?>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="./js/jquery-3.7.1.js"></script>
    <script src="./js/sideBar.js"></script>

    <!-- Add this script at the end -->
    <script>
        // Hide loading and show content when everything is loaded
        window.addEventListener('load', function() {
            document.body.style.opacity = '1';
            document.querySelector('.page-container').style.visibility = 'visible';
            document.querySelector('.loading').style.display = 'none';
        });
    </script>
</body>
</html>