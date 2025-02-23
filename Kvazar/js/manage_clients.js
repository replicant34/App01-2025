document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.mc-btn-edit');
    const deleteButtons = document.querySelectorAll('.mc-btn-delete');
    const editModal = document.getElementById('mc-editModal');
    const deleteModal = document.getElementById('mc-deleteModal');
    const closeButtons = document.querySelectorAll('.mc-close');
    const downloadCsvButton = document.getElementById('mc-download-csv');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const clientId = this.getAttribute('data-id');
            // Fetch client data and populate the form
            fetch(`assets/get_client.php?id=${clientId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editClientId').value = data.Client_id;
                    document.getElementById('editCompanyType').value = data.Company_type;
                    document.getElementById('editFullName').value = data.Full_Company_name;
                    document.getElementById('editShortName').value = data.Short_Company_name;
                    document.getElementById('editINN').value = data.INN;
                    document.getElementById('editKPP').value = data.KPP;
                    document.getElementById('editOGRN').value = data.OGRN;
                    document.getElementById('editPhysicalAddress').value = data.Physical_address;
                    document.getElementById('editLegalAddress').value = data.Legal_address;
                    document.getElementById('editBankName').value = data.Bank_name;
                    document.getElementById('editBIK').value = data.BIK;
                    document.getElementById('editSettlementAccount').value = data.Settlement_account;
                    document.getElementById('editCorrespondentAccount').value = data.Correspondent_account;
                    document.getElementById('editContactPerson').value = data.Contact_person;
                    document.getElementById('editContactPersonPosition').value = data.Contact_person_position;
                    document.getElementById('editContactPersonPhone').value = data.Contact_person_phone;
                    document.getElementById('editContactPersonEmail').value = data.Contact_person_email;
                    document.getElementById('editHeadPosition').value = data.Head_position;
                    document.getElementById('editHeadName').value = data.Head_name;
                    editModal.style.display = 'block';
                });
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const clientId = this.getAttribute('data-id');
            document.getElementById('mc-confirmDelete').setAttribute('data-id', clientId);
            deleteModal.style.display = 'block';
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
        });
    });

    document.getElementById('mc-confirmDelete').addEventListener('click', function() {
        const clientId = this.getAttribute('data-id');
        // Send delete request
        fetch(`assets/delete_client.php?id=${clientId}`, { method: 'DELETE' })
            .then(() => {
                location.reload();
            });
    });

    downloadCsvButton.addEventListener('click', function() {
        window.location.href = 'assets/download_clients_csv.php';
    });
}); 

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

// JavaScript to handle dropdown menu
document.querySelectorAll('.dropbtn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.stopPropagation();
        this.nextElementSibling.classList.toggle('show');
    });
});

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        document.querySelectorAll('.dropdown-content').forEach(dropdown => {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    }
};

// Handle edit and delete actions
document.querySelectorAll('.edit').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        const clientId = this.getAttribute('data-id');
        // Fetch client data and populate the form
        fetch(`assets/get_client.php?id=${clientId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editClientId').value = data.Client_id;
                document.getElementById('editCompanyType').value = data.Company_type;
                document.getElementById('editFullName').value = data.Full_Company_name;
                document.getElementById('editShortName').value = data.Short_Company_name;
                document.getElementById('editINN').value = data.INN;
                document.getElementById('editKPP').value = data.KPP;
                document.getElementById('editOGRN').value = data.OGRN;
                document.getElementById('editPhysicalAddress').value = data.Physical_address;
                document.getElementById('editLegalAddress').value = data.Legal_address;
                document.getElementById('editBankName').value = data.Bank_name;
                document.getElementById('editBIK').value = data.BIK;
                document.getElementById('editSettlementAccount').value = data.Settlement_account;
                document.getElementById('editCorrespondentAccount').value = data.Correspondent_account;
                document.getElementById('editContactPerson').value = data.Contact_person;
                document.getElementById('editContactPersonPosition').value = data.Contact_person_position;
                document.getElementById('editContactPersonPhone').value = data.Contact_person_phone;
                document.getElementById('editContactPersonEmail').value = data.Contact_person_email;
                document.getElementById('editHeadPosition').value = data.Head_position;
                document.getElementById('editHeadName').value = data.Head_name;
                // Populate other fields as needed
                document.getElementById('mc-editModal').style.display = 'block';
            });
    });
});

document.querySelectorAll('.delete').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        const clientId = this.getAttribute('data-id');
        // Open delete confirmation modal
        document.getElementById('mc-deleteModal').style.display = 'block';
        document.getElementById('mc-confirmDelete').onclick = function() {
            // Perform delete action
            fetch('assets/delete_client.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ client_id: clientId })
            }).then(() => {
                // Close modal and refresh page
                document.getElementById('mc-deleteModal').style.display = 'none';
                location.reload();
            });
        };
    });
});

// Submit edit form
document.getElementById('mc-editForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    fetch('assets/edit_client.php', {
        method: 'POST',
        body: formData
    }).then(() => {
        // Close modal and refresh page
        document.getElementById('mc-editModal').style.display = 'none';
        location.reload();
    });
});

// Close modals
document.querySelectorAll('.mc-close, .close').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('mc-editModal').style.display = 'none';
        document.getElementById('mc-deleteModal').style.display = 'none';
    });
});