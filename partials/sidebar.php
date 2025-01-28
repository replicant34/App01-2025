<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
    <img src="Images/Logo.png" alt="Platform Logo" class="headerlogo" id="headerlogo">
        <h2 class="sidebartitle" id="sidebartitle">My Account</h2>
        <span class="username" id="username"><?= $user['first_name'] . ' ' . $user['last_name'] ?></span>
    </div>
       <nav class="sidebarnav" id="sidebarnav">
       <button class="sidebarbutton" id="sidebarbutton" onclick="showSection('history')">
            <i class="fas fa-dashboard"></i> 
            <span class="buttontext" >Dashboard</span>
        </button>
        <button class="sidebarbutton" id="sidebarbutton" onclick="showSection('history')">
            <i class="fas fa-history"></i> 
            <span class="buttontext" >History of Orders</span>
        </button>
        <button class="sidebarbutton" id="sidebarbutton" onclick="showSection('new-order')">
            <i class="fas fa-plus"></i> 
            <span class="buttontext" >New Order</span>
        </button>
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
