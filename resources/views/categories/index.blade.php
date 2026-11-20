@extends('layouts.app')

@section('title', 'Kategori')
@section('page_heading', 'Kategori Produk')

@section('content')
<div class="card">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h5 class="font-weight-bold mb-1">Daftar Kategori</h5>
            <p class="text-muted small mb-0">{{ $categories->total() }} kategori terdaftar</p>
        </div>
        @if (auth()->user()->role === 'admin')
            <a class="btn btn-primary mt-3 mt-md-0" href="{{ route('categories.create') }}">
                <i class="fas fa-plus mr-2"></i>Tambah Kategori
            </a>
        @endif
    </div>
    <div class="card-body">
        <form method="GET" class="bg-light rounded p-3 mb-4">
            <div class="form-row align-items-end">
                <div class="col-md-7 mb-3 mb-md-0">
                    <label for="category-search" class="small font-weight-bold">Cari Kategori</label>
                    <input id="category-search" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Nama atau deskripsi kategori">
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label for="status-filter" class="small font-weight-bold">Status</label>
                    <select id="status-filter" name="status" class="form-control">
                        <option value="">Semua status</option>
                        <option value="Aktif" @selected(request('status') === 'Aktif')>Aktif</option>
                        <option value="Nonaktif" @selected(request('status') === 'Nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-block" type="submit">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="gadget-table-wrap">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th class="text-center">Jumlah Produk</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="font-weight-bold">
                                <a href="{{ route('products.index', ['category_id' => $category->id]) }}">
                                    {{ $category->name }}
                                </a>
                            </td>
                            <td class="text-muted">{{ $category->description ?: '-' }}</td>
                            <td>
                                <span class="badge badge-pill {{ $category->status === 'Aktif' ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $category->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-light border px-3 py-2">{{ $category->products_count }}</span>
                            </td>
                            <td class="text-right">
                                @if (auth()->user()->role === 'admin')
                                    <div class="d-inline-flex align-items-center">
                                        <a class="btn btn-sm btn-outline-warning mr-2" href="{{ route('categories.edit', $category) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('categories.destroy', $category) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Hapus kategori {{ $category->name }}?')"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">Lihat saja</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-tags fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Belum ada kategori yang cocok.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">{{ $categories->links() }}</div>
    </div>
</div>
@endsection
