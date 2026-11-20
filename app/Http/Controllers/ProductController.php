<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->string('q')->toString();
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('product_code', 'like', "%{$keyword}%")
                        ->orWhere('brand', 'like', "%{$keyword}%");
                });
            })
            ->when(
                $request->filled('category_id'),
                fn ($q) => $q->where('category_id', $request->category_id)
            )
            ->when($request->filled('stock_status'), function ($query) use ($request) {
                if ($request->stock_status === 'low') {
                    $query->where('stock', '<=', 5);
                } elseif ($request->stock_status === 'medium') {
                    $query->whereBetween('stock', [6, 20]);
                } elseif ($request->stock_status === 'high') {
                    $query->where('stock', '>', 20);
                }
            })
            ->when(
                in_array($request->input('sort'), ['latest', 'name', 'price_low', 'price_high', 'stock_low', 'stock_high'], true),
                function ($query) use ($request) {
                    match ($request->input('sort')) {
                        'name' => $query->orderBy('name'),
                        'price_low' => $query->orderBy('price'),
                        'price_high' => $query->orderByDesc('price'),
                        'stock_low' => $query->orderBy('stock'),
                        'stock_high' => $query->orderByDesc('stock'),
                        default => $query->latest(),
                    };
                },
                fn ($query) => $query->latest()
            )
            ->paginate(10)
            ->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');

        return view('products.show', compact('product'));
    }

    public function export(): StreamedResponse
    {
        $filename = 'produk-gadget-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function (): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Kode', 'Nama', 'Kategori', 'Merek', 'Harga', 'Stok', 'Deskripsi']);

            Product::with('category')->orderBy('name')->chunk(500, function ($products) use ($output): void {
                foreach ($products as $product) {
                    fputcsv($output, [
                        $product->product_code,
                        $product->name,
                        $product->category?->name,
                        $product->brand,
                        $product->price,
                        $product->stock,
                        $product->description,
                    ]);
                }
            });

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'product_code' => ['required', 'string', 'max:50', 'unique:products,product_code'],
            'name' => ['required', 'string', 'max:150'],
            'brand' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'extensions:jpeg,png,jpg,webp', 'max:5048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('product', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'product_code' => ['required', 'string', 'max:50', Rule::unique('products', 'product_code')->ignore($product->id)],
            'name' => ['required', 'string', 'max:150'],
            'brand' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'extensions:jpeg,png,jpg,webp', 'max:5048'],
        ]);

        $oldImage = $product->image;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('product', 'public');
        }

        $product->update($validated);

        if ($request->hasFile('image') && $oldImage && $oldImage !== $product->image) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $image = $product->image;

        $product->delete();

        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
