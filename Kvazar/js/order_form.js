document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to clicked button and corresponding content
            button.classList.add('active');
            const tabId = button.dataset.tab;
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Client search functionality
    const clientSearch = document.getElementById('clientSearch');
    const clientSelect = document.getElementById('client');
    
    if (clientSearch && clientSelect) {
        // Create search results container
        const searchResults = document.createElement('div');
        searchResults.className = 'search-results';
        clientSearch.parentNode.appendChild(searchResults);

        let selectedIndex = -1;

        // Sync select with search input
        clientSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            clientSearch.value = selectedOption.text;
            searchResults.style.display = 'none';
            
            // Fetch contracts when client is selected
            if (this.value) {
                fetchContracts(this.value);
            } else {
                contractInput.value = '';
                contractInput.classList.remove('error');
                contractMessage.textContent = '';
            }
        });

        // Function to show search results
        function showSearchResults(searchTerm) {
            searchResults.innerHTML = '';
            const options = Array.from(clientSelect.options).slice(1); // Skip first "Select" option
            
            const filteredOptions = options.filter(option => 
                option.text.toLowerCase().includes(searchTerm.toLowerCase())
            );

            filteredOptions.forEach((option, index) => {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.textContent = option.text;
                
                div.addEventListener('click', () => {
                    clientSelect.value = option.value;
                    clientSearch.value = option.text;
                    searchResults.style.display = 'none';
                    fetchContracts(option.value);
                });

                div.addEventListener('mouseover', () => {
                    selectedIndex = index;
                    updateSelectedItem();
                });

                searchResults.appendChild(div);
            });

            searchResults.style.display = filteredOptions.length ? 'block' : 'none';
            selectedIndex = -1;
            updateSelectedItem();
        }

        function updateSelectedItem() {
            const items = searchResults.getElementsByClassName('search-result-item');
            Array.from(items).forEach((item, index) => {
                item.classList.toggle('selected', index === selectedIndex);
            });
        }

        // Input event handler
        clientSearch.addEventListener('input', function() {
            showSearchResults(this.value);
            // Clear select value when typing
            if (this.value !== clientSelect.options[clientSelect.selectedIndex].text) {
                clientSelect.value = '';
            }
        });

        // Keyboard navigation
        clientSearch.addEventListener('keydown', function(e) {
            const items = searchResults.getElementsByClassName('search-result-item');
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    updateSelectedItem();
                    if (items[selectedIndex]) {
                        items[selectedIndex].scrollIntoView({ block: 'nearest' });
                    }
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelectedItem();
                    if (items[selectedIndex]) {
                        items[selectedIndex].scrollIntoView({ block: 'nearest' });
                    }
                    break;

                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0 && items[selectedIndex]) {
                        items[selectedIndex].click();
                    }
                    break;

                case 'Escape':
                    searchResults.style.display = 'none';
                    break;
            }
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!clientSearch.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
                // Sync search input with current select value
                const selectedOption = clientSelect.options[clientSelect.selectedIndex];
                clientSearch.value = selectedOption ? selectedOption.text : '';
            }
        });

        // Show all options when focusing on empty input
        clientSearch.addEventListener('focus', function() {
            if (!this.value) {
                showSearchResults('');
            }
        });
    }

    // Add this after client selection code
    const contractInput = document.getElementById('contract');
    const contractMessage = document.querySelector('.contract-message');

    // Add these constants with other declarations
    const contractDateInput = document.getElementById('contract_date');
    const contractDateMessage = document.querySelector('.contract-date-message');

    // Function to fetch and handle contracts
    function fetchContracts(clientId) {
        console.log('Fetching contracts for client:', clientId);

        fetch(`assets/get_client_contracts.php?client_id=${clientId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Server response:', data);

                if (data.success) {
                    const contracts = data.contracts || [];
                    const contractDate = data.contract_date;
                    
                    // Handle contract number
                    if (contracts.length === 0) {
                        contractInput.value = 'No contract found';
                        contractInput.classList.add('error');
                        contractInput.style.borderColor = '#e74c3c';
                        contractMessage.textContent = '';
                    } else {
                        contractInput.value = contracts[0];
                        contractInput.classList.remove('error');
                        contractMessage.textContent = '';
                        contractInput.style.borderColor = '#3498db';
                    }

                    // Handle contract date
                    if (contractDate) {
                        contractDateInput.value = contractDate;
                        contractDateInput.classList.remove('error');
                        contractDateInput.style.borderColor = '#3498db';
                        contractDateMessage.textContent = '';
                    } else {
                        contractDateInput.value = 'No date found';
                        contractDateInput.classList.add('error');
                        contractDateInput.style.borderColor = '#e74c3c';
                        contractDateMessage.textContent = '';
                    }
                } else {
                    // Error handling for both fields
                    contractInput.value = 'Error';
                    contractInput.classList.add('error');
                    contractInput.style.borderColor = '#e74c3c';
                    contractMessage.textContent = data.error || 'Error fetching contracts';

                    contractDateInput.value = 'Error';
                    contractDateInput.classList.add('error');
                    contractDateInput.style.borderColor = '#e74c3c';
                    contractDateMessage.textContent = '';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                // Error handling for both fields
                contractInput.value = 'Error';
                contractInput.classList.add('error');
                contractInput.style.borderColor = '#e74c3c';
                contractMessage.textContent = 'Error fetching contracts';

                contractDateInput.value = 'Error';
                contractDateInput.classList.add('error');
                contractDateInput.style.borderColor = '#e74c3c';
                contractDateMessage.textContent = '';
            });
    }

    // Add after the contract handling code
    const contractorInput = document.getElementById('contractor');
    const contractorIdInput = document.getElementById('contractor_id');

    // Function to fetch and display contractor
    function fetchContractor() {
        fetch('assets/get_contractor.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.contractor) {
                    contractorInput.value = data.contractor.Full_Company_name;
                    contractorIdInput.value = data.contractor.Contractors_id;
                    contractorInput.style.borderColor = '#3498db';
                } else {
                    contractorInput.value = 'No contractor found';
                    contractorInput.style.borderColor = '#e74c3c';
                    contractorIdInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                contractorInput.value = 'Error loading contractor';
                contractorInput.style.borderColor = '#e74c3c';
                contractorIdInput.value = '';
            });
    }

    // Fetch contractor data
    fetchContractor();

    // Add after the contractor code
    const orderNumberInput = document.getElementById('order_number');
    const orderNumberMessage = document.querySelector('.order-number-message');

    // For admin/CEO: Check order number uniqueness when they change it
    if (!orderNumberInput.readOnly) {
        let checkTimeout;
        
        orderNumberInput.addEventListener('input', function() {
            clearTimeout(checkTimeout);
            
            // Remove any existing validation classes
            orderNumberInput.classList.remove('error');
            orderNumberMessage.textContent = '';
            orderNumberInput.style.borderColor = '#3498db';
            
            // Debounce the check
            checkTimeout = setTimeout(() => {
                if (this.value.trim()) {
                    checkOrderNumber(this.value);
                }
            }, 300);
        });
    }

    function checkOrderNumber(number) {
        fetch(`assets/check_order_number.php?order_number=${encodeURIComponent(number)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (!data.isUnique) {
                        orderNumberInput.classList.add('error');
                        orderNumberMessage.textContent = 'This order number already exists';
                        orderNumberInput.style.borderColor = '#e74c3c';
                    } else {
                        orderNumberInput.classList.remove('error');
                        orderNumberMessage.textContent = '';
                        orderNumberInput.style.borderColor = '#3498db';
                    }
                } else {
                    throw new Error(data.error || 'Error checking order number');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                orderNumberInput.classList.add('error');
                orderNumberMessage.textContent = 'Error checking order number';
                orderNumberInput.style.borderColor = '#e74c3c';
            });
    }

    // Add validation to form submission
    const orderForm = document.getElementById('orderForm');
    orderForm.addEventListener('submit', function(e) {
        if (orderNumberInput.classList.contains('error')) {
            e.preventDefault();
            alert('Please fix the order number before submitting');
        }
    });
}); 