<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Released Productions</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Link to your custom CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.3/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.3/js/buttons.print.min.js"></script>
</head>
<body>

@if(session('success'))
    <div class="alert alert-success timeout-message">
        {{ session('success') }}
    </div>
@endif

<h1>Released Productions</h1>

<div class="home-link">
    <a href="{{ route('production.index') }}" class="button-theme">Back to Home</a>
</div>

<!-- Table to display released production orders -->
<table id="releasedProductionsTable" class="display">
    <thead>
        <tr>
            <th>WO Number</th>
            <th>SO Number</th>
            <th>Cust PO Number</th>
            <th>Customer</th>
            <th>Order Date</th>
            <th>Required Date</th>
            <th>Job Scope</th>
            <th>Part Description</th>
            <th>Drawing No.</th>
            <th>Operations</th>
        </tr>
    </thead>
    <tbody>
        @foreach($productionOrders as $order)
        <tr>
            <td><a href="{{ route('production.show', $order->wo_number) }}">{{ $order->wo_number }}</a></td>
            <td>{{ $order->so_number }}</td>
            <td>{{ $order->cust_po_number }}</td>
            <td>{{ $order->customer }}</td>
            <td>{{ $order->order_date }}</td>
            <td>{{ $order->required_date }}</td>
            <td>{{ $order->job_scope }}</td>
            <td>{{ $order->part_description }}</td>
            <td>{{ $order->drawing_no }}</td>
            <td>
                <ul>
                    @foreach($order->operations as $operation)
                    <li>{{ $operation->process }} - {{ $operation->workstation }} ({{ $operation->standard_hours }} hrs)</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#releasedProductionsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging: true,
        searching: true,
        ordering: true,
        responsive: true
    });
});
    // Wait for the page to load
    document.addEventListener('DOMContentLoaded', function () {
        // Find the success message
        var successMessage = document.querySelector('.timeout-message');
        
        if (successMessage) {
            // Set a timeout to hide the message after 5 seconds (5000 milliseconds)
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 5000); // Adjust time as necessary (in milliseconds)
        }
    });
</script>


</body>
</html>
