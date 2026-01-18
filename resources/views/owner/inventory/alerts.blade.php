{{-- File: resources/views/owner/inventory/alerts.blade.php --}}

@extends('layouts.app')

@section('title', __('inventory.stock_alerts'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>
                    <i class="bx bx-bell text-warning me-2"></i>{{ __('inventory.stock_alerts') }}
                </h4>
                <a href="{{ route('owner.inventory.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('general.back') }}
                </a>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white mb-1">
                                        <i class="bx bx-error-circle me-1"></i>{{ __('inventory.low_stock') }}
                                    </h6>
                                    <h2 class="mb-0">{{ $lowStockProducts->count() }}</h2>
                                    <small>{{ __('inventory.low_stock_warning') }}</small>
                                </div>
                                <div>
                                    <i class="bx bx-error bx-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white mb-1">
                                        <i class="bx bx-x-circle me-1"></i>{{ __('inventory.out_of_stock') }}
                                    </h6>
                                    <h2 class="mb-0">{{ $outOfStockProducts->count() }}</h2>
                                    <small>{{ __('inventory.out_of_stock_warning') }}</small>
                                </div>
                                <div>
                                    <i class="bx bx-package bx-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Low Stock Products --}}
            @if ($lowStockProducts->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0 text-white">
                            <i class="bx bx-error me-1"></i>{{ __('inventory.low_stock') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 60px">{{ __('general.image') }}</th>
                                        <th>{{ __('products.product') }}</th>
                                        <th>{{ __('inventory.sku') }}</th>
                                        <th>{{ __('general.category') }}</th>
                                        <th>{{ __('inventory.available_stock') }}</th>
                                        <th>{{ __('inventory.minimum_stock') }}</th>
                                        <th>%</th>
                                        <th>{{ __('general.status') }}</th>
                                        <th style="width: 150px">{{ __('general.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lowStockProducts as $product)
                                        @php
                                            $available = $product->getAvailableStock();
                                            $percentage = ($available / $product->min_stock) * 100;
                                        @endphp
                                        <tr>
                                            <td>
                                                @if ($product->image)
                                                    <img src="{{ Storage::url($product->image) }}"
                                                        alt="{{ $product->name }}" class="rounded"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="bx bx-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $product->weight }}g</small>
                                            </td>
                                            <td><code>{{ $product->sku }}</code></td>
                                            <td>
                                                <span class="badge bg-info">{{ $product->category->name }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $available }} unit</span>
                                            </td>
                                            <td>{{ $product->min_stock }} unit</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-warning" role="progressbar"
                                                        style="width: {{ min($percentage, 100) }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ number_format($percentage, 0) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($percentage <= 50)
                                                    <span class="badge bg-danger">
                                                        <i class="bx bx-error-circle"></i> Kritis
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="bx bx-error"></i> Menipis
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('owner.inventory.adjust', $product) }}"
                                                        class="btn btn-sm btn-warning" title="Restock">
                                                        <i class="bx bx-plus-circle"></i> Restock
                                                    </a>
                                                    <a href="{{ route('owner.products.show', $product) }}"
                                                        class="btn btn-sm btn-info" title="Detail">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Out of Stock Products --}}
            @if ($outOfStockProducts->count() > 0)
                <div class="card">
                    <div class="card-header bg-danger">
                        <h5 class="mb-0 text-white">
                            <i class="bx bx-x-circle me-1"></i>{{ __('inventory.out_of_stock') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <i class="bx bx-error-circle me-1"></i>
                            <strong>{{ __('general.warning') }}!</strong> {{ __('inventory.out_of_stock_warning') }}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 60px">{{ __('general.image') }}</th>
                                        <th>{{ __('products.product') }}</th>
                                        <th>{{ __('inventory.sku') }}</th>
                                        <th>{{ __('general.category') }}</th>
                                        <th>{{ __('inventory.stock') }}</th>
                                        <th>{{ __('inventory.reserved_stock') }}</th>
                                        <th>{{ __('inventory.available_stock') }}</th>
                                        <th>{{ __('general.status') }}</th>
                                        <th style="width: 150px">{{ __('general.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($outOfStockProducts as $product)
                                        <tr>
                                            <td>
                                                @if ($product->image)
                                                    <img src="{{ Storage::url($product->image) }}"
                                                        alt="{{ $product->name }}" class="rounded"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="bx bx-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $product->weight }}g</small>
                                            </td>
                                            <td><code>{{ $product->sku }}</code></td>
                                            <td>
                                                <span class="badge bg-info">{{ $product->category->name }}</span>
                                            </td>
                                            <td>{{ $product->inventory ? $product->inventory->quantity : 0 }}</td>
                                            <td>{{ $product->inventory ? $product->inventory->reserved : 0 }}</td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <i class="bx bx-x-circle"></i> 0 unit
                                                </span>
                                            </td>
                                            <td>
                                                @if ($product->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                    <br>
                                                    <small class="text-danger">Tidak bisa dibeli!</small>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('owner.inventory.adjust', $product) }}"
                                                        class="btn btn-sm btn-danger" title="Restock Sekarang">
                                                        <i class="bx bx-plus-circle"></i> Restock
                                                    </a>
                                                    <a href="{{ route('owner.products.show', $product) }}"
                                                        class="btn btn-sm btn-info" title="Detail">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- No Alerts --}}
            @if ($lowStockProducts->count() === 0 && $outOfStockProducts->count() === 0)
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bx bx-check-circle bx-lg text-success mb-3"></i>
                        <h4>{{ __('inventory.no_alerts') }}</h4>
                        <p class="text-muted">{{ __('inventory.in_stock') }} 🎉</p>
                        <a href="{{ route('owner.inventory.index') }}" class="btn btn-primary mt-2">
                            <i class="bx bx-package me-1"></i>{{ __('inventory.inventory') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

