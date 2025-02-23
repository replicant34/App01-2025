document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('content').classList.toggle('collapsed');
    });

    // Dropdown toggles
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;
            submenu.classList.toggle('show');
            
            // Update arrow icon
            this.classList.toggle('active');
        });
    });
}); 