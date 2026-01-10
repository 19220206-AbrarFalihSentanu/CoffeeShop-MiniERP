{{-- File: resources/views/owner/landing-settings/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Pengaturan Landing Page')

@push('styles')
    <style>
        .image-preview-box {
            width: 100%;
            height: 180px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            background: #f8f9fa;
        }

        .image-preview-box:hover {
            border-color: #696cff;
            background-color: #f0f0ff;
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
            top: 8px;
            right: 8px;
            z-index: 10;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            padding: 1rem 1.5rem;
        }

        .nav-tabs .nav-link.active {
            color: #696cff;
            font-weight: 600;
            border-bottom: 3px solid #696cff;
            background: transparent;
        }

        .slide-card,
        .partner-card-item {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            background: #fff;
            transition: all 0.3s ease;
        }

        .slide-card:hover,
        .partner-card-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .slide-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .partner-logo-preview {
            width: 80px;
            height: 80px;
            object-fit: contain;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 5px;
        }

        .status-badge {
            font-size: 0.75rem;
        }

        .accordion-button:not(.collapsed) {
            background-color: #f0f0ff;
            color: #696cff;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-section h6 {
            color: #696cff;
            margin-bottom: 1rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">
                    <i class="bx bx-layout me-2"></i>Pengaturan Landing Page
                </h4>
                <p class="text-muted mb-0 mt-1">Kelola tampilan landing page website Anda</p>
            </div>
            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary">
                <i class="bx bx-link-external me-1"></i>Lihat Landing Page
            </a>
        </div>

        <div class="card-body">
            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'slides' ? 'active' : '' }}"
                        href="{{ route('owner.landing-settings.index', ['tab' => 'slides']) }}">
                        <i class="bx bx-images me-1"></i>Beranda/Slides
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'sections' ? 'active' : '' }}"
                        href="{{ route('owner.landing-settings.index', ['tab' => 'sections']) }}">
                        <i class="bx bx-layout me-1"></i>Section Content
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'partners' ? 'active' : '' }}"
                        href="{{ route('owner.landing-settings.index', ['tab' => 'partners']) }}">
                        <i class="bx bx-buildings me-1"></i>Partner
                    </a>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content">
                {{-- =============================================
                    SLIDES TAB
                ============================================= --}}
                <div class="tab-pane fade {{ $activeTab === 'slides' ? 'show active' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1">Carousel Beranda</h5>
                            <p class="text-muted small mb-0">Kelola slide carousel di halaman beranda. Maksimal 5 slide.</p>
                        </div>
                        @if ($slides->count() < 5)
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSlideModal">
                                <i class="bx bx-plus me-1"></i>Tambah Slide
                            </button>
                        @endif
                    </div>

                    @if ($slides->count() > 0)
                        <div class="row">
                            @foreach ($slides as $slide)
                                <div class="col-md-6 col-lg-4">
                                    <div class="slide-card">
                                        @if ($slide->image)
                                            <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}"
                                                class="slide-image mb-3">
                                        @else
                                            <div
                                                class="slide-image mb-3 d-flex align-items-center justify-content-center bg-light">
                                                <i class="bx bx-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">{{ Str::limit($slide->title, 30) }}</h6>
                                            <span
                                                class="badge {{ $slide->is_active ? 'bg-success' : 'bg-secondary' }} status-badge">
                                                {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-3">{{ Str::limit($slide->subtitle, 50) }}</p>

                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary flex-grow-1"
                                                data-bs-toggle="modal" data-bs-target="#editSlideModal{{ $slide->id }}">
                                                <i class="bx bx-edit-alt"></i> Edit
                                            </button>
                                            <form action="{{ route('owner.landing-settings.slides.destroy', $slide) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus slide ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Edit Slide Modal --}}
                                <div class="modal fade" id="editSlideModal{{ $slide->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('owner.landing-settings.slides.update', $slide) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Slide</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label class="form-label">Gambar Slide</label>
                                                            <div class="image-preview-box mb-2"
                                                                onclick="document.getElementById('edit_slide_image_{{ $slide->id }}').click()">
                                                                @if ($slide->image)
                                                                    <img src="{{ asset('storage/' . $slide->image) }}"
                                                                        alt="Preview"
                                                                        id="editSlidePreview{{ $slide->id }}">
                                                                @else
                                                                    <div class="image-preview-placeholder">
                                                                        <i class="bx bx-image-add bx-lg"></i>
                                                                        <p class="mb-0 mt-2 small">Upload gambar</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <input type="file" class="form-control d-none"
                                                                id="edit_slide_image_{{ $slide->id }}" name="image"
                                                                accept="image/*"
                                                                onchange="previewEditSlideImage(event, {{ $slide->id }})">
                                                            <small class="text-muted">Max 5MB. JPG, PNG, WEBP</small>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <div class="mb-3">
                                                                <label class="form-label">Judul <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="title"
                                                                    value="{{ $slide->title }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Subtitle</label>
                                                                <textarea class="form-control" name="subtitle" rows="3">{{ $slide->subtitle }}</textarea>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Text Button</label>
                                                                        <input type="text" class="form-control"
                                                                            name="button_text"
                                                                            value="{{ $slide->button_text }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Link Button</label>
                                                                        <input type="text" class="form-control"
                                                                            name="button_link"
                                                                            value="{{ $slide->button_link }}"
                                                                            placeholder="#produk">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Urutan <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="number" class="form-control"
                                                                            name="order" value="{{ $slide->order }}"
                                                                            min="1" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Status</label>
                                                                        <div class="form-check form-switch mt-2">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" name="is_active"
                                                                                value="1"
                                                                                {{ $slide->is_active ? 'checked' : '' }}>
                                                                            <label class="form-check-label">Aktif</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bx bx-save me-1"></i>Simpan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-images text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Belum ada slide. Tambahkan slide pertama Anda!</p>
                        </div>
                    @endif
                </div>

                {{-- =============================================
                    SECTIONS TAB
                ============================================= --}}
                <div class="tab-pane fade {{ $activeTab === 'sections' ? 'show active' : '' }}">
                    <form action="{{ route('owner.landing-settings.sections.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Product Section --}}
                        <div class="form-section">
                            <h6><i class="bx bx-package me-2"></i>Section Produk</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Judul Section <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="landing_product_title"
                                            value="{{ $landingSettings['landing_product_title']->value ?? 'Produk Kami' }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subtitle</label>
                                        <input type="text" class="form-control" name="landing_product_subtitle"
                                            value="{{ $landingSettings['landing_product_subtitle']->value ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- About Section --}}
                        <div class="form-section">
                            <h6><i class="bx bx-info-circle me-2"></i>Section Tentang</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Judul Section <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="landing_about_title"
                                            value="{{ $landingSettings['landing_about_title']->value ?? '' }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Konten <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="landing_about_content" rows="5" required>{{ $landingSettings['landing_about_content']->value ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Gambar Section</label>
                                    <div class="image-preview-box"
                                        onclick="document.getElementById('landing_about_image').click()">
                                        @if ($landingSettings['landing_about_image']->value ?? null)
                                            <img src="{{ asset('storage/' . $landingSettings['landing_about_image']->value) }}"
                                                alt="About Image" id="aboutPreviewImg">
                                            <button type="button" class="btn btn-sm btn-danger delete-image-btn"
                                                onclick="event.stopPropagation(); deleteSectionImage('landing_about_image')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        @else
                                            <div class="image-preview-placeholder" id="aboutPlaceholder">
                                                <i class="bx bx-image-add bx-lg"></i>
                                                <p class="mb-0 mt-2 small">Upload gambar</p>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" class="form-control d-none" id="landing_about_image"
                                        name="landing_about_image" accept="image/*"
                                        onchange="previewSectionImage(event, 'aboutPreviewImg', 'aboutPlaceholder')">
                                </div>
                            </div>
                        </div>

                        {{-- Partner Section --}}
                        <div class="form-section">
                            <h6><i class="bx bx-buildings me-2"></i>Section Partner</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Judul Section <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="landing_partner_title"
                                            value="{{ $landingSettings['landing_partner_title']->value ?? 'Partner Kami' }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subtitle</label>
                                        <input type="text" class="form-control" name="landing_partner_subtitle"
                                            value="{{ $landingSettings['landing_partner_subtitle']->value ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Section --}}
                        <div class="form-section">
                            <h6><i class="bx bx-phone me-2"></i>Section Kontak</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Judul Section <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="landing_contact_title"
                                            value="{{ $landingSettings['landing_contact_title']->value ?? 'Hubungi Kami' }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subtitle</label>
                                        <input type="text" class="form-control" name="landing_contact_subtitle"
                                            value="{{ $landingSettings['landing_contact_subtitle']->value ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">
                            <p class="text-muted small mb-3">Social Media & Contact Links</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><i
                                                class="bx bxl-whatsapp text-success me-1"></i>WhatsApp</label>
                                        <input type="text" class="form-control" name="landing_whatsapp"
                                            value="{{ $landingSettings['landing_whatsapp']->value ?? '' }}"
                                            placeholder="6281234567890">
                                        <small class="text-muted">Format: 62xxx (tanpa +)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><i
                                                class="bx bx-envelope text-primary me-1"></i>Email</label>
                                        <input type="email" class="form-control" name="landing_email"
                                            value="{{ $landingSettings['landing_email']->value ?? '' }}"
                                            placeholder="contact@example.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><i
                                                class="bx bxl-instagram text-danger me-1"></i>Instagram</label>
                                        <input type="text" class="form-control" name="landing_instagram"
                                            value="{{ $landingSettings['landing_instagram']->value ?? '' }}"
                                            placeholder="@username">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><i
                                                class="bx bxl-facebook text-info me-1"></i>Facebook</label>
                                        <input type="text" class="form-control" name="landing_facebook"
                                            value="{{ $landingSettings['landing_facebook']->value ?? '' }}"
                                            placeholder="username">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>Simpan Semua Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- =============================================
                    PARTNERS TAB
                ============================================= --}}
                <div class="tab-pane fade {{ $activeTab === 'partners' ? 'show active' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1">Daftar Partner</h5>
                            <p class="text-muted small mb-0">Kelola partner/klien yang ditampilkan di landing page</p>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                            <i class="bx bx-plus me-1"></i>Tambah Partner
                        </button>
                    </div>

                    @if ($partners->count() > 0)
                        <div class="row">
                            @foreach ($partners as $partner)
                                <div class="col-md-6 col-lg-4">
                                    <div class="partner-card-item">
                                        <div class="d-flex gap-3">
                                            @if ($partner->logo)
                                                <img src="{{ asset('storage/' . $partner->logo) }}"
                                                    alt="{{ $partner->name }}" class="partner-logo-preview">
                                            @else
                                                <div
                                                    class="partner-logo-preview d-flex align-items-center justify-content-center">
                                                    <i class="bx bx-buildings text-muted" style="font-size: 2rem;"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="mb-1">{{ $partner->name }}</h6>
                                                    <span
                                                        class="badge {{ $partner->is_active ? 'bg-success' : 'bg-secondary' }} status-badge">
                                                        {{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </div>
                                                <p class="text-muted small mb-2">
                                                    {{ Str::limit($partner->description, 50) }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 mt-3">
                                            <button class="btn btn-sm btn-outline-primary flex-grow-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editPartnerModal{{ $partner->id }}">
                                                <i class="bx bx-edit-alt"></i> Edit
                                            </button>
                                            <form
                                                action="{{ route('owner.landing-settings.partners.destroy', $partner) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus partner ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Edit Partner Modal --}}
                                <div class="modal fade" id="editPartnerModal{{ $partner->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('owner.landing-settings.partners.update', $partner) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Partner</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        <div class="image-preview-box mx-auto"
                                                            style="width: 150px; height: 150px;"
                                                            onclick="document.getElementById('edit_partner_logo_{{ $partner->id }}').click()">
                                                            @if ($partner->logo)
                                                                <img src="{{ asset('storage/' . $partner->logo) }}"
                                                                    alt="Logo"
                                                                    id="editPartnerPreview{{ $partner->id }}">
                                                            @else
                                                                <div class="image-preview-placeholder">
                                                                    <i class="bx bx-image-add bx-lg"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" class="form-control d-none"
                                                            id="edit_partner_logo_{{ $partner->id }}" name="logo"
                                                            accept="image/*"
                                                            onchange="previewEditPartnerImage(event, {{ $partner->id }})">
                                                        <small class="text-muted">Upload logo partner</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Partner <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="name"
                                                            value="{{ $partner->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Website</label>
                                                        <input type="url" class="form-control" name="website"
                                                            value="{{ $partner->website }}"
                                                            placeholder="https://example.com">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi</label>
                                                        <textarea class="form-control" name="description" rows="2">{{ $partner->description }}</textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Urutan <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" class="form-control" name="order"
                                                                    value="{{ $partner->order }}" min="1"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Status</label>
                                                                <div class="form-check form-switch mt-2">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="is_active" value="1"
                                                                        {{ $partner->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Aktif</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bx bx-save me-1"></i>Simpan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-buildings text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Belum ada partner. Tambahkan partner pertama!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- =============================================
        ADD SLIDE MODAL
    ============================================= --}}
    <div class="modal fade" id="addSlideModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('owner.landing-settings.slides.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Slide Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Gambar Slide <span class="text-danger">*</span></label>
                                <div class="image-preview-box mb-2"
                                    onclick="document.getElementById('new_slide_image').click()">
                                    <div class="image-preview-placeholder" id="newSlidePreviewPlaceholder">
                                        <i class="bx bx-image-add bx-lg"></i>
                                        <p class="mb-0 mt-2 small">Upload gambar</p>
                                    </div>
                                    <img src="" alt="Preview" id="newSlidePreview" style="display: none;">
                                </div>
                                <input type="file" class="form-control d-none" id="new_slide_image" name="image"
                                    accept="image/*" onchange="previewNewSlideImage(event)" required>
                                <small class="text-muted">Max 5MB. JPG, PNG, WEBP. Resolusi: 1920x1080</small>
                            </div>
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label">Judul <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required
                                        placeholder="Supplier Kopi Terbaik">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Subtitle</label>
                                    <textarea class="form-control" name="subtitle" rows="3" placeholder="Kopi berkualitas premium..."></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Text Button</label>
                                            <input type="text" class="form-control" name="button_text"
                                                placeholder="Lihat Produk">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Link Button</label>
                                            <input type="text" class="form-control" name="button_link"
                                                placeholder="#produk">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Urutan <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="order"
                                                value="{{ $slides->count() + 1 }}" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                    value="1" checked>
                                                <label class="form-check-label">Aktif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>Tambah Slide
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =============================================
        ADD PARTNER MODAL
    ============================================= --}}
    <div class="modal fade" id="addPartnerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('owner.landing-settings.partners.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Partner Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <div class="image-preview-box mx-auto" style="width: 150px; height: 150px;"
                                onclick="document.getElementById('new_partner_logo').click()">
                                <div class="image-preview-placeholder" id="newPartnerPlaceholder">
                                    <i class="bx bx-image-add bx-lg"></i>
                                </div>
                                <img src="" alt="Preview" id="newPartnerPreview" style="display: none;">
                            </div>
                            <input type="file" class="form-control d-none" id="new_partner_logo" name="logo"
                                accept="image/*" onchange="previewNewPartnerImage(event)">
                            <small class="text-muted">Upload logo partner</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Partner <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required
                                placeholder="Nama Perusahaan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" class="form-control" name="website" placeholder="https://example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Deskripsi singkat..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Urutan <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="order"
                                        value="{{ $partners->count() + 1 }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            checked>
                                        <label class="form-check-label">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i>Tambah Partner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview new slide image
        function previewNewSlideImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('newSlidePreview').src = e.target.result;
                    document.getElementById('newSlidePreview').style.display = 'block';
                    document.getElementById('newSlidePreviewPlaceholder').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        // Preview edit slide image
        function previewEditSlideImage(event, slideId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewBox = document.querySelector(`#editSlideModal${slideId} .image-preview-box`);
                    previewBox.innerHTML =
                        `<img src="${e.target.result}" alt="Preview" id="editSlidePreview${slideId}">`;
                }
                reader.readAsDataURL(file);
            }
        }

        // Preview new partner image
        function previewNewPartnerImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('newPartnerPreview').src = e.target.result;
                    document.getElementById('newPartnerPreview').style.display = 'block';
                    document.getElementById('newPartnerPlaceholder').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        // Preview edit partner image
        function previewEditPartnerImage(event, partnerId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewBox = document.querySelector(`#editPartnerModal${partnerId} .image-preview-box`);
                    previewBox.innerHTML =
                        `<img src="${e.target.result}" alt="Preview" id="editPartnerPreview${partnerId}">`;
                }
                reader.readAsDataURL(file);
            }
        }

        // Preview section image
        function previewSectionImage(event, imgId, placeholderId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const placeholder = document.getElementById(placeholderId);
                    const img = document.getElementById(imgId);

                    if (placeholder) placeholder.style.display = 'none';

                    if (img) {
                        img.src = e.target.result;
                        img.style.display = 'block';
                    } else {
                        const previewBox = event.target.previousElementSibling;
                        previewBox.innerHTML = `<img src="${e.target.result}" alt="Preview" id="${imgId}">`;
                    }
                }
                reader.readAsDataURL(file);
            }
        }

        // Delete section image
        function deleteSectionImage(key) {
            if (!confirm('Yakin ingin menghapus gambar ini?')) return;

            fetch("{{ route('owner.landing-settings.sections.delete-image') }}", {
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
                        location.reload();
                    } else {
                        alert('Gagal menghapus gambar');
                    }
                })
                .catch(error => alert('Terjadi kesalahan'));
        }
    </script>
@endpush
