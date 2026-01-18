{{-- File: resources/views/owner/settings/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@push('styles')
    <style>
        .image-preview-box {
            width: 100%;
            max-width: 300px;
            height: 200px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .image-preview-box:hover {
            border-color: #8B5A2B;
            background-color: #f8f9fa;
        }

        .image-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview-placeholder {
            text-align: center;
            color: #999;
        }

        .delete-image-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            color: #8B5A2B;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="bx bx-cog me-2"></i>Pengaturan Sistem
            </h4>
            <p class="text-muted mb-0 mt-1">Kelola pengaturan perusahaan, sistem, dan landing page</p>
        </div>

        <div class="card-body">
            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'general' ? 'active' : '' }}"
                        href="{{ route('owner.settings.index', ['tab' => 'general']) }}">
                        <i class="bx bx-building me-1"></i>Umum
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'system' ? 'active' : '' }}"
                        href="{{ route('owner.settings.index', ['tab' => 'system']) }}">
                        <i class="bx bx-cog me-1"></i>Sistem
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'landing' ? 'active' : '' }}"
                        href="{{ route('owner.settings.index', ['tab' => 'landing']) }}">
                        <i class="bx bx-layout me-1"></i>Landing Page
                    </a>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content">
                {{-- General Settings Tab --}}
                <div class="tab-pane fade {{ $activeTab === 'general' ? 'show active' : '' }}">
                    <form action="{{ route('owner.settings.updateGeneral') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="text-primary mb-3">
                            <i class="bx bx-info-circle me-1"></i>Informasi Perusahaan
                        </h5>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">
                                        Nama Perusahaan <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name" name="company_name"
                                        value="{{ old('company_name', $generalSettings['company_name']->value ?? '') }}"
                                        required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_email" class="form-label">
                                                Email Perusahaan <span class="text-danger">*</span>
                                            </label>
                                            <input type="email"
                                                class="form-control @error('company_email') is-invalid @enderror"
                                                id="company_email" name="company_email"
                                                value="{{ old('company_email', $generalSettings['company_email']->value ?? '') }}"
                                                required>
                                            @error('company_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_phone" class="form-label">
                                                No. Telepon <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                class="form-control @error('company_phone') is-invalid @enderror"
                                                id="company_phone" name="company_phone"
                                                value="{{ old('company_phone', $generalSettings['company_phone']->value ?? '') }}"
                                                placeholder="081234567890" required>
                                            @error('company_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="company_address" class="form-label">
                                        Alamat Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('company_address') is-invalid @enderror" id="company_address"
                                        name="company_address" rows="4" required>{{ old('company_address', $generalSettings['company_address']->value ?? '') }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Logo Perusahaan</label>
                                <div class="image-preview-box mb-2" id="logoPreview"
                                    onclick="document.getElementById('company_logo').click()">
                                    @if ($generalSettings['company_logo']->value ?? null)
                                        <img src="{{ Storage::url($generalSettings['company_logo']->value) }}"
                                            alt="Company Logo" id="logoImage">
                                        <button type="button" class="btn btn-sm btn-danger delete-image-btn"
                                            onclick="event.stopPropagation(); deleteImage('company_logo', 'logoPreview')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    @else
                                        <div class="image-preview-placeholder">
                                            <i class="bx bx-image-add bx-lg"></i>
                                            <p class="mb-0 mt-2 small">Klik untuk upload logo</p>
                                            <p class="mb-0 small text-muted">JPG, PNG, WEBP (Max 2MB)</p>
                                        </div>
                                    @endif
                                </div>
                                <input type="file"
                                    class="form-control d-none @error('company_logo') is-invalid @enderror"
                                    id="company_logo" name="company_logo" accept="image/jpeg,image/png,image/jpg,image/webp"
                                    onchange="previewImage(event, 'logoPreview', 'logoImage')">
                                @error('company_logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror>
                                <small class="text-muted">Logo akan ditampilkan di header sistem</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>Simpan Pengaturan Umum
                            </button>
                        </div>
                    </form>
                </div>

                {{-- System Settings Tab --}}
                <div class="tab-pane fade {{ $activeTab === 'system' ? 'show active' : '' }}">
                    <form action="{{ route('owner.settings.updateSystem') }}" method="POST">
                        @csrf

                        <h5 class="text-primary mb-3">
                            <i class="bx bx-money me-1"></i>Pengaturan Transaksi
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_rate" class="form-label">
                                        Tarif Pajak PPN (%) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('tax_rate') is-invalid @enderror"
                                            id="tax_rate" name="tax_rate"
                                            value="{{ old('tax_rate', $systemSettings['tax_rate']->value ?? 11) }}"
                                            step="0.01" min="0" max="100" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('tax_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <small class="text-muted">Pajak akan diterapkan pada setiap transaksi. Default: 11%
                                        (PPN Indonesia)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="shipping_cost" class="form-label">
                                        Biaya Ongkir Standar (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                            class="form-control @error('shipping_cost') is-invalid @enderror"
                                            id="shipping_cost" name="shipping_cost"
                                            value="{{ old('shipping_cost', $systemSettings['shipping_cost']->value ?? 25000) }}"
                                            min="0" required>
                                    </div>
                                    @error('shipping_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <small class="text-muted">Biaya pengiriman standar untuk setiap order</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_order_amount" class="form-label">
                                        Minimal Order (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                            class="form-control @error('min_order_amount') is-invalid @enderror"
                                            id="min_order_amount" name="min_order_amount"
                                            value="{{ old('min_order_amount', $systemSettings['min_order_amount']->value ?? 100000) }}"
                                            min="0" required>
                                    </div>
                                    @error('min_order_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <small class="text-muted">Minimal jumlah pembelian yang harus dipenuhi customer</small>
                                </div>

                                <div class="mb-3">
                                    <label for="currency" class="form-label">
                                        Mata Uang <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('currency') is-invalid @enderror" id="currency"
                                        name="currency" required>
                                        <option value="IDR"
                                            {{ old('currency', $systemSettings['currency']->value ?? 'IDR') === 'IDR' ? 'selected' : '' }}>
                                            IDR - Indonesian Rupiah
                                        </option>
                                        <option value="USD"
                                            {{ old('currency', $systemSettings['currency']->value ?? 'IDR') === 'USD' ? 'selected' : '' }}>
                                            USD - US Dollar
                                        </option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-1"></i>
                            <strong>Catatan:</strong> Pengaturan sistem akan langsung diterapkan pada semua transaksi baru.
                        </div>

                        <hr class="my-4">

                        {{-- Bank Information Section --}}
                        <h5 class="text-primary mb-3">
                            <i class="bx bx-credit-card me-1"></i>Informasi Bank untuk Pembayaran
                        </h5>
                        <p class="text-muted mb-3">Konfigurasi rekening bank yang akan ditampilkan pada invoice (maksimal 3
                            bank)</p>

                        {{-- Bank 1 --}}
                        <div class="card mb-3 border">
                            <div class="card-header bg-light py-2">
                                <strong><i class="bx bx-bank me-1"></i>Bank 1 (Utama)</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bank_name_1" class="form-label">Nama Bank</label>
                                            <input type="text" class="form-control" id="bank_name_1"
                                                name="bank_name_1"
                                                value="{{ old('bank_name_1', $systemSettings['bank_name_1']->value ?? 'Bank BCA') }}"
                                                placeholder="Contoh: Bank BCA">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bank_account_number_1" class="form-label">No. Rekening</label>
                                            <input type="text" class="form-control" id="bank_account_number_1"
                                                name="bank_account_number_1"
                                                value="{{ old('bank_account_number_1', $systemSettings['bank_account_number_1']->value ?? '') }}"
                                                placeholder="Contoh: 1234567890">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bank_account_name_1" class="form-label">Nama Pemilik
                                                Rekening</label>
                                            <input type="text" class="form-control" id="bank_account_name_1"
                                                name="bank_account_name_1"
                                                value="{{ old('bank_account_name_1', $systemSettings['bank_account_name_1']->value ?? '') }}"
                                                placeholder="Contoh: PT Eureka Kopi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bank 2 --}}
                        <div class="card mb-3 border">
                            <div class="card-header bg-light py-2">
                                <strong><i class="bx bx-bank me-1"></i>Bank 2 (Opsional)</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bank_name_2" class="form-label">Nama Bank</label>
                                            <input type="text" class="form-control" id="bank_name_2"
                                                name="bank_name_2"
                                                value="{{ old('bank_name_2', $systemSettings['bank_name_2']->value ?? '') }}"
                                                placeholder="Contoh: Bank Mandiri">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bank_account_number_2" class="form-label">No. Rekening</label>
                                            <input type="text" class="form-control" id="bank_account_number_2"
                                                name="bank_account_number_2"
                                                value="{{ old('bank_account_number_2', $systemSettings['bank_account_number_2']->value ?? '') }}"
                                                placeholder="Contoh: 0987654321">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bank_account_name_2" class="form-label">Nama Pemilik
                                                Rekening</label>
                                            <input type="text" class="form-control" id="bank_account_name_2"
                                                name="bank_account_name_2"
                                                value="{{ old('bank_account_name_2', $systemSettings['bank_account_name_2']->value ?? '') }}"
                                                placeholder="Contoh: PT Eureka Kopi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bank 3 --}}
                        <div class="card mb-3 border">
                            <div class="card-header bg-light py-2">
                                <strong><i class="bx bx-bank me-1"></i>Bank 3 (Opsional)</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bank_name_3" class="form-label">Nama Bank</label>
                                            <input type="text" class="form-control" id="bank_name_3"
                                                name="bank_name_3"
                                                value="{{ old('bank_name_3', $systemSettings['bank_name_3']->value ?? '') }}"
                                                placeholder="Contoh: Bank BRI">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bank_account_number_3" class="form-label">No. Rekening</label>
                                            <input type="text" class="form-control" id="bank_account_number_3"
                                                name="bank_account_number_3"
                                                value="{{ old('bank_account_number_3', $systemSettings['bank_account_number_3']->value ?? '') }}"
                                                placeholder="Contoh: 1122334455">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="bank_account_name_3" class="form-label">Nama Pemilik
                                                Rekening</label>
                                            <input type="text" class="form-control" id="bank_account_name_3"
                                                name="bank_account_name_3"
                                                value="{{ old('bank_account_name_3', $systemSettings['bank_account_name_3']->value ?? '') }}"
                                                placeholder="Contoh: PT Eureka Kopi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bx bx-info-circle me-1"></i>
                            <strong>Info:</strong> Informasi bank akan ditampilkan pada invoice sebagai opsi pembayaran
                            untuk customer.
                            Kosongkan jika tidak ingin menampilkan bank tertentu.
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>Simpan Pengaturan Sistem
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Landing Page Settings Tab --}}
                <div class="tab-pane fade {{ $activeTab === 'landing' ? 'show active' : '' }}">
                    <form action="{{ route('owner.settings.updateLanding') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Hero Section --}}
                        <h5 class="text-primary mb-3">
                            <i class="bx bx-image me-1"></i>Hero Section
                        </h5>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="landing_hero_title" class="form-label">
                                        Judul Hero <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('landing_hero_title') is-invalid @enderror"
                                        id="landing_hero_title" name="landing_hero_title"
                                        value="{{ old('landing_hero_title', $landingSettings['landing_hero_title']->value ?? '') }}"
                                        required>
                                    @error('landing_hero_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>

                                <div class="mb-3">
                                    <label for="landing_hero_subtitle" class="form-label">
                                        Subjudul Hero <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('landing_hero_subtitle') is-invalid @enderror" id="landing_hero_subtitle"
                                        name="landing_hero_subtitle" rows="3" required>{{ old('landing_hero_subtitle', $landingSettings['landing_hero_subtitle']->value ?? '') }}</textarea>
                                    @error('landing_hero_subtitle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Gambar Hero</label>
                                <div class="image-preview-box mb-2" id="heroPreview"
                                    onclick="document.getElementById('landing_hero_image').click()">
                                    @if ($landingSettings['landing_hero_image']->value ?? null)
                                        <img src="{{ Storage::url($landingSettings['landing_hero_image']->value) }}"
                                            alt="Hero Image" id="heroImage">
                                        <button type="button" class="btn btn-sm btn-danger delete-image-btn"
                                            onclick="event.stopPropagation(); deleteImage('landing_hero_image', 'heroPreview')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    @else
                                        <div class="image-preview-placeholder">
                                            <i class="bx bx-image-add bx-lg"></i>
                                            <p class="mb-0 mt-2 small">Upload gambar hero</p>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control d-none" id="landing_hero_image"
                                    name="landing_hero_image" accept="image/jpeg,image/png,image/jpg,image/webp"
                                    onchange="previewImage(event, 'heroPreview', 'heroImage')">
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- About Section --}}
                        <h5 class="text-primary mb-3">
                            <i class="bx bx-info-circle me-1"></i>About Section
                        </h5>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="landing_about_title" class="form-label">
                                        Judul About <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('landing_about_title') is-invalid @enderror"
                                        id="landing_about_title" name="landing_about_title"
                                        value="{{ old('landing_about_title', $landingSettings['landing_about_title']->value ?? '') }}"
                                        required>
                                    @error('landing_about_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>

                                <div class="mb-3">
                                    <label for="landing_about_content" class="form-label">
                                        Konten About <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('landing_about_content') is-invalid @enderror" id="landing_about_content"
                                        name="landing_about_content" rows="5" required>{{ old('landing_about_content', $landingSettings['landing_about_content']->value ?? '') }}</textarea>
                                    @error('landing_about_content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Gambar About</label>
                                <div class="image-preview-box mb-2" id="aboutPreview"
                                    onclick="document.getElementById('landing_about_image').click()">
                                    @if ($landingSettings['landing_about_image']->value ?? null)
                                        <img src="{{ Storage::url($landingSettings['landing_about_image']->value) }}"
                                            alt="About Image" id="aboutImage">
                                        <button type="button" class="btn btn-sm btn-danger delete-image-btn"
                                            onclick="event.stopPropagation(); deleteImage('landing_about_image', 'aboutPreview')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    @else
                                        <div class="image-preview-placeholder">
                                            <i class="bx bx-image-add bx-lg"></i>
                                            <p class="mb-0 mt-2 small">Upload gambar about</p>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control d-none" id="landing_about_image"
                                    name="landing_about_image" accept="image/jpeg,image/png,image/jpg,image/webp"
                                    onchange="previewImage(event, 'aboutPreview', 'aboutImage')">
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Social Media --}}
                        <h5 class="text-primary mb-3">
                            <i class="bx bx-share-alt me-1"></i>Social Media & Kontak
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="landing_whatsapp" class="form-label">WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bx bxl-whatsapp"></i>
                                        </span>
                                        <input type="text" class="form-control" id="landing_whatsapp"
                                            name="landing_whatsapp"
                                            value="{{ old('landing_whatsapp', $landingSettings['landing_whatsapp']->value ?? '') }}"
                                            placeholder="6281234567890">
                                    </div>
                                    <small class="text-muted">Format: 62xxx (tanpa +)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="landing_instagram" class="form-label">Instagram</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bx bxl-instagram"></i>
                                        </span>
                                        <input type="text" class="form-control" id="landing_instagram"
                                            name="landing_instagram"
                                            value="{{ old('landing_instagram', $landingSettings['landing_instagram']->value ?? '') }}"
                                            placeholder="@username">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="landing_facebook" class="form-label">Facebook</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bx bxl-facebook"></i>
                                        </span>
                                        <input type="text" class="form-control" id="landing_facebook"
                                            name="landing_facebook"
                                            value="{{ old('landing_facebook', $landingSettings['landing_facebook']->value ?? '') }}"
                                            placeholder="username">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="landing_email" class="form-label">Email Kontak</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bx bx-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control" id="landing_email"
                                            name="landing_email"
                                            value="{{ old('landing_email', $landingSettings['landing_email']->value ?? '') }}"
                                            placeholder="contact@example.com">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>Simpan Pengaturan Landing Page
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview image when selected
        function previewImage(event, previewBoxId, imageId) {
            const file = event.target.files[0];
            const previewBox = document.getElementById(previewBoxId);

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewBox.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" id="${imageId}">
                        <button type="button" class="btn btn-sm btn-danger delete-image-btn" 
                                onclick="event.stopPropagation(); removePreview('${previewBoxId}', '${event.target.id}')">
                            <i class="bx bx-trash"></i>
                        </button>
                    `;
                }
                reader.readAsDataURL(file);
            }
        }

        // Remove preview (client-side only, not delete from server)
        function removePreview(previewBoxId, inputId) {
            const previewBox = document.getElementById(previewBoxId);
            const input = document.getElementById(inputId);

            previewBox.innerHTML = `
                <div class="image-preview-placeholder">
                    <i class="bx bx-image-add bx-lg"></i>
                    <p class="mb-0 mt-2 small">Klik untuk upload</p>
                </div>
            `;
            input.value = '';
        }

        // Delete image from server
        function deleteImage(key, previewBoxId) {
            swalCoffee.fire({
                title: 'Hapus Gambar?',
                text: 'Gambar akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('owner.settings.deleteImage') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                key: key
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const previewBox = document.getElementById(previewBoxId);
                                previewBox.innerHTML = `
                                <div class="image-preview-placeholder">
                                    <i class="bx bx-image-add bx-lg"></i>
                                    <p class="mb-0 mt-2 small">Klik untuk upload</p>
                                </div>
                            `;
                                showSuccess('Gambar berhasil dihapus!');
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showError('Gagal menghapus gambar: ' + data.message);
                            }
                        })
                        .catch(error => {
                            showError('Terjadi kesalahan: ' + error);
                        });
                }
            });
        }

        // Set active tab from session/query
        @if (session('activeTab'))
            window.history.replaceState(null, null, '?tab={{ session('activeTab') }}');
        @endif
    </script>
@endpush


