<?php
// File: app/Http/Controllers/Owner/LandingSettingController.php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LandingSlide;
use App\Models\Partner;
use App\Models\Promo;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LandingSettingController extends Controller
{
    /**
     * Display landing settings page
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'slides');

        // Get slides
        $slides = LandingSlide::ordered()->get();

        // Get partners
        $partners = Partner::ordered()->get();

        // Get promos
        $promos = Promo::ordered()->get();

        // Get landing page settings
        $landingSettings = Setting::where('group', 'landing_page')->get()->keyBy('key');

        return view('owner.landing-settings.index', compact(
            'activeTab',
            'slides',
            'partners',
            'promos',
            'landingSettings'
        ));
    }

    // =============================================
    // SLIDES MANAGEMENT
    // =============================================

    /**
     * Store a new slide
     */
    public function storeSlide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'slides');
        }

        try {
            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('landing/slides', 'public');
            }

            $data['is_active'] = $request->has('is_active');

            LandingSlide::create($data);

            return redirect()->route('owner.landing-settings.index', ['tab' => 'slides'])
                ->with('success', 'Slide berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan slide: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update a slide
     */
    public function updateSlide(Request $request, LandingSlide $slide)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'slides');
        }

        try {
            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($slide->image && Storage::disk('public')->exists($slide->image)) {
                    Storage::disk('public')->delete($slide->image);
                }
                $data['image'] = $request->file('image')->store('landing/slides', 'public');
            }

            $data['is_active'] = $request->has('is_active');

            $slide->update($data);

            return redirect()->route('owner.landing-settings.index', ['tab' => 'slides'])
                ->with('success', 'Slide berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui slide: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a slide
     */
    public function destroySlide(LandingSlide $slide)
    {
        try {
            // Delete image
            if ($slide->image && Storage::disk('public')->exists($slide->image)) {
                Storage::disk('public')->delete($slide->image);
            }

            $slide->delete();

            return redirect()->route('owner.landing-settings.index', ['tab' => 'slides'])
                ->with('success', 'Slide berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus slide: ' . $e->getMessage());
        }
    }

    // =============================================
    // PARTNERS MANAGEMENT
    // =============================================

    /**
     * Store a new partner
     */
    public function storePartner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'partners');
        }

        try {
            $data = $validator->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo')->store('landing/partners', 'public');
            }

            $data['is_active'] = $request->has('is_active');

            Partner::create($data);

            return redirect()->route('owner.landing-settings.index', ['tab' => 'partners'])
                ->with('success', 'Partner berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan partner: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update a partner
     */
    public function updatePartner(Request $request, Partner $partner)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'partners');
        }

        try {
            $data = $validator->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($partner->logo && Storage::disk('public')->exists($partner->logo)) {
                    Storage::disk('public')->delete($partner->logo);
                }
                $data['logo'] = $request->file('logo')->store('landing/partners', 'public');
            }

            $data['is_active'] = $request->has('is_active');

            $partner->update($data);

            return redirect()->route('owner.landing-settings.index', ['tab' => 'partners'])
                ->with('success', 'Partner berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui partner: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a partner
     */
    public function destroyPartner(Partner $partner)
    {
        try {
            // Delete logo
            if ($partner->logo && Storage::disk('public')->exists($partner->logo)) {
                Storage::disk('public')->delete($partner->logo);
            }

            $partner->delete();

            return redirect()->route('owner.landing-settings.index', ['tab' => 'partners'])
                ->with('success', 'Partner berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus partner: ' . $e->getMessage());
        }
    }

    // =============================================
    // SECTION SETTINGS
    // =============================================

    /**
     * Update section settings (About, Contact, Promo, Product titles)
     */
    public function updateSections(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'landing_about_title' => ['required', 'string', 'max:255'],
            'landing_about_content' => ['required', 'string'],
            'landing_about_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'landing_contact_title' => ['required', 'string', 'max:255'],
            'landing_contact_subtitle' => ['nullable', 'string'],
            'landing_promo_title' => ['required', 'string', 'max:255'],
            'landing_promo_subtitle' => ['nullable', 'string'],
            'landing_product_title' => ['required', 'string', 'max:255'],
            'landing_product_subtitle' => ['nullable', 'string'],
            'landing_whatsapp' => ['nullable', 'string', 'max:20'],
            'landing_instagram' => ['nullable', 'string', 'max:100'],
            'landing_facebook' => ['nullable', 'string', 'max:100'],
            'landing_email' => ['nullable', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'sections');
        }

        try {
            // Update text settings
            Setting::set('landing_about_title', $request->landing_about_title);
            Setting::set('landing_about_content', $request->landing_about_content);
            Setting::set('landing_contact_title', $request->landing_contact_title);
            Setting::set('landing_contact_subtitle', $request->landing_contact_subtitle);
            Setting::set('landing_promo_title', $request->landing_promo_title);
            Setting::set('landing_promo_subtitle', $request->landing_promo_subtitle);
            Setting::set('landing_product_title', $request->landing_product_title);
            Setting::set('landing_product_subtitle', $request->landing_product_subtitle);
            Setting::set('landing_whatsapp', $request->landing_whatsapp);
            Setting::set('landing_instagram', $request->landing_instagram);
            Setting::set('landing_facebook', $request->landing_facebook);
            Setting::set('landing_email', $request->landing_email);

            // Handle about image upload
            if ($request->hasFile('landing_about_image')) {
                $oldImage = setting('landing_about_image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $aboutPath = $request->file('landing_about_image')->store('landing/sections', 'public');
                Setting::set('landing_about_image', $aboutPath);
            }

            return redirect()->route('owner.landing-settings.index', ['tab' => 'sections'])
                ->with('success', 'Pengaturan section berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete section image
     */
    public function deleteSectionImage(Request $request)
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

    // =============================================
    // PROMO MANAGEMENT
    // =============================================

    /**
     * Store a new promo
     */
    public function storePromo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'order' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'promos');
        }

        try {
            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('landing/promos', 'public');
            }

            $data['is_active'] = $request->has('is_active');

            Promo::create($data);

            return redirect()->route('owner.landing-settings.index', ['tab' => 'promos'])
                ->with('success', 'Promo berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan promo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update a promo
     */
    public function updatePromo(Request $request, Promo $promo)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'order' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('activeTab', 'promos');
        }

        try {
            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($promo->image && Storage::disk('public')->exists($promo->image)) {
                    Storage::disk('public')->delete($promo->image);
                }
                $data['image'] = $request->file('image')->store('landing/promos', 'public');
            }

            $data['is_active'] = $request->has('is_active');

            $promo->update($data);

            return redirect()->route('owner.landing-settings.index', ['tab' => 'promos'])
                ->with('success', 'Promo berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui promo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a promo
     */
    public function destroyPromo(Promo $promo)
    {
        try {
            // Delete image
            if ($promo->image && Storage::disk('public')->exists($promo->image)) {
                Storage::disk('public')->delete($promo->image);
            }

            $promo->delete();

            return redirect()->route('owner.landing-settings.index', ['tab' => 'promos'])
                ->with('success', 'Promo berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus promo: ' . $e->getMessage());
        }
    }
}

