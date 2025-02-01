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
    <title>Express Order</title>
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
                <h1>Express Order</h1>
                <p>Please fill out the form below to place a new express order.</p>
                <form id="new-order-form" class="order-form express">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sender_name">Sender's Name</label>
                            <input type="text" id="sender_name" name="sender_name" required>
                        </div>
                        <div class="form-group">
                            <label for="sender_phone">Sender's Phone</label>
                            <input type="tel" id="sender_phone" name="sender_phone" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pickup_address">Pickup Address</label>
                            <input type="text" id="pickup_address" name="pickup_address" required>
                        </div>
                        <div class="form-group">
                            <label for="pickup_time">Preferred Pickup Time</label>
                            <input type="time" id="pickup_time" name="pickup_time" required>
                        </div>
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
                            <label for="package_type">Package Type</label>
                            <select id="package_type" name="package_type" required>
                                <option value="document">Document</option>
                                <option value="parcel">Parcel</option>
                                <option value="fragile">Fragile</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="urgency_level">Urgency Level</label>
                            <select id="urgency_level" name="urgency_level" required>
                                <option value="same_day">Same Day</option>
                                <option value="next_day">Next Day</option>
                                <option value="two_day">2-Day Express</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="package_weight">Weight (kg)</label>
                            <input type="number" step="0.1" id="package_weight" name="package_weight" required>
                        </div>
                        <div class="form-group">
                            <label for="package_quantity">Quantity</label>
                            <input type="number" id="package_quantity" name="package_quantity" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="signature_required">Signature Required</label>
                            <select id="signature_required" name="signature_required" required>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tracking_updates">Tracking Updates Via</label>
                            <select id="tracking_updates" name="tracking_updates" required>
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                                <option value="both">Both</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="special_instructions">Special Instructions</label>
                        <textarea id="special_instructions" name="special_instructions"></textarea>
                    </div>
                    <button type="submit" class="form-submit-button">Submit Express Order</button>
                </form>
            </div>
        </main>
    </div>

<script src="./js/sideBar.js"> </script>

</body>
</html>