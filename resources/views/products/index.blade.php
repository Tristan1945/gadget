@extends('layouts.app')

@section('title', 'Produk')
@section('page_heading', 'Inventaris Produk')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h5 class="font-weight-bold mb-1">Daftar Produk</h5>
            <p class="text-muted small mb-0">{{ $products->total() }} produk ditemukan</p>
        </div>
        @if (auth()->user()->role === 'admin')
            <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
                <a href="{{ route('products.export') }}" class="btn btn-outline-primary">
                    <i class="fas fa-file-export mr-2"></i>Ekspor CSV
                </a>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Tambah Produk
                </a>
            </div>
        @endif
    </div>
    <div class="card-body">
        <form method="GET" class="bg-light rounded p-3 mb-4">
            <div class="form-row">
                <div class="col-lg-4 mb-3">
                    <label for="product-search" class="small font-weight-bold">Cari Produk</label>
                    <input id="product-search" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Kode, nama, atau merek">
                </div>
                <div class="col-lg-2 mb-3">
                    <label for="category-filter" class="small font-weight-bold">Kategori</label>
                    <select id="category-filter" name="category_id" class="form-control">
                        <option value="">Semua</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 mb-3">
                    <label for="stock-filter" class="small font-weight-bold">Status Stok</label>
                    <select id="stock-filter" name="stock_status" class="form-control">
                        <option value="">Semua</option>
                        <option value="low" @selected(request('stock_status') === 'low')>Menipis (0-5)</option>
                        <option value="medium" @selected(request('stock_status') === 'medium')>Cukup (6-20)</option>
                        <option value="high" @selected(request('stock_status') === 'high')>Banyak (&gt;20)</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-3">
                    <label for="sort-filter" class="small font-weight-bold">Urutkan</label>
                    <select id="sort-filter" name="sort" class="form-control">
                        <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                        <option value="name" @selected(request('sort') === 'name')>Nama A-Z</option>
                        <option value="price_low" @selected(request('sort') === 'price_low')>Harga terendah</option>
                        <option value="price_high" @selected(request('sort') === 'price_high')>Harga tertinggi</option>
                        <option value="stock_low" @selected(request('sort') === 'stock_low')>Stok terendah</option>
                        <option value="stock_high" @selected(request('sort') === 'stock_high')>Stok tertinggi</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-3 d-flex align-items-end">
                    <button class="btn btn-primary btn-block" type="submit">
                        <i class="fas fa-filter mr-1"></i> Terapkan
                    </button>
                </div>
            </div>
            @if (request()->hasAny(['q', 'category_id', 'stock_status', 'sort']))
                <a href="{{ route('products.index') }}" class="small"><i class="fas fa-times mr-1"></i>Reset filter</a>
            @endif
        </form>

        <div class="gadget-table-wrap">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Merek</th>
                            <th>Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                @if ($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                                        class="rounded shadow-sm" style="width:64px;height:64px;object-fit:cover">
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded"
                                        style="width:64px;height:64px;background:#1a2238;color:#6d7892">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td><span class="badge badge-light border">{{ $product->product_code }}</span></td>
                            <td>
                                <a class="font-weight-bold" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                                @if ($product->description)
                                    <div class="small text-muted text-truncate" style="max-width:180px">{{ $product->description }}</div>
                                @endif
                            </td>
                            <td><span class="text-muted">{{ $product->category?->name }}</span></td>
                            <td>{{ $product->brand ?: '-' }}</td>
                            <td class="font-weight-bold">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge badge-pill {{ $product->stock <= 5 ? 'badge-danger' : ($product->stock <= 20 ? 'badge-warning' : 'badge-success') }}">
                                    {{ number_format($product->stock) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="d-inline-flex align-items-center">
                                    <a class="btn btn-sm btn-outline-primary mr-2" href="{{ route('products.show', $product) }}" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if (auth()->user()->role === 'admin')
                                        <a class="btn btn-sm btn-outline-warning mr-2" href="{{ route('products.edit', $product) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus produk {{ $product->name }}?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Belum ada produk yang cocok dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">{{ $products->links() }}</div>
    </div>
</div>
@endsection
