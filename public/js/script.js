let operations = [];
let operationCount = 100; // Start from 100 for better user experience

// Function to handle AJAX requests
function handleAjax(url, method, body, successCallback, errorCallback) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(body)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            errorCallback(data.error);
        } else {
            successCallback(data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorCallback("An error occurred: " + error.message);
    });
}

// Add an operation to the list
function addOperation() {
    const process = document.getElementById('process').value;
    const workstation = document.getElementById('workstation').value;
    const standardHours = document.getElementById('standardHours').value;
    const woNumber = document.getElementById('wo_number').value; // Get the WO number

    // Check if WO number is generated before adding an operation
    if (!woNumber) {
        showError("Please generate a WO number before adding operations.");
        return;
    }

    // Validate inputs
    if (!validateInputs(process, workstation, standardHours)) return;

    const orderDate = new Date(document.getElementById('order_date').value);
    const requiredDate = new Date(document.getElementById('required_date').value);

    // Validate dates
    if (!validateDates(orderDate, requiredDate)) return;

    // Push operation data into the array
    operations.push({ operation: operationCount++, process, workstation, standardHours });
    addRowToOperationsTable(process, workstation, standardHours, woNumber);
    clearInputFields();
    showNotification("Operation added!");
}

// Validate user inputs
function validateInputs(process, workstation, standardHours) {
    if (!process || !workstation || !standardHours || isNaN(standardHours) || standardHours <= 0) {
        showError("Please fill in all fields with valid data. Standard Hours must be a positive number.");
        return false;
    }
    return true;
}

// Validate the order and required dates
function validateDates(orderDate, requiredDate) {
    if (isNaN(orderDate) || isNaN(requiredDate)) {
        showError("Please select valid dates for both order and required dates.");
        return false;
    }
    if (requiredDate < orderDate) {
        showError("The required date must be the same or after the order date.");
        return false;
    }
    return true;
}

// Add a new row to the operations table
function addRowToOperationsTable(process, workstation, standardHours, woNumber) {
    const tableBody = document.getElementById('operationsTable').getElementsByTagName('tbody')[0];
    const operationNumber = operations[operations.length - 1].operation; // Get the current operation number

    // Format QR code data
    const qrCodeData = `${woNumber}#${operationNumber}`; // Format: "WO Number#Operation Number"

    const newRow = tableBody.insertRow();
    newRow.insertCell(0).textContent = operationNumber; // Operation Number
    newRow.insertCell(1).textContent = process; // Process
    newRow.insertCell(2).textContent = workstation; // Workstation
    newRow.insertCell(3).textContent = standardHours; // Standard Hours
    newRow.insertCell(4).innerHTML = '<div class="signature"></div>'; // Signature

    // Generate QR code using the formatted data
    const qrCodeDataUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrCodeData)}&size=100x100`; // Adjustable size
    newRow.insertCell(5).innerHTML = `<img src="${qrCodeDataUrl}" class="qr-code" alt="QR Code">`; // QR Code
}

// Clear the input fields
function clearInputFields() {
    document.getElementById('process').value = '';
    document.getElementById('workstation').value = '';
    document.getElementById('standardHours').value = '';
}

// Show a notification message
function showNotification(message, duration=15000) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.style.display = 'block';
    setTimeout(() => {
        notification.style.display = 'none';
    }, 2000);
}

// Show an error message
function showError(message, duration=15000) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.style.color = 'red'; // Make the error message red
    notification.style.display = 'block';
    setTimeout(() => {
        notification.style.display = 'none';
        notification.style.color = ''; // Reset to default color
    }, 2000);
}

// Attach form submission event once the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Clear WO number input and notification on page load
    document.getElementById('wo_number').value = ''; // Reset WO number to empty
    const notification = document.getElementById('notification');
    notification.textContent = ''; // Clear notification message
    notification.style.display = 'none'; // Hide notification

    // Attach form submission event
    document.getElementById('productionForm').onsubmit = function(event) {
        event.preventDefault(); // Prevent the default form submission

       

        gatherFormData(); // Gather operations data before submission
        this.submit(); // Submit the form
    };

    // Attach event listener for generating WO number button
    const generateWOButton = document.querySelector("button[onclick='generateWONumber()']");

    if (generateWOButton) {
        generateWOButton.addEventListener('click', generateWONumber);
    } else {
        console.error('Generate WO button not found');
    }
});



function gatherFormData() {
    const operationsData = [];
    const rawMaterialsData = [];
    const consumablesData = [];

    // Gather operations data
    document.querySelectorAll('#operationsTable tbody tr').forEach(row => {
        const cells = row.cells;
        operationsData.push({
            operation: cells[0].textContent, // Operation Number
            process: cells[1].textContent, // Process
            workstation: cells[2].textContent, // Workstation
            standardHours: cells[3].textContent // Standard Hours
        });
    });

    // Gather raw materials data
    document.querySelectorAll('#raw-materials-table tbody tr').forEach(row => {
        const grade = row.querySelector('input[name*="[grade]"]').value;
        const size = row.querySelector('input[name*="[size]"]').value;
        const length = row.querySelector('input[name*="[length]"]').value;
        const hnBarNoTno = row.querySelector('input[name*="[hn_bar_tno]"]').value;

        rawMaterialsData.push({ grade, size, length, hnBarNoTno });
    });

    // Gather consumables data
    document.querySelectorAll('#consumables-table tbody tr').forEach(row => {
        const type = row.querySelector('input[name*="[type]"]').value;
        const quantity = row.querySelector('input[name*="[quantity]"]').value;
        const uom = row.querySelector('select[name*="[uom]"]').value;

        consumablesData.push({ type, quantity, uom });
    });

    // Store data in hidden inputs to submit with the form
    document.getElementById('operations_data').value = JSON.stringify(operationsData);
    document.getElementById('raw_materials_data').value = JSON.stringify(rawMaterialsData);
    document.getElementById('consumables_data').value = JSON.stringify(consumablesData);
}


// Generate WO number
function generateWONumber() {
    const soNumber = document.getElementById('so_number').value;
    if (!soNumber) {
        showError("Please enter the Sales Order Number.");
        return;
    }

    handleAjax('/production-management/public/production/generate-wo-number', 'POST', { so_number: soNumber },
        (data) => {
            document.getElementById('wo_number').value = data.wo_number; // Set the WO number in the input field
            showNotification("WO Number generated!");
        },
        (errorMessage) => showError("Failed to generate WO Number: " + errorMessage)
    );
}

function generateAssetNumber() {
    // Get the WO number and lot size from the form
    var woNumber = document.getElementById('wo_number').value;
    var lotSize = parseInt(document.getElementById('lot_size').value);

    if (!woNumber || lotSize <= 0) {
        alert('Please enter a valid WO number and lot size.');
        return;
    }

    var generatedAssetNos = [];
    
    // Loop through the lot size and generate asset numbers
    for (var i = 1; i <= lotSize; i++) {
        // Generate asset number by appending the increment (e.g., 24-00001-01-1, 24-00001-01-2, etc.)
        var assetNo = woNumber + '-' + i;
        generatedAssetNos.push(assetNo);
    }

    // Set the generated asset numbers to the input field
    document.getElementById('generated_asset_no').value = generatedAssetNos.join(',');


}