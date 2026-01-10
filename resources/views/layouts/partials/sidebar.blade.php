{{-- File: resources/views/layouts/partials/sidebar.blade.php (UPDATE) --}}
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <path
                            d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                            id="path-1"></path>
                        <path
                            d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                            id="path-3"></path>
                        <path
                            d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                            id="path-4"></path>
                        <path
                            d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                            id="path-5"></path>
                    </defs>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-4.000000, -2.000000)" fill="#696cff">
                            <use xlink:href="#path-1" opacity="0.8"></use>
                            <use xlink:href="#path-3" opacity="0.6"></use>
                            <use xlink:href="#path-4"></use>
                            <use xlink:href="#path-5"></use>
                        </g>
                    </g>
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Eureka Coffee</span>
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

            {{-- CUSTOMER MENU - Ubah menu Pesanan Saya --}}
            <li class="menu-item {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
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
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" class="menu-link"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="menu-icon tf-icons bx bx-log-out"></i>
                    <div>{{ __('menu.logout') }}</div>
                </a>
            </form>
        </li>
    </ul>
</aside>
