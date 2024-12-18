<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Conformance (CoC) - {{ $finishedOrder->wo_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header .company-logo {
            max-width: 500px;
        }

        .header .company-logo img {
            max-width: 100%;
            height: auto;
            width: 500px; 
        }

        .header .company-details {
            text-align: left;
            max-width: 75%;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin-top: 20px;
        }

        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 20px 0;
        }

        /* Details Section */
        .details {
            margin-bottom: 20px;
            text-align: left;
        }

        .details .entry {
            margin-bottom: 10px;
            display: flex;
        }

        .details .entry strong {
            width: 200px;
            display: inline-block;
            font-weight: bold;
        }

        /* Footer Section */
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #555;
        }

        /* Button Section */
        .button-group {
            text-align: center;
            margin-top: 20px;
        }

        .button-group button {
            margin: 0 10px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
        }

        /* Print Styles */
        @media print {
            .button-group {
                display: none; /* Hide buttons during print */
            }

            body {
                margin: 10mm;
            }

            .header {
                border-bottom: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        @if ($settings->logo_path)
        <div class="company-logo">
            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo">
        </div>
        @endif
        <div class="company-details">
            <p>{{ $settings->company_name ?? 'Your Company Name' }}</p>
            <p>{{ $settings->company_address ?? 'Your Address' }}</p>
            <p>{{ $settings->company_contact ?? 'Your Contact Number' }}</p>
            <p>{{ $settings->company_email ?? 'Your Email Address' }}</p>
        </div>
    </div>

    <!-- Certificate Title and Statement -->
    <h1>Certificate of Conformance</h1>
    <p style="text-align: center;">{{ $settings->coc_statement ?? 'Your COC Statement' }}</p>

    <!-- Details Section -->
    <div class="details">
        <div class="entry">
            <strong>WO Number:</strong> {{ $finishedOrder->wo_number }}
        </div>
        <div class="entry">
            <strong>SO Number:</strong> {{ $finishedOrder->so_number }}
        </div>
        <div class="entry">
            <strong>Customer Name:</strong> {{ $finishedOrder->customer }}
        </div>
        
        <div class="entry">
            <strong>Job Scope:</strong> {{ $finishedOrder->job_scope ?? 'Not Available' }}
        </div>
            
        <div class="entry">
            <p><strong>Part Description:</strong> {{ $finishedOrder->part_description ?? 'Not Available' }}</p>
         </div>   

         <div class="entry">
            <p><strong>Drawing No.:</strong> {{ $finishedOrder->drawing_no ?? 'Not Available' }}</p>
        </div>   

        <div class="entry">
            <strong>Lot Size:</strong> {{ $finishedOrder->lot_size ?? 'Not Available' }}
        </div>
        <div class="entry">
            <strong>Fulfilled Quantity:</strong> {{ $finishedOrder->quantity_fulfilled ?? 'Not Available'}}
        </div>
        <div class="entry">
            <strong>Date Closed:</strong> {{ $finishedOrder->date_closed }}
        </div>
        <div class="entry">
            <strong>Asset Numbers:</strong> 
            @foreach ($finishedOrder->assets as $asset)
                {{ $asset->asset_no }}@if (!$loop->last), @endif
            @endforeach
        </div>
    </div>

    <!-- Raw Material Section -->
    <div class="details">
        <strong>Raw Material:</strong>
        @if($finishedOrder->rawMaterials->isNotEmpty())
            @foreach($finishedOrder->rawMaterials as $rawMaterial)
                <p>{{ $rawMaterial->grade }} (Size: {{ $rawMaterial->size }}; 
                    Length: {{ $rawMaterial->length }}; HN/Bar NO/TNO: {{ $rawMaterial->hn_bar_no_tno }})</p>
            @endforeach
        @else
            <p>Not Available</p>
        @endif
    </div>

    <!-- Consumables Section -->
    <div class="details">
        <strong>Consumables:</strong>
        @if($finishedOrder->consumables->isNotEmpty())
            @foreach($finishedOrder->consumables as $consumable)
                <p>{{ $consumable->type }} (Quantity: {{ $consumable->quantity }}; UOM: {{ $consumable->uom }})</p>
            @endforeach
        @else
            <p>Not Available</p>
        @endif
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>Generated by: {{ auth()->check() ? auth()->user()->name : 'Unknown User' }}</p>
    </div>

    <!-- Button Group -->
    <div class="button-group">
        <button onclick="window.location.href = '{{ route('production.index') }}';">Back</button>
        <button onclick="window.print();">Print</button>
        <button onclick="window.location.href = '{{ route('finishedOrders.generateReport', ['wo_number' => $finishedOrder->wo_number]) }}';">Download PDF</button>
    </div>
</body>
</html>
