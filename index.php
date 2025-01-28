<?php
    session_start();
    if(isset($_SESSION['user'])) header('location: dashboard.php');

    $error_massege = '';

    if($_POST){

        include('database/connection.php');
        
        $username = $_POST['email'];
        $password = $_POST['password'];
        
        $query = 'SELECT * FROM users WHERE users.email = "'. $username .'" AND users.password= "'. $password .'"';
        $stmt = $conn->prepare($query);
        $stmt ->execute();

        if($stmt->rowCount() > 0){
            $stmt ->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt ->fetchAll()[0];
            $_SESSION['user'] = $user;

            header('Location: dashboard.php');

        } else $error_massege = "Please make sure login and password are correct";

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>

    <!-- Logo and Company Name at the Top -->
    <div class="header-container">
        <img src="Images/Logo.png" alt="Platform Logo" class="header-logo">
        <h1 class="header-title">OOO «Ромашка»</h1>
    </div>
    <!-- Login Form -->
    <div class="login-container">
        <form action="index.php" method="POST" id="loginForm" class="login-form">
            <h2>Login</h2>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
        <?php if(!empty($error_massege)) { ?>
            <div>
                <p> Error: <?= $error_massege ?></p>
            </div>
        <?php } ?>
</body>
</html>
