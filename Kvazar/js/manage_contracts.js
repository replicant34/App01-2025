document.addEventListener('DOMContentLoaded', function() {
    // Constants
    const ITEMS_PER_PAGE = 10;
    let currentPage = 1;
    let currentTab = 'client';
    let currentSort = { field: 'created_at', direction: 'desc' };
    let contractData = [];

    // DOM Elements
    const tabButtons = document.querySelectorAll('.tab-btn');
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const table = document.getElementById('contractsTable');
    const tableBody = table.querySelector('tbody');
    const modal = document.getElementById('passwordModal');
    const actionPassword = document.getElementById('actionPassword');
    
    // Debug log for download button
    console.log('Looking for download button...');
    const downloadButton = document.getElementById('downloadContracts');
    console.log('Download button found:', downloadButton);

    if (downloadButton) {
        console.log('Adding click event to download button');
        downloadButton.addEventListener('click', function() {
            console.log('Download button clicked');
            const filters = {
                tab: currentTab,
                search: searchInput.value,
                type: typeFilter.value,
                status: statusFilter.value,
                dateFrom: dateFrom.value,
                dateTo: dateTo.value
            };

            console.log('Download filters:', filters);
            const queryString = new URLSearchParams(filters).toString();
            window.location.href = 'assets/download_contracts.php?' + queryString;
        });
    } else {
        console.error('Download button not found in the DOM');
    }

    // Action state
    let pendingAction = null;

    // Function Declarations
    function renderPagination(totalPages) {
        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'pagination';
        
        // Previous button
        const prevButton = document.createElement('button');
        prevButton.innerHTML = '&laquo; Previous';
        prevButton.className = `pagination-btn ${currentPage === 1 ? 'disabled' : ''}`;
        prevButton.disabled = currentPage === 1;
        prevButton.onclick = () => {
            if (currentPage > 1) {
                currentPage--;
                loadContracts();
            }
        };
        
        // Next button
        const nextButton = document.createElement('button');
        nextButton.innerHTML = 'Next &raquo;';
        nextButton.className = `pagination-btn ${currentPage === totalPages ? 'disabled' : ''}`;
        nextButton.disabled = currentPage === totalPages;
        nextButton.onclick = () => {
            if (currentPage < totalPages) {
                currentPage++;
                loadContracts();
            }
        };
        
        // Page info
        const pageInfo = document.createElement('span');
        pageInfo.className = 'page-info';
        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        
        paginationContainer.appendChild(prevButton);
        paginationContainer.appendChild(pageInfo);
        paginationContainer.appendChild(nextButton);
        
        // Find or create pagination container in DOM
        let existingPagination = document.querySelector('.pagination');
        if (existingPagination) {
            existingPagination.replaceWith(paginationContainer);
        } else {
            document.querySelector('.table-container').appendChild(paginationContainer);
        }
    }

    // Initialize
    loadContracts();

    // Tab Switching
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            currentTab = button.dataset.tab;
            currentPage = 1;
            loadContracts();
        });
    });

    // Sorting
    document.querySelectorAll('th[data-sort]').forEach(th => {
        th.addEventListener('click', () => {
            const field = th.dataset.sort;
            if (currentSort.field === field) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = { field, direction: 'asc' };
            }
            updateSortIcons();
            loadContracts();
        });
    });

    // Filtering
    const filterInputs = [searchInput, typeFilter, statusFilter, dateFrom, dateTo];
    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            currentPage = 1;
            loadContracts();
        });
    });

    searchInput.addEventListener('input', debounce(() => {
        currentPage = 1;
        loadContracts();
    }, 300));

    // Load Contracts
    function loadContracts() {
        const filters = {
            search: searchInput.value,
            type: typeFilter.value,
            status: statusFilter.value,
            dateFrom: dateFrom.value,
            dateTo: dateTo.value,
            page: currentPage,
            perPage: ITEMS_PER_PAGE,
            sort: JSON.stringify(currentSort),
            tab: currentTab
        };

        const queryString = new URLSearchParams(filters).toString();
        console.log('Fetching with params:', queryString);

        fetch('assets/fetch_contracts.php?' + queryString)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Server response:', text);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    contractData = data.contracts;
                    renderContracts(data);
                    renderPagination(data.totalPages);
                } else {
                    throw new Error(data.error || data.debug || 'Unknown error');
                }
            })
            .catch(error => {
                console.error('Full error details:', error);
                console.error('Error stack:', error.stack);
                alert('Error loading contracts: ' + error.message);
            });
    }

    // Render Contracts
    function renderContracts(data) {
        tableBody.innerHTML = '';
        
        data.contracts.forEach(contract => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${contract.contract_number || '-'}</td>
                <td>${contract.company_name || '-'}</td>
                <td>${contract.contract_type || '-'}</td>
                <td>${formatDate(contract.contract_date)}</td>
                <td class="status-cell">
                    <select class="status-select" style="background-color: ${contract.status_color || '#ddd'}"
                            data-contract-id="${contract.id}" data-entity-type="${currentTab}">
                        ${generateStatusOptions(contract.status_name)}
                    </select>
                </td>
                <td>${formatDate(contract.created_at)}</td>
                <td>${contract.created_by_name || '-'}</td>
                <td class="action-buttons">
                    <button class="btn-delete" data-id="${contract.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(tr);
        });

        // Add event listeners
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', handleStatusChange);
        });

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', () => handleAction('delete', btn.dataset.id));
        });
    }

    // Handle Status Change - simplified version without password
    function handleStatusChange(e) {
        const select = e.target;
        const contractId = select.dataset.contractId;
        const entityType = select.dataset.entityType;
        const newStatus = select.value;
        
        // Directly update status without password check
        updateContractStatus(contractId, entityType, newStatus);
    }

    // Update Contract Status - remains the same
    function updateContractStatus(contractId, entityType, newStatus) {
        fetch('assets/update_contract_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contract_id: contractId,
                entity_type: entityType,
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadContracts(); // Reload the table
            } else {
                throw new Error(data.error || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error updating status:', error);
            // Reload contracts to reset the select to its previous value
            loadContracts();
            alert(error.message || 'Error updating status');
        });
    }

    // Handle Action
    function handleAction(action, contractId) {
        if (action === 'delete') {
            checkActionPassword('Редактировать и удалить договор', () => {
                deleteContract(contractId);
            });
        }
    }

    // Delete Contract
    function deleteContract(contractId) {
        if (!confirm('Are you sure you want to delete this contract?')) {
            return;
        }

        fetch('assets/delete_contract.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contract_id: contractId,
                entity_type: currentTab
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadContracts(); // Reload the contracts list
                alert('Contract deleted successfully');
            } else {
                throw new Error(data.error || 'Failed to delete contract');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting contract: ' + error.message);
        });
    }

    // Password Confirmation
    function checkActionPassword(actionName, callback) {
        pendingAction = { callback };
        modal.style.display = 'block';
        actionPassword.value = '';
        actionPassword.focus();
    }

    // Modal Events
    document.getElementById('confirmPassword').addEventListener('click', () => {
        if (!pendingAction) return;
        
        fetch('assets/check_action_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'Редактировать и удалить договор',
                password: actionPassword.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.isValid) {
                modal.style.display = 'none';
                pendingAction.callback();
            } else {
                alert('Incorrect password');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error checking password');
        });
    });

    document.getElementById('cancelPassword').addEventListener('click', () => {
        modal.style.display = 'none';
        pendingAction = null;
        loadContracts(); // Reload to reset any partial changes
    });

    // Utility Functions
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('en-GB'); // or your preferred locale
    }

    function generateStatusOptions(currentStatus) {
        return Array.from(statusFilter.options)
            .filter(option => option.value)
            .map(option => `
                <option value="${option.text}" 
                    ${option.text === currentStatus ? 'selected' : ''}>
                    ${option.text}
                </option>
            `).join('');
    }

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

    function updateSortIcons() {
        document.querySelectorAll('th[data-sort] i').forEach(icon => {
            const th = icon.closest('th');
            if (th.dataset.sort === currentSort.field) {
                icon.className = `fas fa-sort-${currentSort.direction === 'asc' ? 'up' : 'down'}`;
            } else {
                icon.className = 'fas fa-sort';
            }
        });
    }

    // Alternative approach using event delegation
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'downloadContracts' || 
            (e.target.parentElement && e.target.parentElement.id === 'downloadContracts')) {
            console.log('Download button clicked through delegation');
            const filters = {
                tab: currentTab,
                search: searchInput.value,
                type: typeFilter.value,
                status: statusFilter.value,
                dateFrom: dateFrom.value,
                dateTo: dateTo.value
            };

            console.log('Download filters:', filters);
            const queryString = new URLSearchParams(filters).toString();
            window.location.href = 'assets/download_contracts.php?' + queryString;
        }
    });
}); 