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
    <title>International Order</title>
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
            <div id="new-order" class="content-section">
                <h1>International Order</h1>
                <p>Please fill out the form below to place a new international order.</p>
                <form id="new-order-form" class="order-form international">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sender_name">Sender's Name</label>
                            <input type="text" id="sender_name" name="sender_name" required>
                        </div>
                        <div class="form-group">
                            <label for="sender_company">Company Name</label>
                            <input type="text" id="sender_company" name="sender_company">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="recipient_name">Recipient's Name</label>
                            <input type="text" id="recipient_name" name="recipient_name" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient_company">Recipient's Company</label>
                            <input type="text" id="recipient_company" name="recipient_company">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="destination_country">Destination Country</label>
                            <input type="text" id="destination_country" name="destination_country" required>
                        </div>
                        <div class="form-group">
                            <label for="destination_city">Destination City</label>
                            <input type="text" id="destination_city" name="destination_city" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="delivery_address">Delivery Address</label>
                        <textarea id="delivery_address" name="delivery_address" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="package_weight">Package Weight (kg)</label>
                            <input type="number" step="0.1" id="package_weight" name="package_weight" required>
                        </div>
                        <div class="form-group">
                            <label for="package_dimensions">Dimensions (LxWxH cm)</label>
                            <input type="text" id="package_dimensions" name="package_dimensions" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="package_value">Declared Value</label>
                            <input type="number" id="package_value" name="package_value" required>
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <select id="currency" name="currency" required>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customs_number">Customs Declaration Number</label>
                            <input type="text" id="customs_number" name="customs_number">
                        </div>
                        <div class="form-group">
                            <label for="tracking_number">Tracking Number</label>
                            <input type="text" id="tracking_number" name="tracking_number">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping_method">Shipping Method</label>
                            <select id="shipping_method" name="shipping_method" required>
                                <option value="air">Air Freight</option>
                                <option value="sea">Sea Freight</option>
                                <option value="ground">Ground</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="insurance">Insurance Required</label>
                            <select id="insurance" name="insurance" required>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="special_instructions">Special Instructions</label>
                        <textarea id="special_instructions" name="special_instructions"></textarea>
                    </div>
                    <button type="submit" class="form-submit-button">Submit International Order</button>
                </form>
            </div>
        </main>
    </div>

    <script src="./js/sideBar.js"></script>
</body>
</html>