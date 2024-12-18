<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk View - Active Operations</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-qrcode/1.0/jquery.qrcode.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

<h1>Kiosk View - Active Operations</h1>

<!-- Input for scanning barcodes -->
<div class="barcode-scan">
    <label for="barcodeInput">Scan Operation Barcode:</label>
    <input type="text" id="barcodeInput" placeholder="Scan here" autofocus>
</div>

<table id="activeOperationsTable">
    <thead>
        <tr>
            <th>Operation</th>
            <th>Workstation</th>
            <th>Standard Hours</th>
            <th>Status</th>
            <th>Action</th>
            <th>QR Code</th>
        </tr>
    </thead>
    <tbody>
        <!-- Active operations will be dynamically filled here -->
    </tbody>
</table>

<script>
$(document).ready(function() {
    // Fetch active operations
    function fetchActiveOperations() {
        $.ajax({
            url: '/api/active-operations', // Your API endpoint
            method: 'GET',
            success: function(data) {
                $('#activeOperationsTable tbody').empty();
                data.forEach(function(operation) {
                    $('#activeOperationsTable tbody').append(`
                        <tr>
                            <td>${operation.process}</td>
                            <td>${operation.workstation}</td>
                            <td>${operation.standard_hours}</td>
                            <td>
                                <span class="timer" id="timer-${operation.id}">0:00</span>
                                <button class="start-timer" data-operation-id="${operation.id}">Start</button>
                                <button class="stop-timer" data-operation-id="${operation.id}">Stop</button>
                            </td>
                            <td>
                                <button class="complete-operation" data-operation-id="${operation.id}">Complete</button>
                            </td>
                            <td><div class="qrcode" id="qrcode-${operation.id}"></div></td>
                        </tr>
                    `);

                    // Generate QR code for each operation
                    $('#qrcode-' + operation.id).qrcode({
                        text: operation.barcode, // Assuming you have a barcode field in your operation
                        width: 100,
                        height: 100
                    });
                });
            },
            error: function() {
                alert('Failed to fetch active operations.');
            }
        });
    }

    // Fetch active operations on page load
    fetchActiveOperations();

    // Timer functionality
    $(document).on('click', '.start-timer', function() {
        var button = $(this);
        var operationId = button.data('operation-id');
        button.hide();
        button.next('.stop-timer').show();

        var timerElement = $('#timer-' + operationId);
        var seconds = 0;
        var timerInterval = setInterval(function() {
            seconds++;
            var minutes = Math.floor(seconds / 60);
            var remainingSeconds = seconds % 60;
            timerElement.text(`${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`);
        }, 1000);

        button.data('timer-interval', timerInterval);
    });

    $(document).on('click', '.stop-timer', function() {
        var button = $(this);
        var operationId = button.data('operation-id');
        button.hide();
        button.prev('.start-timer').show();

        var timerInterval = button.data('timer-interval');
        clearInterval(timerInterval);
    });

    // Complete operation functionality
    $(document).on('click', '.complete-operation', function() {
        var operationId = $(this).data('operation-id');
        // Implement AJAX call to update the operation status to 'Completed'
        console.log('Operation ID ' + operationId + ' completed.');
    });

    // Barcode scanning functionality
    $('#barcodeInput').on('keypress', function(e) {
        if (e.which === 13) { // Enter key pressed
            var scannedBarcode = $(this).val();
            // Find the corresponding operation based on the scanned barcode
            // Implement logic to highlight or process the operation if needed
            console.log('Scanned barcode: ' + scannedBarcode);
            $(this).val(''); // Clear the input after scanning
        }
    });
});
</script>

</body>
</html>
