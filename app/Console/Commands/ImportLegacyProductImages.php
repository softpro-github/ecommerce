<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportLegacyProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-legacy-product-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy real product photos from storage/legacy_import/product_images/photos/product/{legacy_id}/ into public storage and attach them to the matching imported product (matched via SKU CSW-{legacy_id}).';

    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function handle(): int
    {
        $basePath = storage_path('legacy_import/product_images/photos/product');

        if (! is_dir($basePath)) {
            $this->error("No images found at {$basePath}");

            return self::FAILURE;
        }

        $products = Product::query()->where('sku', 'like', 'CSW-%')->get();
        $imported = 0;

        foreach ($products as $product) {
            $legacyId = Str::after($product->sku, 'CSW-');
            $productFolder = $basePath.DIRECTORY_SEPARATOR.$legacyId;

            if (! is_dir($productFolder)) {
                continue;
            }

            // Each photo lives in its own numbered subfolder (the legacy photoid).
            $photoDirs = collect(scandir($productFolder))
                ->filter(fn ($d) => is_dir($productFolder.DIRECTORY_SEPARATOR.$d) && ! in_array($d, ['.', '..']))
                ->sort(fn ($a, $b) => (int) $a <=> (int) $b)
                ->values();

            if ($photoDirs->isEmpty()) {
                continue;
            }

            // Re-importing: clear previously imported images for this product first.
            foreach ($product->images as $existing) {
                Storage::disk('public')->delete($existing->path);
                $existing->delete();
            }

            $sortOrder = 0;

            foreach ($photoDirs as $photoId) {
                $photoDir = $productFolder.DIRECTORY_SEPARATOR.$photoId;
                $files = collect(scandir($photoDir))
                    ->filter(function ($f) use ($photoDir) {
                        if (! is_file($photoDir.DIRECTORY_SEPARATOR.$f)) {
                            return false;
                        }

                        return in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), self::ALLOWED_EXTENSIONS);
                    });

                foreach ($files as $file) {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    $newName = 'products/'.$legacyId.'-'.$photoId.'-'.Str::random(6).'.'.$extension;

                    Storage::disk('public')->put(
                        $newName,
                        file_get_contents($photoDir.DIRECTORY_SEPARATOR.$file)
                    );

                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $newName,
                        'sort_order' => $sortOrder++,
                    ]);

                    $imported++;
                }
            }

            $this->info("Imported {$sortOrder} image(s) for {$product->name}");
        }

        $this->info("Done. {$imported} images imported total.");

        return self::SUCCESS;
    }
}
