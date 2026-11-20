<nav class="navbar navbar-expand navbar-dark gadget-topbar topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" type="button">
        <i class="fa fa-bars"></i>
    </button>

    <form action="{{ route('products.index') }}" method="GET"
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control border-0 small"
                placeholder="Cari kode, nama, atau merek..." aria-label="Cari produk">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="inventoryAlerts" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                @if ($lowStockCount > 0)
                    <span class="badge badge-danger badge-counter">{{ $lowStockCount }}</span>
                @endif
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="inventoryAlerts">
                <h6 class="dropdown-header">Peringatan Inventaris</h6>
                @forelse ($topLowStockItems as $item)
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('products.show', $item) }}">
                        <div class="mr-3">
                            <div class="icon-circle bg-danger">
                                <i class="fas fa-box-open text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">{{ $item->category?->name }}</div>
                            <span class="font-weight-bold">{{ $item->name }}</span>
                            <span class="small text-danger d-block">Sisa {{ $item->stock }} unit</span>
                        </div>
                    </a>
                @empty
                    <span class="dropdown-item small text-gray-500">Tidak ada stok kritis.</span>
                @endforelse
                <a class="dropdown-item text-center small text-primary" href="{{ route('products.index', ['stock_status' => 'low']) }}">
                    Lihat semua stok menipis
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-200 small">
                    {{ auth()->user()?->name ?? 'Guest' }}
                    <span class="d-block text-xs text-uppercase opacity-75">{{ auth()->user()?->role }}</span>
                </span>
                <div class="avatar-profile">{{ strtoupper(substr(auth()->user()?->name ?? 'G', 0, 1)) }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil Saya
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Keluar
                </a>
            </div>
        </li>
    </ul>
</nav>
