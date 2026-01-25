{{-- File: resources/views/layouts/partials/sidebar.blade.php (UPDATE) --}}
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                @if (setting('company_logo'))
                    <img src="{{ asset('storage/' . setting('company_logo')) }}"
                        alt="{{ setting('company_name', 'Eureka Coffee') }}"
                        style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #c9a66b;">
                @else
                    <i class='bx bxs-coffee' style="font-size: 28px; color: #c9a66b;"></i>
                @endif
            </span>
            <span
                class="app-brand-text demo menu-text fw-bolder ms-2">{{ setting('company_name', 'Eureka Coffee') }}</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @if (Auth::user()->isOwner())
            {{-- ============================================================ --}}
            {{-- OWNER MENU --}}
            {{-- ============================================================ --}}
            <li class="menu-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                <a href="{{ route('owner.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>{{ __('menu.dashboard') }}</div>
                </a>
            </li>

            {{-- NEW: Katalog Produk --}}
            <li class="menu-item {{ request()->routeIs('catalog.*') ? 'active' : '' }}">
                <a href="{{ route('catalog.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div>{{ __('menu.product_catalog') }}</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.management') }}</span></li>

            <li class="menu-item {{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                <a href="{{ route('owner.users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>{{ __('menu.manage_users') }}</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                <a href="{{ route('owner.categories.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-category"></i>
                    <div>{{ __('menu.manage_categories') }}</div>
                </a>
            </li>

            {{-- LINK PRODUK YANG BENAR --}}
            <li class="menu-item {{ request()->routeIs('owner.products.*') ? 'active' : '' }}">
                <a href="{{ route('owner.products.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-coffee"></i>
                    <div>{{ __('menu.manage_products') }}</div>
                </a>
            </li>

            {{-- LINK INVENTORY --}}
            <li class="menu-item {{ request()->routeIs('owner.inventory.*') ? 'active' : '' }}">
                <a href="{{ route('owner.inventory.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div>{{ __('menu.manage_inventory') }}</div>
                </a>
            </li>

            {{-- LINK SUPPLIER --}}
            <li class="menu-item {{ request()->routeIs('owner.suppliers.*') ? 'active' : '' }}">
                <a href="{{ route('owner.suppliers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store-alt"></i>
                    <div>{{ __('menu.suppliers') }}</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.approval') }}</span></li>

            {{-- OWNER MENU - Ubah menu Approval Order --}}
            <li class="menu-item {{ request()->routeIs('owner.orders.approval.*') ? 'active' : '' }}">
                <a href="{{ route('owner.orders.approval.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-check-circle"></i>
                    <div>{{ __('menu.order_approval') }}</div>
                    @php
                        $pendingOrderCount = \App\Models\Order::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingOrderCount > 0)
                        <span class="badge badge-center rounded-pill bg-danger ms-auto">{{ $pendingOrderCount }}</span>
                    @endif
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('owner.purchase-orders.*') ? 'active' : '' }}">
                <a href="{{ route('owner.purchase-orders.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart"></i>
                    <div>{{ __('menu.purchase_order_approval') }}</div>
                    @php
                        $pendingCount = \App\Models\PurchaseOrder::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingCount > 0)
                        <span class="badge badge-center rounded-pill bg-danger ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.transactions') }}</span></li>

            <li class="menu-item {{ request()->routeIs('owner.payments.*') ? 'active' : '' }}">
                <a href="{{ route('owner.payments.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-credit-card"></i>
                    <div>{{ __('menu.payment_verification') }}</div>
                    @php
                        $pendingPaymentsCount = \App\Models\Payment::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingPaymentsCount > 0)
                        <span
                            class="badge badge-center rounded-pill bg-danger ms-auto">{{ $pendingPaymentsCount }}</span>
                    @endif
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">{{ __('menu.history') }}</span>
            </li>

            <li class="menu-item {{ request()->routeIs('owner.orders.history.*') ? 'active' : '' }}">
                <a href="{{ route('owner.orders.history.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div>{{ __('menu.order_history') }}</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('owner.purchase-orders.history.*') ? 'active' : '' }}">
                <a href="{{ route('owner.purchase-orders.history.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-archive"></i>
                    <div>{{ __('menu.po_history') }}</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.financial') }}</span></li>

            <li class="menu-item {{ request()->routeIs('owner.financial.dashboard') ? 'active' : '' }}">
                <a href="{{ route('owner.financial.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-line-chart"></i>
                    <div>{{ __('menu.financial_dashboard') }}</div>
                </a>
            </li>

            <li
                class="menu-item {{ request()->routeIs('owner.financial.index') || request()->routeIs('owner.financial.expense.*') ? 'active' : '' }}">
                <a href="{{ route('owner.financial.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-wallet"></i>
                    <div>{{ __('menu.financial_logs') }}</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">{{ __('menu.reports') }}</span>
            </li>

            <li class="menu-item {{ request()->routeIs('owner.reports.financial') ? 'active' : '' }}">
                <a href="{{ route('owner.reports.financial') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                    <div>{{ __('menu.financial_report') }}</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('owner.reports.inventory') ? 'active' : '' }}">
                <a href="{{ route('owner.reports.inventory') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div>{{ __('menu.inventory_report') }}</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.settings') }}</span></li>

            <li class="menu-item {{ request()->routeIs('owner.settings.*') ? 'active' : '' }}">
                <a href="{{ route('owner.settings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div>{{ __('menu.system_settings') }}</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('owner.landing-settings.*') ? 'active' : '' }}">
                <a href="{{ route('owner.landing-settings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div>{{ __('menu.landing_page') }}</div>
                </a>
            </li>
        @elseif(Auth::user()->isAdmin())
            {{-- ============================================================ --}}
            {{-- ADMIN MENU --}}
            {{-- ============================================================ --}}
            <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>{{ __('menu.dashboard') }}</div>
                </a>
            </li>

            {{-- NEW: Katalog Produk --}}
            <li class="menu-item {{ request()->routeIs('catalog.*') ? 'active' : '' }}">
                <a href="{{ route('catalog.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div>{{ __('menu.product_catalog') }}</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.management') }}</span></li>

            <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>{{ __('menu.manage_users') }}</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-category"></i>
                    <div>{{ __('menu.manage_categories') }}</div>
                </a>
            </li>

            {{-- LINK PRODUK YANG BENAR --}}
            <li class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <a href="{{ route('admin.products.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-coffee"></i>
                    <div>{{ __('menu.manage_products') }}</div>
                </a>
            </li>

            {{-- LINK INVENTORY --}}
            <li class="menu-item {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                <a href="{{ route('admin.inventory.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div>{{ __('menu.manage_inventory') }}</div>
                </a>
            </li>

            {{-- LINK SUPPLIER --}}
            <li class="menu-item {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                <a href="{{ route('admin.suppliers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store-alt"></i>
                    <div>{{ __('menu.suppliers') }}</div>
                </a>
            </li>

            {{-- NEW: Purchase Order Menu --}}
            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.procurement') }}</span></li>

            <li
                class="menu-item {{ request()->routeIs('admin.purchase-orders.*') && !request()->routeIs('admin.purchase-orders.receive.*') ? 'active' : '' }}">
                <a href="{{ route('admin.purchase-orders.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-purchase-tag"></i>
                    <div>{{ __('menu.purchase_orders') }}</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.purchase-orders.receive.*') ? 'active' : '' }}">
                <a href="{{ route('admin.purchase-orders.receive.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div>{{ __('menu.receive_stock') }}</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.approval') }}</span></li>

            {{-- ADMIN MENU - Order Approval --}}
            <li class="menu-item {{ request()->routeIs('admin.orders.approval.*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.approval.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-check-circle"></i>
                    <div>{{ __('menu.order_approval') }}</div>
                    @php
                        $pendingOrderCount = \App\Models\Order::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingOrderCount > 0)
                        <span
                            class="badge badge-center rounded-pill bg-danger ms-auto">{{ $pendingOrderCount }}</span>
                    @endif
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.transactions') }}</span></li>

            <li class="menu-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <a href="{{ route('admin.payments.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-credit-card"></i>
                    <div>{{ __('menu.payment_verification') }}</div>
                    @php
                        $pendingPaymentsCount = \App\Models\Payment::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingPaymentsCount > 0)
                        <span
                            class="badge badge-center rounded-pill bg-danger ms-auto">{{ $pendingPaymentsCount }}</span>
                    @endif
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.history') }}</span></li>

            <li class="menu-item {{ request()->routeIs('admin.orders.history.*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.history.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div>{{ __('menu.order_history') }}</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.purchase-orders.history.*') ? 'active' : '' }}">
                <a href="{{ route('admin.purchase-orders.history.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-archive"></i>
                    <div>{{ __('menu.po_history') }}</div>
                </a>
            </li>
        @elseif(Auth::user()->isCustomer())
            {{-- ============================================================ --}}
            {{-- CUSTOMER MENU --}}
            {{-- ============================================================ --}}
            <li class="menu-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <a href="{{ route('customer.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>{{ __('menu.dashboard') }}</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.shopping') }}</span></li>

            {{-- NEW: Katalog Produk --}}
            <li class="menu-item {{ request()->routeIs('catalog.*') ? 'active' : '' }}">
                <a href="{{ route('catalog.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div>{{ __('menu.product_catalog') }}</div>
                </a>
            </li>


            {{-- Keranjang Belanja --}}
            <li class="menu-item {{ request()->routeIs('customer.index') ? 'active' : '' }}">
                <a href="{{ route('customer.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart"></i>
                    <div>
                        {{ __('menu.shopping_cart') }}
                        @if (auth()->user()->cart_count > 0)
                            <span
                                class="badge bg-danger rounded-pill ms-auto">{{ auth()->user()->cart_count }}</span>
                        @endif
                    </div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span
                    class="menu-header-text">{{ __('menu.orders') }}</span></li>

            {{-- CUSTOMER MENU - Pesanan Aktif --}}
            <li
                class="menu-item {{ request()->routeIs('customer.orders.*') && !request()->is('*history*') ? 'active' : '' }}">
                <a href="{{ route('customer.orders.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                    <div>{{ __('menu.my_orders') }}</div>
                    @php
                        // Count only active orders (not completed or rejected)
                        $myOrderCount = \App\Models\Order::forCustomer(auth()->id())
                            ->whereNotIn('status', ['completed', 'rejected', 'cancelled'])
                            ->count();
                    @endphp
                    @if ($myOrderCount > 0)
                        <span class="badge bg-info ms-auto">{{ $myOrderCount }}</span>
                    @endif
                </a>
            </li>

            {{-- CUSTOMER MENU - History Order --}}
            <li class="menu-item {{ request()->routeIs('customer.orders.history.*') ? 'active' : '' }}">
                <a href="{{ route('customer.orders.history.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div>{{ __('menu.order_history') }}</div>
                </a>
            </li>
        @endif

        {{-- ============================================================ --}}
        {{-- MENU UNTUK SEMUA ROLE --}}
        {{-- ============================================================ --}}
        <li class="menu-header small text-uppercase"><span class="menu-header-text">{{ __('menu.account') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <a href="{{ route('profile.edit') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div>{{ __('menu.my_profile') }}</div>
            </a>
        </li>

        <li class="menu-item">
            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                @csrf
                <button type="submit" class="menu-link w-100 text-start border-0 bg-transparent p-0"
                    style="cursor: pointer;">
                    <i class="menu-icon tf-icons bx bx-log-out"></i>
                    <div>{{ __('menu.logout') }}</div>
                </button>
            </form>
        </li>
    </ul>
</aside>
