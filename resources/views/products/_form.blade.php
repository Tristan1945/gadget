<div class="row g-3">
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold" for="product_code">Kode Produk</label>
            <input id="product_code" name="product_code" class="form-control @error('product_code') is-invalid @enderror" value="{{ old('product_code', $product->product_code ?? '') }}" placeholder="Contoh: P0001" required>
            @error('product_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold" for="name">Nama Produk</label>
            <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name ?? '') }}" placeholder="Contoh: Smartphone" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold" for="category_id">Kategori</label>
            <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
            <option value="">Pilih kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold" for="brand">Merek</label>
            <input id="brand" name="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand', $product->brand ?? '') }}" placeholder="Contoh: Samsung">
            @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold" for="price">Harga</label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                <input id="price" type="number" min="0" max="9999999999999.99" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price ?? '') }}" placeholder="0" required>
            </div>
            @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold" for="stock">Stok</label>
            <input id="stock" type="number" min="0" max="4294967295" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock ?? 0) }}" placeholder="0" required>
            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-group mb-0">
            <label class="font-weight-bold" for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Tambahkan deskripsi produk">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold" for="image">Gambar</label>
            <input id="image" type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if(!empty($product?->image))
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" class="img-thumbnail" style="max-width: 200px;">
                </div>
            @else
                <div class="mt-2 text-secondary small">Belum ada gambar yang diunggah.</div>
            @endif
        </div>
    </div>
</div>
