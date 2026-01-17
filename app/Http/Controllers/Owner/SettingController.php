<?php
// File: app/Http/Controllers/Owner/SettingController.php
// Jalankan: php artisan make:controller Owner/SettingController

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display settings page with tabs
     */
    public function index(Request $request)
    {
        // Get active tab from query string, default to 'general'
        $activeTab = $request->get('tab', 'general');

        // Get settings grouped by category
        $generalSettings = Setting::where('group', 'general')->get()->keyBy('key');
        $systemSettings = Setting::where('group', 'system')->get()->keyBy('key');
        $landingSettings = Setting::where('group', 'landing_page')->get()->keyBy('key');

        return view('owner.settings.index', compact(
            'generalSettings',
            'systemSettings',
            'landingSettings',
            'activeTab'
        ));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'email', 'max:255'],
            'company_phone' => ['required', 'string', 'max:20'],
            'company_address' => ['required', 'string'],
            'company_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'general');
        }

        try {
            // Update text settings
            Setting::set('company_name', $request->company_name);
            Setting::set('company_email', $request->company_email);
            Setting::set('company_phone', $request->company_phone);
            Setting::set('company_address', $request->company_address);

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                // Delete old logo if exists
                $oldLogo = setting('company_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }

                // Upload new logo
                $logoPath = $request->file('company_logo')->store('settings', 'public');
                Setting::set('company_logo', $logoPath);
            }

            return redirect()->route('owner.settings.index', ['tab' => 'general'])
                ->with('success', 'Pengaturan umum berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage())
                ->withInput()->with('activeTab', 'general');
        }
    }

    /**
     * Update system settings
     */
    public function updateSystem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'min_order_amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            // Bank 1
            'bank_name_1' => ['nullable', 'string', 'max:100'],
            'bank_account_number_1' => ['nullable', 'string', 'max:50'],
            'bank_account_name_1' => ['nullable', 'string', 'max:100'],
            // Bank 2
            'bank_name_2' => ['nullable', 'string', 'max:100'],
            'bank_account_number_2' => ['nullable', 'string', 'max:50'],
            'bank_account_name_2' => ['nullable', 'string', 'max:100'],
            // Bank 3
            'bank_name_3' => ['nullable', 'string', 'max:100'],
            'bank_account_number_3' => ['nullable', 'string', 'max:50'],
            'bank_account_name_3' => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'system');
        }

        try {
            Setting::set('tax_rate', $request->tax_rate);
            Setting::set('shipping_cost', $request->shipping_cost);
            Setting::set('min_order_amount', $request->min_order_amount);
            Setting::set('currency', $request->currency);

            // Save bank settings
            Setting::set('bank_name_1', $request->bank_name_1);
            Setting::set('bank_account_number_1', $request->bank_account_number_1);
            Setting::set('bank_account_name_1', $request->bank_account_name_1);

            Setting::set('bank_name_2', $request->bank_name_2);
            Setting::set('bank_account_number_2', $request->bank_account_number_2);
            Setting::set('bank_account_name_2', $request->bank_account_name_2);

            Setting::set('bank_name_3', $request->bank_name_3);
            Setting::set('bank_account_number_3', $request->bank_account_number_3);
            Setting::set('bank_account_name_3', $request->bank_account_name_3);

            return redirect()->route('owner.settings.index', ['tab' => 'system'])
                ->with('success', 'Pengaturan sistem berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage())
                ->withInput()->with('activeTab', 'system');
        }
    }

    /**
     * Update landing page settings
     */
    public function updateLanding(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'landing_hero_title' => ['required', 'string', 'max:255'],
            'landing_hero_subtitle' => ['required', 'string'],
            'landing_hero_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'landing_about_title' => ['required', 'string', 'max:255'],
            'landing_about_content' => ['required', 'string'],
            'landing_about_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'landing_whatsapp' => ['nullable', 'string', 'max:20'],
            'landing_instagram' => ['nullable', 'string', 'max:100'],
            'landing_facebook' => ['nullable', 'string', 'max:100'],
            'landing_email' => ['nullable', 'email', 'max:255']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'landing');
        }

        try {
            // Update text settings
            Setting::set('landing_hero_title', $request->landing_hero_title);
            Setting::set('landing_hero_subtitle', $request->landing_hero_subtitle);
            Setting::set('landing_about_title', $request->landing_about_title);
            Setting::set('landing_about_content', $request->landing_about_content);
            Setting::set('landing_whatsapp', $request->landing_whatsapp);
            Setting::set('landing_instagram', $request->landing_instagram);
            Setting::set('landing_facebook', $request->landing_facebook);
            Setting::set('landing_email', $request->landing_email);

            // Handle hero image upload
            if ($request->hasFile('landing_hero_image')) {
                $oldImage = setting('landing_hero_image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $heroPath = $request->file('landing_hero_image')->store('settings', 'public');
                Setting::set('landing_hero_image', $heroPath);
            }

            // Handle about image upload
            if ($request->hasFile('landing_about_image')) {
                $oldImage = setting('landing_about_image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $aboutPath = $request->file('landing_about_image')->store('settings', 'public');
                Setting::set('landing_about_image', $aboutPath);
            }

            return redirect()->route('owner.settings.index', ['tab' => 'landing'])
                ->with('success', 'Pengaturan landing page berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage())
                ->withInput()->with('activeTab', 'landing');
        }
    }

    /**
     * Delete uploaded image
     */
    public function deleteImage(Request $request)
    {
        $key = $request->input('key');

        try {
            $imagePath = setting($key);

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            Setting::set($key, null);

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar: ' . $e->getMessage()
            ], 500);
        }
    }
}
