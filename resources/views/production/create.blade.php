<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Production Order</title>
    
    <!-- CSRF token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.11/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-qrcode/1.0/jquery.qrcode.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/script.js') }}" defer></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
            background-color: #f7f7f7;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #333;
        }

        .home-link {
            margin-bottom: 20px;
        }

        .home-link a {
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.1em;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-info {
            background-color: #cce5ff;
            color: #004085;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            margin-top: 30px;
            font-size: 1.4em;
            color: #333;
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Increased gap for better spacing */
            align-items: flex-start;
        }

        .form-group .input-container {
            flex: 1;
            min-width: 250px;
        }

        #operation_name {
            width: 200px; /* Adjust select box width */
            height: 40px; /* Adjust select box height */
            font-size: 16px;
            padding: 5px;
        }

        .form-group .input-container input,
        .form-group .input-container textarea {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 150px;
            text-align: center;
            display: inline-block;
            padding: 10px 15px;
            margin: 10px 0;
            font-size: 1em;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button[type="submit"] {
            background-color: #28a745;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #007bff;
        }

        @media (max-width: 768px) {
            .form-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<h1>Create Production Order</h1>

<!-- Home Button -->
<div class="home-link">
    <a href="{{ route('production.index') }}" class="button-theme">Back to Home</a>
</div>

<div id="createOrder" class="tab active">
    <h2>Create Production Order</h2>
    <div id="notification" style="display: none;"></div> <!-- Initially hidden -->

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('wo_number'))
        <div class="alert alert-info">Generated WO Number: {{ session('wo_number') }}</div>
    @endif

    <form id="productionForm" action="{{ route('production.store') }}" method="POST" onsubmit="gatherOperationsData()">
        @csrf
        
        <div class="form-container">

            <!-- Order Information Section -->
            <h3>Order Information</h3>
            <div class="form-group">
                <div class="input-container">
                    <label for="wo_number">WO Number:</label>
                    <input type="text" id="wo_number" name="wo_number" placeholder="Generated WO Number" 
                           value="{{ old('wo_number', session('wo_number')) }}" readonly>
                    <button type="button" onclick="generateWONumber()">Generate WO Number</button>
                </div>
                <div class="input-container">
                    <label for="so_number">SO Number:</label>
                    <input type="text" id="so_number" name="so_number" placeholder="Enter SO Number" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-container">
                    <label for="cust_po_number">Cust PO Number:</label>
                    <input type="text" id="cust_po_number" name="cust_po_number" placeholder="Enter Cust PO Number" required>
                </div>
                <div class="input-container">
                    <label for="customer">Customer:</label>
                    <input type="text" id="customer" name="customer" placeholder="Enter customer name" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-container">
                    <label for="order_date">Order Date:</label>
                    <input type="date" id="order_date" name="order_date" required>
                </div>
                <div class="input-container">
                    <label for="required_date">Required Date:</label>
                    <input type="date" id="required_date" name="required_date" required>
                </div>
            </div>

            <!-- Product Information Section -->
            <h3>Product Information</h3>
            <div class="form-group">
                <div class="input-container">
                    <label for="job_scope">Job Scope:</label>
                    <textarea id="job_scope" name="job_scope" placeholder="Enter Job Scope" required rows="4"></textarea>
                </div>
                <div class="input-container">
                    <label for="part_description">Part Description:</label>
                    <input type="text" id="part_description" name="part_description" placeholder="Part description" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-container">
                    <label for="drawing_no">Drawing No.:</label>
                    <input type="text" id="drawing_no" name="drawing_no" placeholder="Enter Drawing No.">
                </div>
                <div class="input-container">
                    <label for="asset_no_input">Asset#:</label>
                    <textarea id="asset_no_input" name="asset_no_input" placeholder="Enter Asset#"></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="input-container">
                    <label for="generated_asset_no">Generated Asset#:</label>
                    <textarea id="generated_asset_no" name="generated_asset_no" placeholder="Generated Asset#" readonly></textarea>
                    <button type="button" onclick="generateAssetNumber()">Generate Asset#</button>
                </div>
                <div class="input-container">
                    <label for="lot_size">This lot consists of:</label>
                    <input type="number" id="lot_size" name="lot_size" placeholder="Enter number of assets" required min="1" max="99">
                </div>
            </div>

            <h3>Raw Materials</h3>
<table id="raw-materials-table">
    <thead>
        <tr>
            <th>Grade</th>
            <th>Size</th>
            <th>Length</th>
            <th>HN/Bar NO/TNO</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- Dynamic Rows -->
    </tbody>
</table>
<button type="button" id="add-raw-material">Add Raw Material</button>


<h3>Consumables</h3>
<table id="consumables-table">
    <thead>
        <tr>
            <th>Type</th>
            <th>Quantity</th>
            <th>UOM</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- Dynamic Rows -->
    </tbody>
</table>
<button type="button" id="add-consumable">Add Consumable</button>


            <!-- Operations Section -->
            <h3>Operations Input</h3>
            <div class="form-group">

        <div class="input-container">
            <label for="operation_name">Operation:</label>
            <select id="operation_name" name="operation_name" required>
                <option value="" disabled selected>Select an operation</option>
             </select>
             <button type="button" id="createNewOperationButton">Create New Operation</button>
         </div>

        <div class="input-container">
             <label for="process">Process:</label>
             <input type="text" id="process" placeholder="Process description" required>
        </div>

        <div class="input-container">
            <label for="workstation">Work Station:</label>
            <input type="text" id="workstation" placeholder="Machine name" required>
        </div>

        <div class="input-container">
             <label for="standardHours">Standard Hours:</label>
            <input type="number" id="standardHours" placeholder="Hours" required min="0">
        </div>


            <div class="form-group">
                <button type="button" onclick="addOperation()">Add Operation</button>
                <button type="button" onclick="clearAll()">Clear All</button>
            </div>

            <!-- Operations Review Section -->
            <h3>Operations Review</h3>
            <table id="operationsTable">
                <thead>
                    <tr>
                        <th>Operation</th>
                        <th>Process</th>
                        <th>Work Station</th>
                        <th>Standard Hours</th>
                        <th>Signature</th>
                        <th>QR Code</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Operations will be added here -->
                </tbody>
            </table>

            <input type="hidden" id="operations_data" name="operations_data">
            <input type="hidden" id="raw_materials_data" name="raw_materials_data">
            <input type="hidden" id="consumables_data" name="consumables_data">

            <button type="submit">Create Order</button>
        </div>
    </form>
</div>

</body>

<script>
    const listOperationsUrl = `{{ route('listed-operations') }}`;
    const createOperationUrl = `{{ route('new-operation') }}`;
    const updateOperationUrl = `{{ route('update-operation', ['id' => ':id']) }}`;

    $(document).ready(function() {
        // Function to create and display the operation creation modal
        function showCreateOperationModal(operation = null) {
         
            $('#operationModal').remove();

            let modalHtml = `
                <div id="operationModal" style="position: fixed; top: 20%; left: 30%; width: 40%; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000;">
                    <h2>${operation ? 'Edit Operation' : 'Create New Operation'}</h2>
                    <label for="new_operation_name">Operation Name:</label>
                    <input type="text" id="new_operation_name" value="${operation ? operation.operation_name : ''}" required><br><br>

                    <label for="new_process">Process:</label>
                    <input type="text" id="new_process" value="${operation ? operation.process : ''}" required><br><br>

                    <label for="new_workstation">Work Station:</label>
                    <input type="text" id="new_workstation" value="${operation ? operation.workstation : ''}" required><br><br>

                    <label for="new_standard_hours">Standard Hours:</label>
                    <input type="number" id="new_standard_hours" value="${operation ? operation.standard_hours : ''}" required min="0" step="0.5"><br><br>

                    <button id="saveOperationButton" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px;">${operation ? 'Update' : 'Save'} Operation</button>
                    <button id="closeModalButton" style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 5px;">Close</button>
                </div>
            `;

            $('body').append(modalHtml);

            // If operation data is passed, set the action URL for updating
            if (operation) {
                $('#saveOperationButton').attr('data-id', operation.id); // Set data-id for editing
            }
        }

        // Load operations from the server on page load
        function loadOperations() {
            $.ajax({
                url: listOperationsUrl,
                type: 'GET',
                success: function(response) {
                    const operationSelect = $('#operation_name');
                    operationSelect.empty();
                    operationSelect.append('<option value="" disabled selected>Select an operation</option>');
                    response.forEach(operation => {
                        operationSelect.append(`
                            <option value="${operation.id}" 
                                    data-process="${operation.process}" 
                                    data-workstation="${operation.workstation}" 
                                    data-standard-hours="${operation.standard_hours}">
                                ${operation.operation_name}
                            </option>
                        `);
                    });
                },
                error: function(error) {
                    console.error('Error loading operations:', error);
                }
            });
        }

        // Call loadOperations on page load
        loadOperations();

        // When operation is selected, fill the fields
        $(document).on('change', '#operation_name', function() {
            const selectedOption = $(this).find('option:selected');
            const process = selectedOption.data('process');
            const workstation = selectedOption.data('workstation');
            const standardHours = selectedOption.data('standard-hours');

            $('#process').val(process);               // Populate process field
            $('#workstation').val(workstation);       // Populate workstation field
            $('#standardHours').val(standardHours);   // Populate standard hours field
        });

        // Show modal for creating a new operation
        $(document).on('click', '#createNewOperationButton', function() {
         showCreateOperationModal();
        });

        // Save or Update operation when the "Save" or "Update" button is clicked
        $(document).on('click', '#saveOperationButton', function() {
            const operationId = $(this).attr('data-id');
            const newOperationData = {
                operation_name: $('#new_operation_name').val().trim(),
                process: $('#new_process').val().trim(),
                workstation: $('#new_workstation').val().trim(),
                standard_hours: parseFloat($('#new_standard_hours').val().trim()),
            };

            // Validate form fields before making the AJAX request
            if (!newOperationData.operation_name) {
                alert('Operation Name is required.');
                return;
            }
            if (!newOperationData.process) {
                alert('Process is required.');
                return;
            }
            if (!newOperationData.workstation) {
                alert('Workstation is required.');
                return;
            }
            if (!newOperationData.standard_hours || isNaN(newOperationData.standard_hours) || parseFloat(newOperationData.standard_hours) <= 0) {
                alert('Standard Hours must be a positive number.');
                return;
            }

            // Determine the URL and method for creating or updating the operation
            const ajaxUrl = operationId ? updateOperationUrl.replace(':id', operationId) : createOperationUrl;
            const ajaxType = operationId ? 'PUT' : 'POST';

            $.ajax({
                url: ajaxUrl,
                type: ajaxType,
                data: {
                    id: operationId,
                    ...newOperationData
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, // CSRF token for security
                success: function(response) {
                    alert(`${operationId ? 'Operation updated' : 'Operation created'} successfully!`);
                    $('#operationModal').remove();
                    loadOperations(); // Reload operations in the dropdown
                },
                error: function(error) {
                    if (error.status === 422) { // Laravel validation errors
                        const errors = error.responseJSON.errors;
                        console.error('Validation Errors:', errors);
                        let errorMessage = 'Validation Errors:\n';
                        Object.values(errors).forEach(errorArray => {
                            errorMessage += errorArray.join(', ') + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert('Error saving operation');
                    }
                    console.error('Error:', error);
                }
            });
        });

        // Close modal when the "Close" button is clicked
        $(document).on('click', '#closeModalButton', function() {
            $('#operationModal').remove();
        });

        // Edit operation button clicked
        $(document).on('click', '.editOperationButton', function() {
            const operationId = $(this).data('id');

            // Fetch the operation details from the server
            $.ajax({
                url: `${listOperationsUrl}/${operationId}`,
                type: 'GET',
                success: function(response) {
                    // Show the modal with the operation data pre-filled
                    showCreateOperationModal(response);
                },
                error: function(error) {
                    alert('Error loading operation for editing');
                    console.error(error);
                }
            });
        });
    });

// Add raw material row
document.getElementById('add-raw-material').addEventListener('click', function() {
    const table = document.querySelector('#raw-materials-table tbody');
    const rowCount = table ? table.rows.length : 0; // Get current row count
    const rawMaterialIndex = rowCount; // Set index to the current row count

    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" name="raw_materials[${rawMaterialIndex}][grade]" required></td>
        <td><input type="text" name="raw_materials[${rawMaterialIndex}][size]" required></td>
        <td><input type="text" name="raw_materials[${rawMaterialIndex}][length]" required></td>
        <td><input type="text" name="raw_materials[${rawMaterialIndex}][hn_bar_tno]" required></td>
        <td><button type="button" class="remove-row">Remove</button></td>
    `;
    table.appendChild(row);
});

// Add consumable row
document.getElementById('add-consumable').addEventListener('click', function() {
    const table = document.querySelector('#consumables-table tbody');
    const rowCount = table ? table.rows.length : 0; // Get current row count
    const consumableIndex = rowCount; // Set index to the current row count

    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" name="consumables[${consumableIndex}][type]" required></td>
        <td><input type="number" name="consumables[${consumableIndex}][quantity]" required></td>
        <td><select name="consumables[${consumableIndex}][uom]" required>
            <option value="kg">kg</option>
            <option value="pcs">pcs</option>
            <option value="length">length</option>
        </select></td>
        <td><button type="button" class="remove-row">Remove</button></td>
    `;
    table.appendChild(row);
});

// Remove row when clicked (delegated event)
document.querySelector('#raw-materials-table tbody').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-row')) {
        event.target.closest('tr').remove();
        // Recalculate the indices after removing a row
        const table = document.querySelector('#raw-materials-table tbody');
        const rows = table.querySelectorAll('tr');
        rows.forEach((row, index) => {
            // Update the input names with the new index
            row.querySelector('input[name^="raw_materials"]').setAttribute('name', `raw_materials[${index}][grade]`);
        });
    }
});

document.querySelector('#consumables-table tbody').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-row')) {
        event.target.closest('tr').remove();
        // Recalculate the indices after removing a row
        const table = document.querySelector('#consumables-table tbody');
        const rows = table.querySelectorAll('tr');
        rows.forEach((row, index) => {
            // Update the input names with the new index
            row.querySelector('input[name^="consumables"]').setAttribute('name', `consumables[${index}][type]`);
        });
    }
});


</script>

</html>
