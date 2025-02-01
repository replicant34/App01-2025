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
            <div class="content-section">
                <h1>Add New User</h1>
                <p>Please fill out the form below to add a new user.</p>
                <form class="order-form" id="addUserForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="userType">User Type</label>
                            <select id="userType" name="userType" required>
                                <option value="user">Regular User</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="form-submit-button">Add User</button>
                </form>
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

        // Form submission handling
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Password validation
            if(document.getElementById('password').value !== document.getElementById('confirmPassword').value) {
                alert('Passwords do not match!');
                return;
            }

            // Create form data
            const formData = {
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                userType: document.getElementById('userType').value
            };

            // Send to server
            fetch('database/add-user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if(data.success) {
                    window.location.href = 'user-manage.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the user.');
            });
        });
    </script>
</body>
</html> 