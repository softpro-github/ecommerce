<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportLegacyCatalog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-legacy-catalog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import real categories and products from the legacy AbanteCart "citystylewears" database, skipping demo/seed cruft that has no products attached.';

    /** @var array<int, Category> legacy categoryid => new Category */
    private array $categoryMap = [];

    public function handle(): int
    {
        $legacyCategories = DB::connection('legacy')->table('categories')->get()->keyBy('categoryid');

        $realCategoryIds = DB::connection('legacy')->table('category_products')
            ->distinct()
            ->pluck('categoryid')
            ->all();

        // Pull in ancestors so the hierarchy stays intact.
        $realSet = [];
        foreach ($realCategoryIds as $id) {
            $current = $legacyCategories->get($id);
            while ($current) {
                $realSet[$current->categoryid] = true;
                $current = $current->parentid && $current->parentid != 0
                    ? $legacyCategories->get($current->parentid)
                    : null;
            }
        }
        $realCategoryIds = array_keys($realSet);

        $this->info('Importing '.count($realCategoryIds).' real categories (skipping demo/seed categories with no linked products)...');

        foreach ($realCategoryIds as $id) {
            $this->importCategory($id, $legacyCategories);
        }

        $featuredIds = DB::connection('legacy')->table('featured_products')
            ->where('enabled', 1)
            ->distinct()
            ->pluck('productid')
            ->all();

        $productCategoryLinks = DB::connection('legacy')->table('category_products')
            ->whereIn('categoryid', $realCategoryIds)
            ->orderByRaw("main = 'Y' desc")
            ->get()
            ->groupBy('productid');

        $legacyProducts = DB::connection('legacy')->table('products')
            ->whereIn('productid', $productCategoryLinks->keys())
            ->where('deleted', 0)
            ->get();

        $imported = 0;

        foreach ($legacyProducts as $legacyProduct) {
            $primaryLink = $productCategoryLinks->get($legacyProduct->productid)->first();
            $category = $this->categoryMap[$primaryLink->categoryid] ?? null;

            $slug = Str::slug($legacyProduct->cleanurl ?: $legacyProduct->name);
            $listPrice = (float) $legacyProduct->list_price;
            $price = (float) $legacyProduct->price;

            Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category?->id,
                    'brand_id' => null,
                    'name' => html_entity_decode($legacyProduct->name, ENT_QUOTES),
                    'description' => html_entity_decode(trim(strip_tags((string) $legacyProduct->descr)), ENT_QUOTES) ?: null,
                    'sku' => 'CSW-'.$legacyProduct->productid,
                    'price' => $price,
                    'compare_at_price' => $listPrice > $price ? $listPrice : null,
                    'stock_qty' => max(0, (int) $legacyProduct->avail),
                    'status' => $legacyProduct->status === '1' ? 'active' : 'draft',
                    'featured' => in_array($legacyProduct->productid, $featuredIds, true),
                    'bestseller' => false,
                ]
            );

            $imported++;
        }

        $this->info("Imported {$imported} products across ".count($this->categoryMap).' categories.');

        return self::SUCCESS;
    }

    private function importCategory(int $legacyId, $legacyCategories): Category
    {
        if (isset($this->categoryMap[$legacyId])) {
            return $this->categoryMap[$legacyId];
        }

        $row = $legacyCategories->get($legacyId);

        $parent = null;
        if ($row->parentid && $row->parentid != 0) {
            $parent = $this->importCategory((int) $row->parentid, $legacyCategories);
        }

        $slug = Str::slug($row->cleanurl ?: $row->title);

        $category = Category::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'parent_id' => $parent?->id,
                'name' => html_entity_decode(str_replace('_', ' ', $row->title), ENT_QUOTES),
                'description' => html_entity_decode(trim(strip_tags((string) $row->description)), ENT_QUOTES) ?: null,
                'enabled' => (bool) $row->enabled,
                'sort_order' => (int) $row->orderby,
            ]
        );

        return $this->categoryMap[$legacyId] = $category;
    }
}
