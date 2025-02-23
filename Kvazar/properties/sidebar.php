<nav id="sidebar" class="active">
    <div class="sidebar-header">
        <div class="user-info">
            <img src="images/avatar-default.png" alt="User Avatar" class="avatar">
            <h3><?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
            <span>Administrator</span>
        </div>
    </div>

    <ul class="list-unstyled components">
        <li>
            <a href="admin_dashboard.php">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li>
            <a href="#ordersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-box"></i>
                <span>Orders</span>
            </a>
            <ul class="collapse list-unstyled" id="ordersSubmenu">
                <li>
                    <a href="new_order.php">
                        <i class="fas fa-plus"></i>
                        <span>New Order</span>
                    </a>
                </li>
                <li>
                    <a href="manage_orders.php">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Orders</span>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#usersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <ul class="collapse list-unstyled" id="usersSubmenu">
                <li>
                    <a href="add_user.php"><i class="fas fa-user-plus"></i> Add User</a>
                </li>
                <li>
                    <a href="manage_users.php"><i class="fas fa-user-cog"></i> Manage Users</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#clientsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-users"></i>
                <span>Clients</span>
            </a>
            <ul class="collapse list-unstyled" id="clientsSubmenu">
                <li>
                    <a href="add_client.php"><i class="fas fa-plus-circle"></i> New Client</a>
                </li>
                <li>
                    <a href="manage_clients.php"><i class="fas fa-tasks"></i> Manage Clients</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#partnersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-handshake"></i>
                <span>Partners</span>
            </a>
            <ul class="collapse list-unstyled" id="partnersSubmenu">
                <li>
                    <a href="manage_partners.php">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Partners</span>
                    </a>
                </li>
                <li>
                    <a href="add_partner.php">
                        <i class="fas fa-plus-circle"></i>
                        <span>New Partner</span>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#couriersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-truck"></i>
                <span>Couriers</span>
            </a>
            <ul class="collapse list-unstyled" id="couriersSubmenu">
                <li>
                    <a href="add_courier.php"><i class="fas fa-plus-circle"></i> New Courier</a>
                </li>
                <li>
                    <a href="manage_couriers.php"><i class="fas fa-tasks"></i> Manage Couriers</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#agentsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-user-tie"></i>
                <span>Other Agents</span>
            </a>
            <ul class="collapse list-unstyled" id="agentsSubmenu">
                <li>
                    <a href="add_agent.php"><i class="fas fa-plus-circle"></i> New Agent</a>
                </li>
                <li>
                    <a href="manage_agents.php"><i class="fas fa-tasks"></i> Manage Agents</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#contractsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-file-contract"></i>
                <span>Contracts</span>
            </a>
            <ul class="collapse list-unstyled" id="contractsSubmenu">
                <li>
                    <a href="new_contract.php">
                        <i class="fas fa-plus"></i>
                        <span>New Contract</span>
                    </a>
                </li>
                <li>
                    <a href="manage_contracts.php">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Contracts</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
