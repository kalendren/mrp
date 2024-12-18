<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finished Orders</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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

<h1>Finished Orders</h1>

<div class="home-link">
    <a href="{{ route('production.index') }}" class="button-theme">Back to Home</a>
</div>

<table id="finishedOrdersTable" class="display">
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
            <th>Lot Size</th>
            <th>Date Closed</th>
            <th>Closed By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($finishedOrders as $order)
        <tr>
            <!-- Make the WO Number clickable to route to the detailed view -->
            <td><a href="{{ route('production.showfinished', $order->wo_number) }}">{{ $order->wo_number }}</a></td>
            <td>{{ $order->so_number }}</td>
            <td>{{ $order->cust_po_number }}</td>
            <td>{{ $order->customer }}</td>
            <td>{{ $order->order_date }}</td>
            <td>{{ $order->required_date }}</td>
            <td>{{ $order->job_scope }}</td>
            <td>{{ $order->part_description }}</td>
            <td>{{ $order->drawing_no }}</td>
            <td>{{ $order->lot_size }}</td>
            <td>{{ $order->date_closed }}</td>
            <td>{{ $order->closed_by }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
$(document).ready(function() {
    $('#finishedOrdersTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        paging: true,
        searching: true,
        ordering: true,
        responsive: true
    });
});
</script>

</body>
</html>
