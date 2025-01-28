// Toggle Sidebar Collapse
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
}

// Show Content Section
function showSection(sectionId) {
    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.sidebar-button');
    buttons.forEach(button => button.classList.remove('active'));

    // Add active class to clicked button
    const activeButton = [...buttons].find(button =>
        button.textContent.trim().toLowerCase().includes(sectionId)
    );
    if (activeButton) {
        activeButton.classList.add('active');
    }

    // Hide all content sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.classList.remove('active'));

    // Show the selected content section
    const activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.classList.add('active');
    }
}
