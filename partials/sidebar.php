<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
    <img src="Images/Logo.png" alt="Platform Logo" class="headerlogo" id="headerlogo">
        <h2 class="sidebartitle" id="sidebartitle">My Account</h2>
        <span class="username" id="username"><?= $user['first_name'] . ' ' . $user['last_name'] ?></span>
    </div>
       <nav class="sidebarnav" id="sidebarnav">
       <button class="sidebarbutton" id="sidebarbutton" onclick="redirectToDashboard()">
            <i class="fas fa-dashboard"></i> 
            <span class="buttontext" >Dashboard</span>
        </button>
        <button class="sidebarbutton" id="sidebarbutton" onclick="showSection('history')">
            <i class="fas fa-history"></i> 
            <span class="buttontext" >History of Orders</span>
        </button>
        <button class="sidebarbutton" id="new-order-btn" onclick="toggleSubButtons()">
            <i class="fas fa-plus"></i> 
            <span class="buttontext">New Order</span>
        </button>
        <div class="sub-buttons" id="order-sub-buttons" style="display: none;">
            <button class="sidebarbutton sub-button" onclick="redirectToDomesticOrder()">
                <i class="fas fa-home"></i> 
                <span class="buttontext">Domestic Order</span>
            </button>
            <button class="sidebarbutton sub-button" onclick="redirectToInternationalOrder()">
                <i class="fas fa-globe"></i> 
                <span class="buttontext">International Order</span>
            </button>
            <button class="sidebarbutton sub-button" onclick="redirectToExpressOrder()">
                <i class="fas fa-shipping-fast"></i> 
                <span class="buttontext">Express Order</span>
            </button>
        </div>
        <button class="sidebarbutton" id="sidebarbutton" onclick="showSection('documents')">
            <i class="fas fa-file-alt"></i> 
            <span class="buttontext" >Documents</span>
        </button>
        <button class="sidebarbutton" id="sidebarbutton" onclick="redirectToAddUser()">
            <i class="fas fa-user-plus"></i> 
            <span class="buttontext">Add User</span>
        </button>
        <button class="sidebarbutton" id="sidebarbutton" onclick="redirectToManageUsers()">
            <i class="fas fa-users"></i> 
            <span class="buttontext">Manage Users</span>
        </button>
    </nav>
</aside>
<script>
    // Redirect function for Add User button
    function redirectToAddUser() {
        window.location.href = 'user-add.php';
    }
</script>
<script>
    // Redirect function for Add User button
    function redirectToManageUsers() {
        window.location.href = 'user-manage.php';
    }
</script>
<script>
    // Redirect function for Add User button
    function redirectToDashboard() {
        window.location.href = 'dashboard.php';
    }
</script>
<script>
function toggleSubButtons() {
    const subButtons = document.getElementById('order-sub-buttons');
    subButtons.style.display = subButtons.style.display === 'none' ? 'block' : 'none';
}
</script>
<script>
    // Redirect function for Add User button
    function redirectToDomesticOrder() {
        window.location.href = 'order-domestic.php';
    }
</script>
<script>
    // Redirect function for Add User button
    function redirectToInternationalOrder() {
        window.location.href = 'order-international.php';
    }
</script>
<script>
    // Redirect function for Add User button
    function redirectToExpressOrder() {
        window.location.href = 'order-express.php';
    }
</script>
