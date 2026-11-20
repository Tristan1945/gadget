@extends('layouts.app')

@section('title', 'Edit Produk')
@section('page_heading', 'Edit Produk')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="mb-4">
            <h5 class="font-weight-bold text-gray-800 mb-1">Perbarui Produk</h5>
            <p class="text-muted small mb-0">Ubah informasi produk yang dipilih.</p>
        </div>
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('products._form')
            <div class="d-flex flex-column flex-sm-row justify-content-end mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-light mb-2 mb-sm-0 mr-sm-2">Batal</a>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save mr-2"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection