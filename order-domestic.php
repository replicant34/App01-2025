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
    <title>Domestic Order</title>
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
                <h1>Domestic Order</h1>
                <p>Please fill out the form below to place a new domestic order.</p>
                <form id="new-order-form" class="order-form domestic">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sender_name">Sender's Name</label>
                            <input type="text" id="sender_name" name="sender_name" required>
                        </div>
                        <div class="form-group">
                            <label for="sender_email">Sender's Email</label>
                            <input type="email" id="sender_email" name="sender_email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sender_phone">Sender's Phone</label>
                            <input type="tel" id="sender_phone" name="sender_phone" required>
                        </div>
                        <div class="form-group">
                            <label for="sender_postal">Sender's Postal Code</label>
                            <input type="text" id="sender_postal" name="sender_postal" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="pickup_address">Pickup Address</label>
                        <textarea id="pickup_address" name="pickup_address" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="recipient_name">Recipient's Name</label>
                            <input type="text" id="recipient_name" name="recipient_name" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient_phone">Recipient's Phone</label>
                            <input type="tel" id="recipient_phone" name="recipient_phone" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="delivery_address">Delivery Address</label>
                        <textarea id="delivery_address" name="delivery_address" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="service_type">Service Type</label>
                            <select id="service_type" name="service_type" required>
                                <option value="standard">Standard Delivery</option>
                                <option value="express">Express Delivery</option>
                                <option value="same_day">Same Day Delivery</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="card">Credit Card</option>
                                <option value="cash">Cash on Delivery</option>
                                <option value="transfer">Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="package_type">Package Type</label>
                            <select id="package_type" name="package_type" required>
                                <option value="parcel">Parcel</option>
                                <option value="document">Document</option>
                                <option value="fragile">Fragile</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estimated_value">Estimated Value</label>
                            <input type="number" id="estimated_value" name="estimated_value" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="special_instructions">Special Instructions</label>
                        <textarea id="special_instructions" name="special_instructions"></textarea>
                    </div>
                    <button type="submit" class="form-submit-button">Submit Domestic Order</button>
                </form>
            </div>
        </main>
    </div>

<script src="./js/sideBar.js"> </script>

</body>
</html>