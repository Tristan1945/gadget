<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            User::updateOrCreate(
                ['email' => 'test@example.com'],
                ['name' => 'Test User', 'password' => 'password']
            );

            Product::query()->delete();
            Category::query()->delete();

            $categoryDefinitions = [
                ['name' => 'Minuman', 'count' => 1, 'stock' => 10],
                ['name' => 'Smartphone', 'count' => 40, 'stock' => 1100],
                ['name' => 'Laptop', 'count' => 60, 'stock' => 1400],
                ['name' => 'Tablet', 'count' => 75, 'stock' => 1700],
                ['name' => 'Aksesoris', 'count' => 90, 'stock' => 1800],
                ['name' => 'Audio', 'count' => 105, 'stock' => 1900],
                ['name' => 'Smartwatch', 'count' => 129, 'stock' => 2090],
            ];

            $productNumber = 1;

            foreach ($categoryDefinitions as $categoryIndex => $definition) {
                $category = Category::create([
                    'name' => $definition['name'],
                    'description' => 'Kategori '.$definition['name'],
                    'status' => 'Aktif',
                ]);

                $stocks = $this->distributeStock(
                    $definition['count'],
                    $definition['stock'],
                    $categoryIndex === 1
                );

                foreach ($stocks as $stock) {
                    Product::create([
                        'category_id' => $category->id,
                        'product_code' => sprintf('P%04d', $productNumber),
                        'name' => 'Produk Gadget '.$productNumber,
                        'brand' => 'Brand '.(1 + (($productNumber - 1) % 10)),
                        'price' => 100000 + (($productNumber - 1) % 50) * 25000,
                        'stock' => $stock,
                        'description' => 'Data produk gadget',
                    ]);

                    $productNumber++;
                }
            }
        });
    }

    /**
     * @return list<int>
     */
    private function distributeStock(int $count, int $total, bool $includeLowStock): array
    {
        $stocks = [];

        if ($includeLowStock) {
            for ($stock = 1; $stock <= 5; $stock++) {
                $stocks = array_merge($stocks, array_fill(0, 3, $stock));
            }
        }

        $remainingCount = $count - count($stocks);
        $remainingTotal = $total - array_sum($stocks);
        $baseStock = intdiv($remainingTotal, $remainingCount);
        $remainder = $remainingTotal % $remainingCount;

        for ($index = 0; $index < $remainingCount; $index++) {
            $stocks[] = $baseStock + ($index < $remainder ? 1 : 0);
        }

        return $stocks;
    }
}
