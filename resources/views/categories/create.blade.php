@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page_heading', 'Tambah Kategori')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="mb-4">
            <h5 class="font-weight-bold text-gray-800 mb-1">Informasi Kategori</h5>
            <p class="text-muted small mb-0">Isi data kategori dengan lengkap.</p>
        </div>
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="form-group">
                <label class="font-weight-bold" for="name">Nama Kategori</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Smartphone">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Tambahkan deskripsi kategori">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="status">Status</label>
                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="Aktif" @selected(old('status', 'Aktif') === 'Aktif')>Aktif</option>
                    <option value="Nonaktif" @selected(old('status') === 'Nonaktif')>Nonaktif</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-end mt-4">
                <a href="{{ route('categories.index') }}" class="btn btn-light mb-2 mb-sm-0 mr-sm-2">Batal</a>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save mr-2"></i>Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection