<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Process Control Router for WO {{ $data->wo_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100%;
            height: auto;
            width: 500px; /* You can adjust the size as needed */
        }
        header .company-details {
         text-align: left; /* Align text to the left */
         flex: 1; /* Ensure the details take up remaining space */
         padding-left: 20px; /* Add some padding on the left */
         margin-left: auto; /* Push the details to the right */
        }
        .header .company-details p {
            margin: 5px 0;
        }
        .header h1 {
            margin-top: 10px;
        }
        .order-details,
        .customer-details {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .order-details > div,
        .customer-details > div {
            width: 100%; /* This ensures both columns take up equal space */
            margin: 0;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .footer p {
            font-size: 14px;
        }
        .footer div {
            margin-top: 20px;
        }

        hr {
        width: 100%; /* Make the <hr> span the full width of the container */
        border: 0; /* Remove default border */
        border-top: 2px solid #000; /* Set the desired line style */
        margin: 20px 0; /* Optional: add some space above and below */
        }


          /* Hide specific buttons when generating PDF or printing */
        @media print {
        .btn-info, .btn-primary {
            display: none !important;
        }
        /* Hide buttons when exporting or printing */
        .hide-export-buttons .btn-info, .hide-export-buttons .btn-primary {
            display: none !important;
        }
    }

    </style>
</head>
<body>

<div style="text-align: right; margin-top: 20px;">
    <a href="{{ route('production.showfinished', $data->wo_number) }}" class="btn btn-secondary" style="padding: 10px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
        Back to Order Details
    </a>
    <a href="{{ route('production.export.pdf', $data->wo_number) }}" class="btn btn-primary" style="padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">
        Export to PDF
    </a>
    <button onclick="window.print()" class="btn btn-info" style="padding: 10px 15px; background-color: #17a2b8; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">
        Print
    </button>
</div>

<div class="container">
    <!-- Header Section -->
    <div class="header">
        @if ($settings->logo_path)
        <div>
            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Current Logo">
        </div>
        @endif
        <div class="company-details">
            <p>{{ $settings->company_name ?? 'Oilfield Services & Supplies Pte Ltd' }}</p>
            <p>{{ $settings->company_address ?? 'No.20 Tuas Ave 10, 639144 Singapore' }}</p>
            <p>{{ $settings->company_contact ?? 'Phone No. (65) 65425933 Fax No. (65) 65426355' }}</p>
            <p>{{ $settings->company_email ?? 'OSS_SG_SALES@oss-grp.com' }}</p>
        </div>
    </div>

    <hr>
    <h1>Process Control Router</h1>

    <!-- Order and Customer Details -->
    <div class="order-details">
        <div>
            <p><strong>Customer:</strong> {{ $data->customer ?? 'Not Available' }}</p>
            <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($data->order_date)->format('F j, Y') }}</p>
            <p><strong>Required Date:</strong> {{ \Carbon\Carbon::parse($data->required_date)->format('F j, Y') }}</p>
           
            
            <div style="width: 200%; text-align:left; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 10px 0; margin-left: 0; margin-right: 0;">
                 <p style="display: inline-block; padding: 0 10px;">
                 <strong>Job Scope:</strong> {{ $data->job_scope ?? 'Not Available' }}
                </p>
            </div>
            
        
            <p><strong>Part Description:</strong> {{ $data->part_description ?? 'Not Available' }}</p>
            
            <p><strong>Drawing No.:</strong> {{ $data->drawing_no ?? 'Not Available' }}</p>
            <p><strong>Asset No:</strong> {{ $data->assets->pluck('asset_no')->implode(', ') ?? 'Not Available' }}</p>
            <p><strong>Lot Size:</strong> {{ $data->lot_size ?? 'Not Available' }}</p>

            <!-- Raw Material Section -->
            <p><strong>Raw Material:</strong></p>
            <p>
            @if($data->rawMaterials->isNotEmpty())
                @foreach($data->rawMaterials as $rawMaterial)
                    {{ $rawMaterial->grade }} 
                    (Size: {{ $rawMaterial->size }}; 
                    Length: {{ $rawMaterial->length }}; 
                    HN/Bar NO/TNO: {{ $rawMaterial->hn_bar_tno }})
                         <br> <!-- New line for next raw material -->
                    @endforeach
                 @else
                    Not Available
                @endif
            </p>

            <!-- Consumables Section -->
            <p><strong>Consumables:</strong></p>
            <p>
                @if($data->consumables->isNotEmpty())
                     @foreach($data->consumables as $consumable)
                    {{ $consumable->type }} 
                    (Quantity: {{ $consumable->quantity }}; 
                     UOM: {{ $consumable->uom }})
                    <br> <!-- New line for next consumable -->
                @endforeach
             @else
                 Not Available
            @endif
            </p>

        </div>

        <div>
            
            <p><strong>WO Number:</strong> {{ $data->wo_number }}</p>
            <p><strong>SO Number:</strong> {{ $data->so_number ?? 'Not Available' }}</p>
            <p><strong>Customer PO Number:</strong> {{ $data->cust_po_number ?? 'Not Available' }}</p>
        </div>
    </div>

    <!-- QR Code Section -->
    <div>
        @php
            // Concatenate all details into a single string for QR Code generation
            $orderDetails = "WO Number: " . $data->wo_number . "\n"
                . "SO Number: " . ($data->so_number ?? 'Not Available') . "\n"
                . "Customer PO Number: " . ($data->cust_po_number ?? 'Not Available') . "\n"
                . "Customer: " . ($data->customer ?? 'Not Available') . "\n"
                . "Order Date: " . (\Carbon\Carbon::parse($data->order_date)->format('F j, Y')) . "\n"
                . "Required Date: " . (\Carbon\Carbon::parse($data->required_date)->format('F j, Y')) . "\n"
                . "Job Scope: " . ($data->job_scope ?? 'Not Available') . "\n"
                . "Part Description: " . ($data->part_description ?? 'Not Available') . "\n"
                . "Drawing No.: " . ($data->drawing_no ?? 'Not Available') . "\n"
                . "Asset No: " . ($data->assets->pluck('asset_no')->implode(', ') ?? 'Not Available') . "\n"
                . "Lot Size: " . ($data->lot_size ?? 'Not Available');
        @endphp
        {!! QrCode::size(100)->generate($orderDetails) !!}
    </div>

    <!-- Operations Section -->
    <h2>Operations</h2>
    <table>
        <thead>
            <tr>
                <th>Operation Number</th>
                <th>Process</th>
                <th>Workstation</th>
                <th>Standard Hours</th>
                <th>Signature</th>
                <th>QR Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['operations'] as $index => $operation)
                <tr>
                    <td>{{ 100 + $index }}</td> 
                    <td>{{ $operation->process ?? 'Not Available' }}</td>
                    <td>{{ $operation->workstation ?? 'Not Available' }}</td>
                    <td>{{ $operation->standard_hours ?? 'Not Available' }}</td>
                    <td>_________</td>
                    <td>{!! QrCode::size(70)->generate($data->wo_number . '#' . (100 + $index)) !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        <p>{{ $settings->pcr_footer ?? 'Your Footer Text' }}</p>

        <div>
            <div style="display: flex; justify-content: space-between; width: 100%; padding-bottom: 10px;">
                <p><strong>Prepared By:</strong> <span style="border-bottom: 1px solid #000; width: 150px;"></span></p>
                <p><strong>Reviewed By:</strong> <span style="border-bottom: 1px solid #000; width: 150px;"></span></p>
                <p><strong>Released By:</strong> <span style="border-bottom: 1px solid #000; width: 150px;"></span></p>
            </div>

            <div style="display: flex; justify-content: space-between;">
                <p><strong>Verified By (QC):</strong> <span style="border-bottom: 1px solid #000; width: 150px;"></span></p>
                <p><strong>Approved By (QA):</strong> <span style="border-bottom: 1px solid #000; width: 150px;"></span></p>
            </div>

            <div style="text-align: right;">
                <p>FR####</p>
            </div>
        </div>
    </div>
</div>

<script>
    function printPage() {
        window.print();
    }
</script>

</body>
</html>
