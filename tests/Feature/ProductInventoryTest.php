<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_create_product(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/products/create');

        $response->assertForbidden();
    }

    public function test_admin_can_store_product_with_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Smartphone',
            'description' => null,
            'status' => 'Aktif',
        ]);

        $response = $this->actingAs($admin)->post('/products', [
            'category_id' => $category->id,
            'product_code' => 'P0001',
            'name' => 'Gadget Test',
            'brand' => 'Brand Test',
            'price' => '2500000',
            'stock' => 12,
            'description' => 'Produk untuk pengujian',
            'image' => UploadedFile::fake()->image('gadget.jpg'),
        ]);

        $response->assertRedirect('/products');
        $product = Product::where('product_code', 'P0001')->firstOrFail();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_replacing_product_image_removes_old_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Laptop',
            'status' => 'Aktif',
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'product_code' => 'P0002',
            'name' => 'Laptop Test',
            'brand' => 'Brand Test',
            'price' => 10000000,
            'stock' => 5,
            'description' => null,
            'image' => 'product/old.jpg',
        ]);
        Storage::disk('public')->put('product/old.jpg', 'old image');

        $response = $this->actingAs($admin)->put('/products/'.$product->id, [
            'category_id' => $category->id,
            'product_code' => 'P0002',
            'name' => 'Laptop Test',
            'brand' => 'Brand Test',
            'price' => '11000000',
            'stock' => 7,
            'description' => null,
            'image' => UploadedFile::fake()->image('new.jpg'),
        ]);

        $response->assertRedirect('/products');
        $product->refresh();
        $this->assertNotSame('product/old.jpg', $product->image);
        Storage::disk('public')->assertMissing('product/old.jpg');
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_destroy_product_removes_its_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Tablet',
            'status' => 'Aktif',
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'product_code' => 'P0003',
            'name' => 'Tablet Test',
            'price' => 5000000,
            'stock' => 3,
            'image' => 'product/delete.jpg',
        ]);
        Storage::disk('public')->put('product/delete.jpg', 'delete image');

        $response = $this->actingAs($admin)->delete('/products/'.$product->id);

        $response->assertRedirect('/products');
        $this->assertModelMissing($product);
        Storage::disk('public')->assertMissing('product/delete.jpg');
    }

    public function test_stock_must_fit_database_unsigned_integer_limit(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Aksesoris',
            'status' => 'Aktif',
        ]);

        $response = $this->actingAs($admin)->post('/products', [
            'category_id' => $category->id,
            'product_code' => 'P0004',
            'name' => 'Aksesoris Test',
            'price' => 100000,
            'stock' => 4294967296,
        ]);

        $response->assertSessionHasErrors('stock');
        $this->assertDatabaseMissing('products', ['product_code' => 'P0004']);
    }

    public function test_customer_can_view_product_detail(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $category = Category::create([
            'name' => 'Audio',
            'status' => 'Aktif',
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'product_code' => 'P0005',
            'name' => 'Headset Test',
            'price' => 750000,
            'stock' => 8,
        ]);

        $response = $this->actingAs($customer)->get('/products/'.$product->id);

        $response->assertOk();
        $response->assertSee('Headset Test');
        $response->assertSee('P0005');
    }

    public function test_admin_can_export_products_as_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Smartwatch',
            'status' => 'Aktif',
        ]);
        Product::create([
            'category_id' => $category->id,
            'product_code' => 'P0006',
            'name' => 'Jam Pintar',
            'brand' => 'Gadget',
            'price' => 1500000,
            'stock' => 14,
        ]);

        $response = $this->actingAs($admin)->get('/products/export/csv');

        $response->assertOk();
        $response->assertDownload();
        $this->assertStringContainsString('Jam Pintar', $response->streamedContent());
    }
}
