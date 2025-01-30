document.addEventListener("DOMContentLoaded", function () {
    const rowsPerPage = 15; // Records per page
    const tableBody = document.getElementById("table-body");
    const paginationControls = document.getElementById("pagination-controls");
    const rows = Array.from(tableBody.getElementsByTagName("tr")); // All table rows
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    let currentPage = 1;

    // Function to display rows for the current page
    function displayRows(page) {
        // Hide all rows
        rows.forEach((row) => (row.style.display = "none"));

        // Calculate the start and end index for rows to display
        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = Math.min(startIndex + rowsPerPage, totalRows);

        // Show only rows for the current page
        for (let i = startIndex; i < endIndex; i++) {
            rows[i].style.display = "";
        }
    }

    // Function to generate pagination controls
    function generatePaginationControls() {
        paginationControls.innerHTML = ""; // Clear existing controls

        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement("button");
            button.textContent = i;
            button.className = "pagination-button";
            if (i === currentPage) button.classList.add("active");

            // Add click event to navigate to the selected page
            button.addEventListener("click", () => {
                currentPage = i;
                displayRows(currentPage);
                updateActiveButton();
            });

            paginationControls.appendChild(button);
        }
    }

    // Function to update the active button's styling
    function updateActiveButton() {
        const buttons = Array.from(
            paginationControls.getElementsByClassName("pagination-button")
        );
        buttons.forEach((button, index) => {
            button.classList.toggle("active", index + 1 === currentPage);
        });
    }

    // Initialize the table with the first page
    displayRows(currentPage);
    generatePaginationControls();
});
