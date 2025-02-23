document.addEventListener('DOMContentLoaded', function() {
    // Constants
    const ITEMS_PER_PAGE = 10;
    let currentPage = 1;
    let currentTab = 'client';
    let currentSort = { field: 'created_at', direction: 'desc' };
    let visibleColumns = new Set([
        'status', 'company_type', 'full_name', 'short_name', 'contracts',
        'inn', 'kpp', 'ogrn', 'physical_address', 'legal_address',
        'bank_name', 'bik', 'settlement_account', 'correspondent_account',
        'contact_person', 'contact_position', 'contact_phone', 'contact_email',
        'head_position', 'head_name'
    ]); // Default visible columns
    let idColumn = 'Client_id'; // Default for client tab

    // Column definitions
    const columnDefs = {
        common: [
            { id: 'status', label: 'Status', sortable: true },
            { id: 'company_type', label: 'Company Type', sortable: true, field: 'Company_type' },
            { id: 'full_name', label: 'Full Name', sortable: true, field: 'Full_Company_name' },
            { id: 'short_name', label: 'Short Name', sortable: true, field: 'Short_Company_name' },
            { id: 'contracts', label: 'Contracts', sortable: false },
            { id: 'inn', label: 'INN', sortable: true, field: 'INN' },
            { id: 'kpp', label: 'KPP', sortable: true, field: 'KPP' },
            { id: 'ogrn', label: 'OGRN', sortable: true, field: 'OGRN' },
            { id: 'physical_address', label: 'Physical Address', sortable: true, field: 'Physical_address' },
            { id: 'legal_address', label: 'Legal Address', sortable: true, field: 'Legal_address' },
            { id: 'bank_name', label: 'Bank Name', sortable: true, field: 'Bank_name' },
            { id: 'bik', label: 'BIK', sortable: true, field: 'BIK' },
            { id: 'settlement_account', label: 'Settlement Account', sortable: true, field: 'Settlement_account' },
            { id: 'correspondent_account', label: 'Correspondent Account', sortable: true, field: 'Correspondent_account' },
            { id: 'contact_person', label: 'Contact Person', sortable: true, field: 'Contact_person' },
            { id: 'contact_position', label: 'Contact Position', sortable: true, field: 'Contact_person_position' },
            { id: 'contact_phone', label: 'Contact Phone', sortable: true, field: 'Contact_person_phone' },
            { id: 'contact_email', label: 'Contact Email', sortable: true, field: 'Contact_person_email' },
            { id: 'head_position', label: 'Head Position', sortable: true, field: 'Head_position' },
            { id: 'head_name', label: 'Head Name', sortable: true, field: 'Head_name' },
            { id: 'created_at', label: 'Created At', sortable: true },
            { id: 'updated_at', label: 'Updated At', sortable: true }
        ],
        courier: [
            { id: 'drivers', label: 'Drivers', sortable: false },
            { id: 'vehicles', label: 'Vehicles', sortable: false }
        ],
        client: [
            { id: 'orders', label: 'Orders', sortable: false }
        ]
    };

    // DOM Elements
    const tabButtons = document.querySelectorAll('.tab-btn');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const bankFilter = document.getElementById('bankFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const columnSettingsBtn = document.getElementById('columnSettingsBtn');
    const columnSettingsModal = document.getElementById('columnSettingsModal');
    const columnCheckboxes = document.getElementById('columnCheckboxes');
    const downloadButton = document.getElementById('downloadPartners');
    const passwordModal = document.getElementById('passwordModal');
    const actionPassword = document.getElementById('actionPassword');
    const statusModal = document.getElementById('statusModal');
    const cancelStatusChange = document.getElementById('cancelStatusChange');

    // Initialize column settings
    function initializeColumnSettings() {
        columnCheckboxes.innerHTML = '';
        const columns = [...columnDefs.common];
        
        if (currentTab === 'courier') {
            columns.push(...columnDefs.courier);
        } else if (currentTab === 'client') {
            columns.push(...columnDefs.client);
        }

        columns.forEach(col => {
            const div = document.createElement('div');
            div.className = 'checkbox-item';
            div.innerHTML = `
                <input type="checkbox" id="col_${col.id}" 
                       ${visibleColumns.has(col.id) ? 'checked' : ''}>
                <label for="col_${col.id}">${col.label}</label>
            `;
            columnCheckboxes.appendChild(div);
        });
    }

    // Load partners data
    function loadPartners() {
        const filters = {
            tab: currentTab,
            page: currentPage,
            perPage: ITEMS_PER_PAGE,
            search: searchInput.value,
            status: statusFilter.value,
            bank: bankFilter.value,
            dateFrom: dateFrom.value,
            dateTo: dateTo.value,
            sort: JSON.stringify(currentSort)
        };

        console.log('Fetching partners with filters:', filters);
        
        fetch('assets/fetch_partners.php?' + new URLSearchParams(filters))
            .then(response => response.json())
            .then(data => {
                console.log('Received data:', data);
                if (data.success) {
                    renderTable(data.partners);
                    renderPagination(data.totalPages);
                } else {
                    console.error('Error:', data.error);
                    if (data.debug) {
                        console.log('Debug info:', data.debug);
                    }
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    // Render table with data
    function renderTable(partners) {
        console.log('Partners data:', partners);
        const tbody = document.querySelector('#partnersTable tbody');
        tbody.innerHTML = '';

        partners.forEach(partner => {
            const tr = document.createElement('tr');
            tr.innerHTML = generateTableRow(partner);
            tbody.appendChild(tr);
        });
    }

    // Generate table row HTML
    function generateTableRow(partner) {
        let html = '';
        
        columnDefs.common.forEach(col => {
            if (visibleColumns.has(col.id)) {
                if (col.id === 'status') {
                    html += `<td>
                        <button class="status-btn" data-id="${partner[idColumn]}" data-current-status="${partner.Status}">
                            <span class="status-dot" style="background-color: ${partner.status_color}"></span>
                            ${partner.status_name}
                        </button>
                    </td>`;
                } else if (col.id === 'contracts') {
                    html += generateContractsCell(partner.contracts);
                } else {
                    const value = col.field ? partner[col.field] : partner[col.id];
                    html += `<td title="${value || '-'}">${value || '-'}</td>`;
                }
            }
        });

        // Add tab-specific columns
        if (currentTab === 'courier' && partner.drivers) {
            if (visibleColumns.has('drivers')) {
                html += generateDriversCell(partner.drivers);
            }
            if (visibleColumns.has('vehicles')) {
                html += generateVehiclesCell(partner.vehicles);
            }
        } else if (currentTab === 'client' && partner.orders) {
            if (visibleColumns.has('orders')) {
                html += generateOrdersCell(partner.orders);
            }
        }

        // Add actions column at the end
        html += `
            <td class="actions-column">
                <button class="edit-btn" data-id="${partner[idColumn]}">Edit</button>
            </td>
        `;

        return html;
    }

    // Generate cells for special columns
    function generateContractsCell(contracts) {
        if (!contracts || !contracts.length) return '<td>-</td>';
        
        const contractsList = contracts.map(c => 
            `<div>#${c.number} | ${c.date} | ${c.type} 
             <span class="status-dot" style="background-color: ${c.status_color}"></span></div>`
        ).join('');

        return `
            <td>
                <div class="dropdown">
                    <span>Contracts (${contracts.length})</span>
                    <div class="dropdown-content">${contractsList}</div>
                </div>
            </td>
        `;
    }

    // Generate drivers cell
    function generateDriversCell(drivers) {
        if (!drivers || !drivers.length) return '<td>-</td>';
        
        const driversList = drivers.map(d => 
            `<div>${d.name}</div>`
        ).join('');

        return `
            <td>
                <div class="dropdown">
                    <span>Drivers (${drivers.length})</span>
                    <div class="dropdown-content">${driversList}</div>
                </div>
            </td>
        `;
    }

    // Generate vehicles cell
    function generateVehiclesCell(vehicles) {
        if (!vehicles || !vehicles.length) return '<td>-</td>';
        
        const vehiclesList = vehicles.map(v => 
            `<div>${v.brand} (${v.plate_number})</div>`
        ).join('');

        return `
            <td>
                <div class="dropdown">
                    <span>Vehicles (${vehicles.length})</span>
                    <div class="dropdown-content">${vehiclesList}</div>
                </div>
            </td>
        `;
    }

    // Generate orders cell
    function generateOrdersCell(orders) {
        if (!orders || !orders.length) return '<td>-</td>';
        
        const ordersList = orders.map(o => 
            `<div>#${o.id} (${o.status})</div>`
        ).join('');

        return `
            <td>
                <div class="dropdown">
                    <span>Orders (${orders.length})</span>
                    <div class="dropdown-content">${ordersList}</div>
                </div>
            </td>
        `;
    }

    // Password protection for actions
    let pendingAction = null;

    function checkActionPassword(action, callback) {
        console.log('Checking password for action:', action);
        
        const passwordModal = document.getElementById('passwordModal');
        const actionPassword = document.getElementById('actionPassword');
        
        // Reset password input
        actionPassword.value = '';
        
        // Show password modal
        passwordModal.style.display = 'block';
        actionPassword.focus();

        // Store the callback
        document.getElementById('confirmPassword').onclick = function() {
            const password = actionPassword.value;
            
            // Convert action to action ID
            const actionId = action === 'download' ? 3 : 2; // 2 for edit/delete, 3 for download
            
            console.log('Sending password verification request for action ID:', actionId);
            
            // Send password to server for verification
            fetch('assets/check_action_password.php', {  // Changed endpoint
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: actionId,  // Send numeric action ID
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Password verification response:', data);
                if (data.success && data.isValid) {  // Check both success and isValid
                    passwordModal.style.display = 'none';
                    callback();
                } else {
                    alert('Incorrect password');
                    actionPassword.value = '';
                    actionPassword.focus();
                }
            })
            .catch(error => {
                console.error('Password verification error:', error);
                alert('Error verifying password');
            });
        };

        // Handle cancel
        document.getElementById('cancelPassword').onclick = function() {
            passwordModal.style.display = 'none';
        };
    }

    // Column visibility handlers
    columnSettingsBtn.addEventListener('click', () => {
        initializeColumnSettings();
        columnSettingsModal.style.display = 'block';
    });

    document.getElementById('applyColumnSettings').addEventListener('click', () => {
        visibleColumns.clear();
        document.querySelectorAll('#columnCheckboxes input:checked').forEach(checkbox => {
            visibleColumns.add(checkbox.id.replace('col_', ''));
        });
        columnSettingsModal.style.display = 'none';
        generateTableHeaders();
        loadPartners();
    });

    // Add cancel button handler for column settings
    document.getElementById('cancelColumnSettings').addEventListener('click', () => {
        columnSettingsModal.style.display = 'none';
    });

    // Tab switching
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            currentTab = button.dataset.tab;
            
            // Set the correct idColumn based on tab
            switch(currentTab) {
                case 'client':
                    idColumn = 'Client_id';
                    break;
                case 'courier':
                    idColumn = 'Courier_id';
                    break;
                case 'agent':
                    idColumn = 'Agent_id';
                    break;
            }
            
            currentPage = 1;
            loadPartners();
        });
    });

    // Sort handlers
    document.querySelectorAll('th[data-sort]').forEach(th => {
        th.addEventListener('click', () => {
            const field = th.dataset.sort;
            if (currentSort.field === field) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.field = field;
                currentSort.direction = 'asc';
            }
            loadPartners();
        });
    });

    // Filter handlers
    const filterInputs = [searchInput, statusFilter, bankFilter, dateFrom, dateTo];
    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            currentPage = 1;
            loadPartners();
        });
    });

    // Debounced search
    searchInput.addEventListener('input', debounce(() => {
        currentPage = 1;
        loadPartners();
    }, 300));

    // Download handler
    downloadButton.addEventListener('click', () => {
        checkActionPassword('download', () => {
            const filters = {
                tab: currentTab,
                search: searchInput.value,
                status: statusFilter.value,
                bank: bankFilter.value,
                dateFrom: dateFrom.value,
                dateTo: dateTo.value
            };
            const queryString = new URLSearchParams(filters).toString();
            window.location.href = 'assets/download_partners.php?' + queryString;
        });
    });

    // Edit/Delete handlers
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-btn')) {
            const partnerId = e.target.dataset.id;
            checkActionPassword('edit', () => {
                // Fetch partner data and show edit modal
                fetch(`assets/fetch_partner.php?id=${partnerId}&type=${currentTab}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Basic Information
                            document.getElementById('editId').value = partnerId;
                            document.getElementById('editType').value = currentTab;
                            document.getElementById('editCompanyType').value = data.Company_type;
                            document.getElementById('editFullName').value = data.Full_Company_name;
                            document.getElementById('editShortName').value = data.Short_Company_name;
                            document.getElementById('editStatus').value = data.Status;
                            document.getElementById('editINN').value = data.INN;
                            document.getElementById('editKPP').value = data.KPP;
                            document.getElementById('editOGRN').value = data.OGRN;
                            
                            // Address Information
                            document.getElementById('editPhysicalAddress').value = data.Physical_address;
                            document.getElementById('editLegalAddress').value = data.Legal_address;
                            
                            // Bank Information
                            document.getElementById('editBankName').value = data.Bank_name;
                            document.getElementById('editBIK').value = data.BIK;
                            document.getElementById('editSettlementAccount').value = data.Settlement_account;
                            document.getElementById('editCorrespondentAccount').value = data.Correspondent_account;
                            
                            // Contact Information
                            document.getElementById('editContactPerson').value = data.Contact_person;
                            document.getElementById('editContactPersonPosition').value = data.Contact_person_position;
                            document.getElementById('editContactPersonPhone').value = data.Contact_person_phone;
                            document.getElementById('editContactPersonEmail').value = data.Contact_person_email;
                            document.getElementById('editHeadPosition').value = data.Head_position;
                            document.getElementById('editHeadName').value = data.Head_name;

                            // Update status color
                            const statusSelect = document.getElementById('editStatus');
                            const selectedOption = statusSelect.options[statusSelect.selectedIndex];
                            if (selectedOption.dataset.color) {
                                statusSelect.style.borderColor = selectedOption.dataset.color;
                            }

                            document.getElementById('editModal').style.display = 'block';
                        } else {
                            alert('Error loading partner data');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error loading partner data');
                    });
            });
        }
    });

    // Add form submit handler
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            id: document.getElementById('editId').value,
            type: document.getElementById('editType').value
        };

        // Convert form data to object
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        fetch('assets/edit_partner.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('editModal').style.display = 'none';
                loadPartners(); // Reload the table
            } else {
                alert('Error updating partner: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating partner');
        });
    });

    // Add status select color change handler
    document.getElementById('editStatus').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.dataset.color) {
            this.style.borderColor = selectedOption.dataset.color;
        }
    });

    // Utility function for debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Add this function to generate table headers
    function generateTableHeaders() {
        const headerRow = document.getElementById('tableHeaders');
        let html = '';

        // Add visible columns
        columnDefs.common.forEach(col => {
            if (visibleColumns.has(col.id)) {
                const sortable = col.sortable ? ` data-sort="${col.id}"` : '';
                const sortIcon = col.sortable ? ' <i class="fas fa-sort"></i>' : '';
                html += `<th${sortable}>${col.label}${sortIcon}</th>`;
            }
        });

        // Add tab-specific columns
        if (currentTab === 'courier') {
            columnDefs.courier.forEach(col => {
                if (visibleColumns.has(col.id)) {
                    html += `<th>${col.label}</th>`;
                }
            });
        } else if (currentTab === 'client') {
            columnDefs.client.forEach(col => {
                if (visibleColumns.has(col.id)) {
                    html += `<th>${col.label}</th>`;
                }
            });
        }

        // Add actions column
        html += '<th>Actions</th>';
        
        headerRow.innerHTML = html;

        // Reattach sort handlers
        attachSortHandlers();
    }

    // Add this function to reattach sort handlers
    function attachSortHandlers() {
        document.querySelectorAll('th[data-sort]').forEach(th => {
            th.addEventListener('click', () => {
                const field = th.dataset.sort;
                if (currentSort.field === field) {
                    currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSort.field = field;
                    currentSort.direction = 'asc';
                }
                loadPartners();
            });
        });
    }

    // Add these event listeners after other initialization code
    let currentStatusButton = null;

    // Update the status button click handler
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('status-btn') || e.target.closest('.status-btn')) {
            const statusBtn = e.target.classList.contains('status-btn') ? 
                e.target : e.target.closest('.status-btn');
            
            currentStatusButton = statusBtn;
            statusModal.style.display = 'block';
        } else if (e.target.classList.contains('status-option')) {
            const newStatusId = e.target.dataset.statusId;
            const partnerId = currentStatusButton.dataset.id;
            
            fetch('assets/update_partner_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: partnerId,
                    type: currentTab,
                    status: newStatusId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusModal.style.display = 'none';
                    loadPartners(); // Reload the table
                } else {
                    alert('Error updating status: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating status');
            });
        }
    });

    // Close status modal
    cancelStatusChange.addEventListener('click', () => {
        statusModal.style.display = 'none';
    });

    // Initial load
    loadPartners();
    generateTableHeaders(); // Initial header generation
}); 