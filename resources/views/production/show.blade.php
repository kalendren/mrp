<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Order View</title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
        }

        hr {
            margin: 20px 0;
            border: 0;
            border-top: 2px solid #ddd;
        }

        /* Styling for form and input fields */
        .input-container {
            margin-bottom: 15px;
        }

        .input-container label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .input-container input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .input-container input[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        .alert {
            padding: 10px;
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

        /* Button and link styles */
        .button-theme {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .button-theme:hover {
            background-color: #0056b3;
        }

        /* Align buttons and links in one row */
        .home-link, .generate-report, .transfer-order {
            display: inline-block;
            margin-right: 10px;
        }

        /* Dropdown button styling */
        .dropdown {
            display: inline-block;
            position: relative;
        }

        .dropdown-toggle {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            min-width: 160px;
            z-index: 1000;
        }

        .dropdown-menu li {
            list-style-type: none;
        }

        .dropdown-menu li button {
            background: none;
            border: none;
            padding: 10px;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-menu li button:hover {
            background-color: #f1f1f1;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f8f9fa;
        }

        /* Hide Edit button and show Save button only after clicking Edit */
        #saveButton {
            display: none;
        }

        .dropdown-menu.show {
             display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Display success or error messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <h1>Production Order View</h1>
        <hr>

        <!-- Back Button and other action buttons in one row -->
        <div class="home-link">
            <a href="{{ route('production.released') }}" class="button-theme">Back to Released Productions</a>
        </div>

        <div class="generate-report">
            <a href="{{ route('production.generateReport', ['wo_number' => $data['wo_number']]) }}" class="button-theme">Generate PCR</a>
        </div>
        
        <div class="transfer-order">
            <button type="button" class="button-theme" onclick="openModal()">Transfer to Finished Order</button>
        </div>

        <!-- Edit Options Dropdown -->
        <div class="dropdown">
            <button class="button-theme dropdown-toggle" type="button" id="dropdownMenuButton" aria-expanded="false">
                Edit Options
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><button class="dropdown-item" type="button" id="editButton">
                    <i class="fa fa-edit"></i> Edit
                </button></li>
                <li><button class="dropdown-item" type="submit" form="productionForm" id="saveButton">
                    <i class="fa fa-save"></i> Save Changes
                </button></li>
            </ul>
        </div>

        <!-- Transfer Modal -->
        <div id="transferModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="closeModal()">&times;</span>
                <h2>Transfer to Finished Order</h2>
                <form action="{{ route('production.transferToFinished', $data['wo_number']) }}" method="POST">
                    @csrf
                    <input type="hidden" name="generate_report" value="0">
                    <label for="generate_report">Generate Report:</label>
                    <input type="checkbox" id="generate_report" name="generate_report" value="1">
                    <div class="input-container">
                        <label for="quantity">Quantity Fulfilled:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="{{ $data['lot_size'] }}" required>
                    </div>
                    <button type="submit" class="button-theme">Confirm Transfer</button>
                </form>
            </div>
        </div>

        <!-- Production Order Form -->
        <form method="POST" action="{{ route('production.update', $data['wo_number']) }}" id="productionForm">
            @csrf
            @method('PUT')

            <!-- Form Fields -->
            <div class="input-container">
                <label for="wo_number">WO Number:</label>
                <input type="text" id="wo_number" name="wo_number" value="{{ $data['wo_number'] ?? 'Not Available' }}" readonly>
            </div>

            <div class="input-container">
                <label for="so_number">SO Number:</label>
                <input type="text" id="so_number" name="so_number" value="{{ old('so_number', $data['so_number'] ?? 'Not Available') }}" readonly>
            </div>

            <div class="input-container">
                <label for="cust_po_number">Cust PO Number:</label>
                <input type="text" id="cust_po_number" name="cust_po_number" value="{{ old('cust_po_number', $data['cust_po_number'] ?? 'Not Available') }}" disabled>
            </div>

            <div class="input-container">
                <label for="customer">Customer:</label>
                <input type="text" id="customer" name="customer" value="{{ old('customer', $data['customer'] ?? 'Not Available') }}" disabled>
            </div>

            <div class="input-container">
                <label for="order_date">Order Date:</label>
                <input type="date" id="order_date" name="order_date" value="{{ old('order_date', $data['order_date'] ?? 'Not Available') }}" disabled>
            </div>

            <div class="input-container">
                <label for="required_date">Required Date:</label>
                <input type="date" id="required_date" name="required_date" value="{{ old('required_date', $data['required_date'] ?? 'Not Available') }}" disabled>
            </div>

            <div class="input-container">
                <label for="job_scope">Job Scope:</label>
                <input type="text" id="job_scope" name="job_scope" value="{{ old('job_scope', $data['job_scope'] ?? 'Not Available') }}" disabled>
            </div>

            <div class="input-container">
                <label for="part_description">Part Description:</label>
                <input type="text" id="part_description" name="part_description" value="{{ old('part_description', $data['part_description'] ?? 'Not Available') }}" disabled>
            </div>

            <div class="input-container">
                <label for="drawing_no">Drawing No.:</label>
                <input type="text" id="drawing_no" name="drawing_no" value="{{ old('drawing_no', $data['drawing_no'] ?? 'Not Available') }}" disabled>
            </div>

            <div class="input-container">
                <label for="asset_no">Asset No:</label>
                <input type="text" id="asset_no" name="asset_no" value="{{ $data['asset_no'] ?? 'Not Available' }}" readonly>
            </div>

            <div class="input-container">
                <label for="lot_size">Lot Size:</label>
                <input type="number" id="lot_size" name="lot_size" value="{{ old('lot_size', $data['lot_size'] ?? 'Not Available') }}" disabled>
            </div>

            <div class="input-container">
                <label for="quantity_fulfilled">Quantity Fulfilled:</label>
                <input type="number" id="quantity_fulfilled" name="quantity_fulfilled" value="{{ old('quantity_fulfilled', $data['quantity_fulfilled'] ?? 'Not Available') }}" readonly>
            </div>

            </form>

        <!-- Raw Materials -->
        <h2>Raw Materials</h2>
        @if(!empty($data['raw_materials']))
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Grade</th>
                            <th>Size</th>
                            <th>Length</th>
                            <th>HN/Bar/TNO</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data['raw_materials'] as $raw_material)
                        <tr>
                            <td>{{ $raw_material['grade'] ?? 'Not Available' }}</td>
                            <td>{{ $raw_material['size'] ?? 'Not Available' }}</td>
                            <td>{{ $raw_material['length'] ?? 'Not Available' }}</td>
                            <td>{{ $raw_material['hn_bar_tno'] ?? 'Not Available' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No raw materials data available or failed to fetch data.</p>
        @endif

        <!-- Consumables -->
        <h2>Consumables</h2>
        @if(!empty($data['consumables']))
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>UOM</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data['consumables'] as $consumable)
                        <tr>
                            <td>{{ $consumable['type'] ?? 'Not Available' }}</td>
                            <td>{{ $consumable['quantity'] ?? 'Not Available' }}</td>
                            <td>{{ $consumable['uom'] ?? 'Not Available' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No consumables data available or failed to fetch data.</p>
        @endif

    </div>


        </form>

        <!-- Operations Data -->
        <h2>Operations Data</h2>
        @if(!empty($data['operations']))
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Operation Number</th>
                            <th>Process</th>
                            <th>Work Station</th>
                            <th>Standard Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data['operations'] as $index => $operation)
                        <tr>
                            <td>{{ 100 + $index }}</td> 
                            <td>{{ $operation['process'] ?? 'Not Available' }}</td>
                            <td>{{ $operation['workstation'] ?? 'Not Available' }}</td>
                            <td>{{ $operation['standard_hours'] ?? 'Not Available' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No operations data available or failed to fetch data.</p>
        @endif

        <!-- Assets Data -->
        <h2>Assets</h2>
        @if(!empty($data['assets']) && count($data['assets']) > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Asset No</th>
                            <th>Generated By System</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['assets'] as $asset)
                            <tr>
                                <td>{{ $asset->asset_no ?? 'Not Available' }}</td>
                                <td>{{ $asset->generated_by_system ? 'Yes' : 'No' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No assets data available or failed to fetch data.</p>
        @endif

    </div>

    <script>
      // Dropdown functionality
document.getElementById('dropdownMenuButton').addEventListener('click', function(event) {
    // Prevent the page from reloading on click
    event.stopPropagation();
    // Toggle the dropdown menu visibility
    document.querySelector('.dropdown-menu').classList.toggle('show');
});

// Close the dropdown menu if the user clicks outside
window.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown')) {
        document.querySelector('.dropdown-menu').classList.remove('show');
    }
});

// Enable editing when clicking 'Edit'
document.getElementById('editButton').addEventListener('click', function() {
    let inputs = document.querySelectorAll('#productionForm input');
    inputs.forEach(input => input.disabled = false);
    document.getElementById('saveButton').style.display = 'block';
});
// Modal Handling for Transfer to Finished Order
function openModal() {
    document.getElementById("transferModal").style.display = "block";
}

function closeModal() {
    document.getElementById("transferModal").style.display = "none";
}

// Close modal on outside click
window.onclick = function(event) {
    let modal = document.getElementById("transferModal");
    if (event.target == modal) {
        closeModal();
    }
}

    </script>
</body>
</html>
