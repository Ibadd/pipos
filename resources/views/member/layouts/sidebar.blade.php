<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('auth.index') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2">
                <img src="{{ asset('images/pitagoras.png') }}" alt="{{ env('APP_NAME') }}" class="img-fluid">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    {{-- <div class="menu-inner-shadow"></div> --}}

    <ul class="menu-inner py-1 mt-2">
        <!-- Dashboard -->
        <li class="menu-item {{ menuAktif('') }}">
            <a href="{{ route('auth.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-pie-chart"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Aktivitas</span></li>
        @haspermission('KASIR_READ')
            <li class="menu-item {{ menuAktif('kasir') }}">
                <a href="{{ route('kasir.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money-withdraw"></i>
                    <div data-i18n="Analytics">Kasir </div>
                </a>
            </li>
        @endhaspermission

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Produk</span></li>
        @haspermission('PRODUK_READ')
            <li class="menu-item {{ menuAktif('produk') }}">
                <a href="{{ route('produk.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-data"></i>
                    <div data-i18n="Analytics">Produk</div>
                </a>
            </li>
        @endhaspermission

        @haspermission('KATEGORI_READ')
            <li class="menu-item {{ menuAktif('kategori') }}">
                <a href="{{ route('kategori.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-category-alt"></i>
                    <div data-i18n="Analytics">Kategori</div>
                </a>
            </li>
        @endhaspermission

        @haspermission('SUPLIER_READ')
            <li class="menu-item {{ menuAktif('suplier') }}">
                <a href="{{ route('suplier.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div data-i18n="Analytics">Suplier</div>
                </a>
            </li>
        @endhaspermission

        @haspermission('UNIT_READ')
            <li class="menu-item {{ menuAktif('unit') }}">
                <a href="{{ route('unit.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cable-car"></i>
                    <div data-i18n="Analytics">Unit</div>
                </a>
            </li>
        @endhaspermission


        <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Member</span></li>
        @haspermission('MEMBER_READ')
            <li class="menu-item {{ menuAktif('member') }}">
                <a href="{{ route('member.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user-account"></i>
                    <div data-i18n="Analytics">Member</div>
                </a>
            </li>
        @endhaspermission

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Report</span></li>
        @haspermission('MEMBER_READ')
            <li class="menu-item {{ menuAktif('stokin') }}">
                <a href="{{ route('stokin.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-add"></i>
                    <div data-i18n="Analytics">Stok Masuk</div>
                </a>
            </li>
        @endhaspermission

        @haspermission('MEMBER_READ')
            <li class="menu-item {{ menuAktif('stokout') }}">
                <a href="{{ route('stokout.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-link-external"></i>
                    <div data-i18n="Analytics">Stok Keluar</div>
                </a>
            </li>
        @endhaspermission


        <li class="menu-header small text-uppercase"><span class="menu-header-text">Pengaturan</span></li>
        @haspermission('BANNER_READ')
            <li class="menu-item {{ menuAktif('banner') }}">
                <a href="{{ route('banner.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-image"></i>
                    <div data-i18n="Analytics">Banner</div>
                </a>
            </li>
        @endhaspermission

        @haspermission('PENGATURAN_READ')
            <li class="menu-item {{ menuAktif('pengaturan') }}">
                <a href="{{ route('pengaturan.show') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Analytics">Pengaturan</div>
                </a>
            </li>
        @endhaspermission

    </ul>
</aside>
