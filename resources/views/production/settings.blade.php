<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <style>
        /* Your existing styles remain the same */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        h1 {
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="file"],
        form textarea {
            width: 100%;
            max-width: 600px;
            padding: 0.5rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form textarea {
            height: 100px; /* Adjust height as needed */
            resize: vertical; /* Allow users to resize the textarea vertically */
        }

        form button[type="submit"] {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .alert {
            color: red;
            font-size: 0.9rem;
        }

        .home-link {
            margin-bottom: 20px;
        }

        .home-link a {
            display: inline-block;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .home-link a:hover {
            background-color: #5a6268;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Update Company Settings</h1>

    <!-- Home Button -->
    <div class="home-link">
        <a href="{{ route('production.index') }}" class="button-theme">Back to Home</a>
    </div>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="company_name">Company Name:</label>
        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $settings->company_name) }}">

        <label for="company_address">Company Address:</label>
        <input type="text" name="company_address" id="company_address" value="{{ old('company_address', $settings->company_address) }}">

        <label for="company_contact">Company Contact:</label>
        <input type="text" name="company_contact" id="company_contact" value="{{ old('company_contact', $settings->company_contact) }}">

        <label for="company_email">Company Emails:</label>
        <input type="text" 
               name="company_email" 
               id="company_email"
               value="{{ old('company_email', $settings->company_email ?? '') }}" 
               required
               placeholder="example@oss.com / example2@oss.com"
               pattern="([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})(\s*\/\s*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*"
               title="Please enter email addresses separated by slashes (e.g. example@oss.com / example2@oss.com)">
        @error('company_email')
            <span class="alert alert-danger">{{ $message }}</span>
        @enderror

        <label for="pcr_footer">PCR Footer:</label>
        <textarea name="pcr_footer" id="pcr_footer">{{ old('pcr_footer', $settings->pcr_footer) }}</textarea>

        <label for="coc_statement">CoC Statement:</label>
        <textarea name="coc_statement" id="coc_statement">{{ old('coc_statement', $settings->coc_statement) }}</textarea>

         <!-- Add the Prefix field -->
         <label for="Prefix">Prefix:</label>
        <input type="text" name="Prefix" id="Prefix" value="{{ old('Prefix', $settings->Prefix ?? '') }}">

        <label for="logo">Company Logo:</label>
        <input type="file" name="logo" id="logo">

        @if ($settings->logo_path)
        <div>
            <p>Current Logo:</p>
            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Current Logo">
        </div>
        @endif

       
        <button type="submit">Save Settings</button>
    </form>
</body>
</html>
