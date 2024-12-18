<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    // Show settings form
    public function edit()
    {
        // Retrieve the settings record or initialize a new one
        $settings = Setting::first() ?? new Setting();
        return view('production.settings', compact('settings'));
    }

    // Update settings
    public function update(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_contact' => 'nullable|string|max:100',
            'company_email' => 'nullable|string|max:500', // String to allow additional processing
            'pcr_footer' => 'nullable|string|max:1000',
            'coc_statement' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // File size max 2MB
            'Prefix' => 'nullable|string|max:10',
        ]);

        // Get the existing settings record or create a new one
        $settings = Setting::firstOrNew([]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if (!empty($settings->logo_path) && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $settings->logo_path = $logoPath;
        }

        // Update fields
        $settings->company_name = $request->input('company_name');
        $settings->company_address = $request->input('company_address');
        $settings->company_contact = $request->input('company_contact');

        // Process and validate company emails
        if ($request->has('company_email')) {
            $emails = array_map('trim', explode('/', $request->input('company_email', '')));

            // Validate each email address
            foreach ($emails as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return redirect()->back()->withErrors([
                        'company_email' => 'Invalid email format: ' . $email,
                    ]);
                }
            }

            // Store emails as a slash-separated string
            $settings->company_email = implode(' / ', $emails);
        }

        // Update additional fields
        $settings->pcr_footer = $request->input('pcr_footer');
        $settings->coc_statement = $request->input('coc_statement');

        // Update the Prefix field
        if ($request->has('Prefix')) {
            $settings->Prefix = $request->input('Prefix');
        }

        // Save the updated settings and handle errors
        try {
            $settings->save();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Failed to save settings. Please try again.',
            ]);
        }

        // Redirect with success message
        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully.');
    }
}
