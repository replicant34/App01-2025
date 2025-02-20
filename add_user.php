<?php
session_start();
require_once 'config/db_connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this path is correct

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $role = $_POST['role'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    
    // Handle ID for all roles using Client_id column
    $entityId = null;
    if (!isset($_POST['skip_client']) && isset($_POST['client_id'])) {
        // Validate that the ID exists in the appropriate table
        try {
            $table = '';
            switch($role) {
                case 'client':
                    $table = 'Clients';
                    $column = 'Client_id';
                    break;
                case 'courier':
                    $table = 'Couriers';
                    $column = 'Courier_id';
                    break;
                case 'agent':
                    $table = 'Agents';
                    $column = 'Agent_id';
                    break;
            }
            
            if ($table && $_POST['client_id']) {
                $checkStmt = $pdo->prepare("SELECT 1 FROM $table WHERE $column = ?");
                $checkStmt->execute([$_POST['client_id']]);
                if ($checkStmt->fetchColumn()) {
                    $entityId = $_POST['client_id'];
                }
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert user into the database
            $stmt = $pdo->prepare("
                INSERT INTO Users (Full_name, Email, Phone, Position, Role, Login, Password, Client_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$fullName, $email, $phone, $position, $role, $login, $hashedPassword, $entityId]);

            // Log the action
            $stmt = $pdo->prepare("INSERT INTO action_logs (user_id, action_type, table_name, description, ip_address) VALUES (?, 'INSERT', 'users', 'Added new user: $login', ?)");
            $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);

            $success = 'User added successfully!';
            // Send email notification
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'kvazarlogistics@gmail.com'; // SMTP username
                $mail->Password = 'iijybydhuhzyatbn'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('kvazarlogistics@gmail.com', 'Kvazar');
                $mail->addAddress($email, $fullName);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Welcome to Kvazar';
                $mail->Body    = "Hello $fullName,<br><br>Your account has been created. Your login is: $login.<br>Your password is: $password<br>Best regards,<br>Kvazar Team";

                $mail->send();
                $success .= ' Email notification sent.';
            } catch (Exception $e) {
                $error .= " Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User - Kvazar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/add_user.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'properties/sidebar.php'; ?>
        <!-- Page Content -->
        <div id="content">
            <?php include 'properties/NavbarLogout.php'; ?>
            

            <div class="content-wrapper">
                <div class="form-container">
                    <div class="form-header">
                        <h1>Add New User</h1>
                    </div>

                    <?php if ($error): ?>
                        <div class="error-message">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="success-message">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" id="phone" name="phone">
                        </div>

                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" id="position" name="position">
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="client">Client</option>
                                <option value="courier">Courier</option>
                                <option value="agent">Agent</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="login">Login</label>
                            <input type="text" id="login" name="login" required>
                            <small class="form-text">Click 'Generate from Email' or enter your own login</small>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                            <small class="form-text">Click 'Generate Password' or enter your own password</small>
                        </div>

                        <div class="form-group">
                            <label for="client_id">Client ID (Optional)</label>
                            <select id="client_id" name="client_id">
                                <option value="">Select</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                            <div class="skip-button-container">
                                <button type="button" id="skip_button" class="skip-button">Skip Selection</button>
                                <input type="checkbox" id="skip_client" name="skip_client" style="display: none;">
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="js/admin_dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const clientIdSelect = document.getElementById('client_id');
            const skipClientCheckbox = document.getElementById('skip_client');
            const clientIdContainer = document.querySelector('.form-group:has(#client_id)');
            const emailInput = document.getElementById('email');
            const loginInput = document.getElementById('login');

            // Auto-fill login from email
            emailInput.addEventListener('input', function() {
                // Only update login if it's empty or matches the previous email
                const currentEmail = emailInput.value;
                const currentLogin = loginInput.value;
                
                // If login is empty or was previously set to an email
                if (!currentLogin || currentLogin.includes('@')) {
                    loginInput.value = currentEmail;
                }
            });

            // Update labels based on role
            function updateLabels(role) {
                const selectLabel = document.querySelector('label[for="client_id"]');
                const skipLabel = document.querySelector('label[for="skip_client"]');
                
                switch(role) {
                    case 'client':
                        selectLabel.textContent = 'Select Client';
                        skipLabel.textContent = 'Skip Client Selection';
                        break;
                    case 'courier':
                        selectLabel.textContent = 'Select Courier';
                        skipLabel.textContent = 'Skip Courier Selection';
                        break;
                    case 'agent':
                        selectLabel.textContent = 'Select Agent';
                        skipLabel.textContent = 'Skip Agent Selection';
                        break;
                }
            }

            roleSelect.addEventListener('change', function() {
                const role = roleSelect.value;
                clientIdSelect.innerHTML = '<option value="">Select</option>';

                if (role) {
                    // Show selection for all roles
                    clientIdContainer.style.display = 'block';
                    skipClientCheckbox.checked = false;
                    clientIdSelect.disabled = false;
                    
                    // Update labels
                    updateLabels(role);

                    console.log('Fetching data for role:', role);
                    
                    fetch(`assets/fetch_clients.php?role=${encodeURIComponent(role)}`)
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Received data:', data);
                            if (Array.isArray(data)) {
                                data.forEach(item => {
                                    const option = document.createElement('option');
                                    option.value = item.id;
                                    option.textContent = item.Short_Company_name;
                                    clientIdSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            alert('Error loading list. Please try again.');
                        });
                } else {
                    clientIdContainer.style.display = 'none';
                }
                skipButton.classList.remove('active');
                skipButton.textContent = 'Skip Selection';
            });

            const skipButton = document.getElementById('skip_button');
            const skipCheckbox = document.getElementById('skip_client');

            skipButton.addEventListener('click', function() {
                skipCheckbox.checked = !skipCheckbox.checked;
                skipButton.classList.toggle('active');
                clientIdSelect.disabled = skipCheckbox.checked;
                if (skipCheckbox.checked) {
                    clientIdSelect.value = '';
                    skipButton.textContent = 'Selection Skipped';
                } else {
                    skipButton.textContent = 'Skip Selection';
                }
            });

            const passwordInput = document.getElementById('password');
            const generatePasswordBtn = document.createElement('button');
            generatePasswordBtn.type = 'button'; // Prevent form submission
            generatePasswordBtn.className = 'btn-generate-password';
            generatePasswordBtn.textContent = 'Generate Password';

            // Insert generate button after password input
            passwordInput.parentNode.insertBefore(generatePasswordBtn, passwordInput.nextSibling);

            // Function to generate random password
            function generatePassword() {
                const length = 8;
                const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
                let password = '';
                
                // Ensure at least one uppercase, one lowercase, one number, and one special character
                password += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)];
                password += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)];
                password += '0123456789'[Math.floor(Math.random() * 10)];
                password += '!@#$%^&*'[Math.floor(Math.random() * 8)];

                // Fill the rest with random characters
                for (let i = password.length; i < length; i++) {
                    password += charset[Math.floor(Math.random() * charset.length)];
                }

                // Shuffle the password
                password = password.split('').sort(() => Math.random() - 0.5).join('');
                
                return password;
            }

            generatePasswordBtn.addEventListener('click', function() {
                const newPassword = generatePassword();
                passwordInput.value = newPassword;
                passwordInput.type = 'text'; // Show password temporarily
                setTimeout(() => {
                    passwordInput.type = 'password'; // Hide password after 3 seconds
                }, 5000);
            });

            // Create generate login button
            const generateLoginBtn = document.createElement('button');
            generateLoginBtn.type = 'button';
            generateLoginBtn.className = 'btn-generate-password'; // Using same style as password button
            generateLoginBtn.textContent = 'Generate from Email';

            // Insert generate login button after login input
            loginInput.parentNode.insertBefore(generateLoginBtn, loginInput.nextSibling);

            // Function to generate login from email
            function generateLoginFromEmail() {
                const email = emailInput.value;
                if (email) {
                    loginInput.value = email;
                } else {
                    alert('Please enter an email address first');
                }
            }

            // Add click event for generate login button
            generateLoginBtn.addEventListener('click', function() {
                generateLoginFromEmail();
            });

            // Remove the automatic login generation on email input
            emailInput.removeEventListener('input', function() {});

            // Add this to your existing JavaScript, after creating the buttons
            function addPulseEffect(button) {
                button.addEventListener('click', function() {
                    button.classList.add('clicked');
                    setTimeout(() => {
                        button.classList.remove('clicked');
                    }, 300);
                });
            }

            // Apply the effect to both buttons
            addPulseEffect(generatePasswordBtn);
            addPulseEffect(generateLoginBtn);
        });
    </script>
</body>
</html> 