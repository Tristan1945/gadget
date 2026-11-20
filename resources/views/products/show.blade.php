@extends('layouts.app')

@section('title', $product->name)
@section('page_heading', 'Detail Produk')

@section('content')
<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-center p-4" style="min-height:380px">
                @if ($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                        class="rounded shadow-lg" style="max-width:100%;max-height:420px;object-fit:contain">
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-camera fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada gambar produk</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <span class="badge badge-light border mb-2">{{ $product->product_code }}</span>
                        <h3 class="font-weight-bold mb-1">{{ $product->name }}</h3>
                        <p class="text-muted mb-0">{{ $product->brand ?: 'Merek belum ditentukan' }}</p>
                    </div>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                </div>

                <h4 class="font-weight-bold" style="color:#a99fff">
                    Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                </h4>

                <div class="row mt-4 mb-4">
                    <div class="col-sm-6 mb-3">
                        <div class="bg-light rounded p-3">
                            <div class="small text-muted">Kategori</div>
                            <div class="font-weight-bold">{{ $product->category?->name ?: '-' }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="bg-light rounded p-3">
                            <div class="small text-muted">Ketersediaan</div>
                            <span class="badge badge-pill {{ $product->stock <= 5 ? 'badge-danger' : ($product->stock <= 20 ? 'badge-warning' : 'badge-success') }}">
                                {{ number_format($product->stock) }} unit
                            </span>
                        </div>
                    </div>
                </div>

                <h6 class="font-weight-bold">Deskripsi</h6>
                <p class="text-muted">{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
