@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page_heading', 'Edit Kategori')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="mb-4">
            <h5 class="font-weight-bold text-gray-800 mb-1">Perbarui Kategori</h5>
            <p class="text-muted small mb-0">Ubah informasi kategori yang dipilih.</p>
        </div>
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="font-weight-bold" for="name">Nama Kategori</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $category->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="status">Status</label>
                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="Aktif" @selected(old('status', $category->status) === 'Aktif')>Aktif</option>
                    <option value="Nonaktif" @selected(old('status', $category->status) === 'Nonaktif')>Nonaktif</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-end mt-4">
                <a href="{{ route('categories.index') }}" class="btn btn-light mb-2 mb-sm-0 mr-sm-2">Batal</a>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save mr-2"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection