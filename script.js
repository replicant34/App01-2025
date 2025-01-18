// Get references to form elements
const loginForm = document.getElementById("loginForm");
const errorMessage = document.getElementById("errorMessage");

// Handle form submission
loginForm.addEventListener("submit", async (e) => {
    e.preventDefault(); // Prevent default form submission

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    // Example: Simple validation
    if (username === "" || password === "") {
        showError("Username and password cannot be empty.");
        return;
    }

    // Mock login API request (Replace with your actual backend API)
    try {
        const response = await fetch("http://localhost:3000/login", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username, password }),
        });

        if (response.ok) {
            const data = await response.json();
            console.log("Login Successful:", data);
            // Redirect to dashboard or next page
            window.location.href = "/dashboard.html";
        } else {
            const errorData = await response.json();
            showError(errorData.message || "Invalid username or password.");
        }
    } catch (error) {
        console.error("Login Error:", error);
        showError("An error occurred. Please try again later.");
    }
});

// Show error message
function showError(message) {
    errorMessage.textContent = message;
    errorMessage.style.display = "block";
}

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


// Handle Form Submission
document.getElementById('new-order-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission behavior

    // Collect form data
    const formData = new FormData(event.target);
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    // Log the form data (for demonstration purposes)
    console.log('Form Submitted:', data);

    // Reset the form after submission
    event.target.reset();

    // Show a success message (optional)
    alert('Order successfully submitted!');
});