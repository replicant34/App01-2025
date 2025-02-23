document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const entityTypeSelect = document.getElementById('entityType');
    const entityIdSelect = document.getElementById('entityId');
    const contractTypeSelect = document.getElementById('contractType');
    const contractNumberInput = document.getElementById('contractNumber');
    const generateNumberBtn = document.getElementById('generateNumber');
    const contractDateInput = document.getElementById('contractDate');
    const contractStatusSelect = document.getElementById('contractStatus');
    const contractForm = document.getElementById('contractForm');
    const contractNumberMessage = document.getElementById('contractNumberMessage');

    // Initialize date picker
    flatpickr(contractDateInput, {
        dateFormat: "Y-m-d",
        allowInput: true
    });

    // Enable/disable form fields based on entity type selection
    entityTypeSelect.addEventListener('change', function() {
        const isSelected = this.value !== '';
        
        // Enable/disable fields
        const fieldsToToggle = [
            entityIdSelect,
            contractTypeSelect,
            contractNumberInput,
            generateNumberBtn,
            contractDateInput,
            contractStatusSelect
        ];

        fieldsToToggle.forEach(field => {
            field.disabled = !isSelected;
        });

        if (isSelected) {
            // Fetch companies based on selected type
            fetchCompanies(this.value);
        } else {
            // Reset second select
            entityIdSelect.innerHTML = '<option value="">First select type</option>';
            entityIdSelect.disabled = true;
        }
    });

    // Fetch companies based on entity type
    function fetchCompanies(entityType) {
        fetch(`assets/fetch_entities.php?type=${entityType}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    entityIdSelect.innerHTML = '<option value="">Select Company</option>';
                    data.entities.forEach(entity => {
                        const option = document.createElement('option');
                        option.value = entity.id;
                        option.textContent = entity.name;
                        entityIdSelect.appendChild(option);
                    });
                } else {
                    throw new Error(data.error || 'Error fetching companies');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching companies. Please try again.');
            });
    }

    // Generate contract number
    generateNumberBtn.addEventListener('click', function() {
        fetch('assets/generate_contract_number.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contractNumberInput.value = data.number;
                    checkContractNumber(data.number);
                } else {
                    throw new Error(data.error || 'Error generating number');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error generating contract number. Please try again.');
            });
    });

    // Check contract number uniqueness
    let checkTimeout;
    contractNumberInput.addEventListener('input', function() {
        clearTimeout(checkTimeout);
        const number = this.value.trim();
        
        if (number) {
            checkTimeout = setTimeout(() => checkContractNumber(number), 500);
        } else {
            contractNumberMessage.textContent = '';
            contractNumberMessage.className = 'message';
        }
    });

    function checkContractNumber(number) {
        fetch(`assets/check_contract_number.php?number=${encodeURIComponent(number)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (!data.isUnique) {
                        contractNumberMessage.textContent = 'This contract number already exists';
                        contractNumberMessage.className = 'message error';
                    } else {
                        contractNumberMessage.textContent = 'Contract number is available';
                        contractNumberMessage.className = 'message success';
                    }
                } else {
                    throw new Error(data.error || 'Error checking number');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                contractNumberMessage.textContent = 'Error checking contract number';
                contractNumberMessage.className = 'message error';
            });
    }

    // Form submission
    contractForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (contractNumberMessage.classList.contains('error')) {
            alert('Please fix the contract number before submitting');
            return;
        }

        const formData = new FormData(this);
        
        // Log form data for debugging
        console.log('Submitting form data:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        createContract();
    });

    // Add color indicator for status
    contractStatusSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const color = selectedOption.dataset.color;
        if (color) {
            this.style.borderColor = color;
        } else {
            this.style.borderColor = '#3498db'; // default border color
        }
    });

    function createContract() {
        const formData = {
            entity_type: entityTypeSelect.value,
            entity_id: entityIdSelect.value,
            contract_type: contractTypeSelect.value,
            contract_number: contractNumberInput.value,
            contract_date: contractDateInput.value,
            contract_status: contractStatusSelect.value
        };

        // Debug log
        console.log('Sending data:', formData);

        fetch('assets/create_contract.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Contract created successfully');
                window.location.href = 'manage_contracts.php';
            } else {
                throw new Error(data.error || 'Failed to create contract');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating contract: ' + error.message);
        });
    }
}); 