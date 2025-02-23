document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('mc-editModal');
    const deleteModal = document.getElementById('mc-deleteModal');
    const editForm = document.getElementById('mc-editForm');
    const closeButtons = document.querySelectorAll('.mc-close');
    const downloadCsvButton = document.getElementById('mc-download-csv');

    // Fetch user data function
    function fetchUserData(userId) {
        fetch(`assets/get_user.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                // Populate form fields
                document.getElementById('editUserId').value = data.User_id;
                document.getElementById('editFullName').value = data.Full_name;
                document.getElementById('editClientId').value = data.Client_id;
                document.getElementById('editPosition').value = data.Position;
                document.getElementById('editPhone').value = data.Phone;
                document.getElementById('editEmail').value = data.Email;
                document.getElementById('editLogin').value = data.Login;
                document.getElementById('editRole').value = data.Role;
            })
            .catch(error => console.error('Error:', error));
    }

    // Handle dropdown menu
    document.querySelectorAll('.dropbtn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            this.nextElementSibling.classList.toggle('show');
        });
    });

    // Handle edit action
    document.querySelectorAll('.edit').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const userId = this.getAttribute('data-id');
            fetchUserData(userId);
            editModal.style.display = 'block';
        });
    });

    // Handle delete action
    document.querySelectorAll('.delete').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const userId = this.getAttribute('data-id');
            deleteModal.style.display = 'block';
            document.getElementById('mc-confirmDelete').setAttribute('data-id', userId);
        });
    });

    // Handle edit form submission
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('assets/edit_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            alert(data.success);
            editModal.style.display = 'none';
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle delete confirmation
    document.getElementById('mc-confirmDelete').addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        fetch('assets/delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ user_id: userId })
        })
        .then(() => {
            deleteModal.style.display = 'none';
            location.reload();
        });
    });

    // Close modals with X button
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === editModal || e.target === deleteModal) {
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
        }
    });

    // Table scrolling controls
    document.getElementById('mc-scroll-left').addEventListener('click', function() {
        document.querySelector('.mc-table-container').scrollBy({
            left: -200,
            behavior: 'smooth'
        });
    });

    document.getElementById('mc-scroll-right').addEventListener('click', function() {
        document.querySelector('.mc-table-container').scrollBy({
            left: 200,
            behavior: 'smooth'
        });
    });

    // CSV download
    downloadCsvButton.addEventListener('click', function() {
        window.location.href = 'assets/download_users_csv.php';
    });
});