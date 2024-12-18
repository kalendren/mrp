<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>Production Management</title>

    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    

    </style>
</head>
<body>

    <header>
        @if ($settings->logo_path)
            <div>
                <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Current Logo" width="500">
            </div>
        @endif
        <p>{{ $settings->company_name ?? 'Your Company Name' }}</p>
        <p>{{ $settings->company_address ?? 'Your Address' }}</p>
        <hr>
        <h1>Production Management System</h1>
    </header>

    <!-- Navigation Bar -->
    <div class="nav-links">
        <a href="{{ route('production.create') }}" class="button-theme">
            <i class="fas fa-plus-circle"></i>Create Production Order
        </a>
        <a href="{{ route('production.released') }}" class="button-theme">
            <i class="fas fa-th-list"></i>Released Productions
        </a>
        <a href="{{ route('production.kiosk') }}" class="button-theme">
            <i class="fas fa-tv"></i>Kiosk View
        </a>
        <a href="{{ route('production.finished') }}" class="button-theme">
            <i class="fas fa-file"></i>Finished Order
        </a>
        <a href="{{ route('admin.settings.edit') }}" class="button-theme">
            <i class="fas fa-cogs"></i>Go to Settings
        </a>
    </div>
    <br>


    <!-- Tile Boxes for Released and Finished Work Orders -->
    <div class="tile-boxes">
        

        <div class="tile-box">
            <h3>Released Orders</h3>
            <p>{{ $releasedOrderCount }} Orders</p>
        </div>

        <div class="tile-box">
            <h3>Finished Orders</h3>
            <p>{{ $finishedOrderCount }} Orders</p>
        </div>
    </div>
    <br>

    <footer>
        <p>&copy; 2024 Production Management System | All rights reserved.</p>
    </footer>

</body>
</html>
