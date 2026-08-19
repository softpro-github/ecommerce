<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithFileUploads, WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('nullable|exists:categories,id')]
    public ?int $category_id = null;

    #[Validate('nullable|string|max:255')]
    public string $sku = '';

    #[Validate('required|numeric|min:0')]
    public string $price = '';

    #[Validate('nullable|numeric|min:0')]
    public string $compare_at_price = '';

    #[Validate('required|integer|min:0')]
    public string $stock_qty = '0';

    #[Validate('required|in:active,draft,sold_out,archived')]
    public string $status = 'draft';

    public bool $featured = false;

    public bool $bestseller = false;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $newImages = [];

    public array $variants = [];

    public array $sizeOptions = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'];

    public array $selectedSizes = [];

    public string $quickColors = '';

    public string $search = '';

    protected function rulesForVariants(): array
    {
        return [
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.stock_qty' => 'nullable|integer|min:0',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $product = Product::with('variants')->findOrFail($id);

        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->description = (string) $product->description;
        $this->category_id = $product->category_id;
        $this->sku = (string) $product->sku;
        $this->price = (string) $product->price;
        $this->compare_at_price = (string) $product->compare_at_price;
        $this->stock_qty = (string) $product->stock_qty;
        $this->status = $product->status;
        $this->featured = $product->featured;
        $this->bestseller = $product->bestseller;
        $this->variants = $product->variants->map(fn ($v) => [
            'id' => $v->id,
            'size' => $v->size,
            'color' => $v->color,
            'stock_qty' => $v->stock_qty,
        ])->toArray();
        $this->selectedSizes = collect($this->variants)
            ->filter(fn ($v) => $v['size'] && ! $v['color'])
            ->pluck('size')
            ->values()
            ->all();
        $this->newImages = [];
        $this->quickColors = '';
        $this->showForm = true;
    }

    public function addVariantRow(): void
    {
        $this->variants[] = ['id' => null, 'size' => '', 'color' => '', 'stock_qty' => 0];
    }

    public function updatedSelectedSizes(): void
    {
        // Drop size-only rows (no color) for sizes that are no longer checked, deleting any saved variant.
        $toRemove = collect($this->variants)
            ->filter(fn ($v) => $v['size'] && ! $v['color'] && ! in_array($v['size'], $this->selectedSizes, true));

        foreach ($toRemove as $variant) {
            if ($variant['id'] ?? null) {
                ProductVariant::query()->where('id', $variant['id'])->delete();
            }
        }

        $this->variants = collect($this->variants)
            ->reject(fn ($v) => $v['size'] && ! $v['color'] && ! in_array($v['size'], $this->selectedSizes, true))
            ->values()
            ->all();

        // Add rows for newly checked sizes that don't already have one.
        $existingSizes = collect($this->variants)
            ->filter(fn ($v) => $v['size'] && ! $v['color'])
            ->pluck('size')
            ->all();

        foreach ($this->selectedSizes as $size) {
            if (! in_array($size, $existingSizes, true)) {
                $this->variants[] = ['id' => null, 'size' => $size, 'color' => '', 'stock_qty' => 0];
            }
        }
    }

    public function addQuickColors(): void
    {
        foreach ($this->splitTags($this->quickColors) as $color) {
            $this->variants[] = ['id' => null, 'size' => '', 'color' => $color, 'stock_qty' => 0];
        }

        $this->quickColors = '';
    }

    /**
     * @return array<int, string>
     */
    private function splitTags(string $value): array
    {
        return collect(explode(',', $value))
            ->map(fn ($v) => trim($v))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function removeVariantRow(int $index): void
    {
        if (isset($this->variants[$index]['id'])) {
            ProductVariant::query()->where('id', $this->variants[$index]['id'])->delete();
        }

        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function save(): void
    {
        $this->validate();
        $this->validate($this->rulesForVariants());

        $slugBase = Str::slug($this->name);
        $slug = $slugBase;
        $i = 1;
        while (Product::query()->where('slug', $slug)->where('id', '!=', $this->editingId)->exists()) {
            $slug = $slugBase.'-'.(++$i);
        }

        $data = [
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description ?: null,
            'category_id' => $this->category_id,
            'sku' => $this->sku ?: null,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price !== '' ? $this->compare_at_price : null,
            'stock_qty' => $this->stock_qty,
            'status' => $this->status,
            'featured' => $this->featured,
            'bestseller' => $this->bestseller,
        ];

        if ($this->editingId) {
            $product = Product::findOrFail($this->editingId);
            $product->update($data);
        } else {
            $product = Product::create($data);
        }

        foreach ($this->variants as $variant) {
            if (($variant['size'] ?? '') === '' && ($variant['color'] ?? '') === '') {
                continue;
            }

            ProductVariant::query()->updateOrCreate(
                ['id' => $variant['id'] ?? null, 'product_id' => $product->id],
                [
                    'size' => $variant['size'] ?: null,
                    'color' => $variant['color'] ?: null,
                    'stock_qty' => $variant['stock_qty'] ?: 0,
                ]
            );
        }

        foreach ($this->newImages as $image) {
            $path = $image->store('products', 'public');
            ProductImage::create(['product_id' => $product->id, 'path' => $path]);
        }

        session()->flash('status', 'Product saved.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function deleteImage(int $imageId): void
    {
        $image = ProductImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->path);
        $image->delete();
    }

    public function delete(int $id): void
    {
        $product = Product::findOrFail($id);
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $product->delete();

        session()->flash('status', 'Product deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'name', 'description', 'category_id', 'sku',
            'price', 'compare_at_price', 'stock_qty', 'status', 'featured', 'bestseller',
            'newImages', 'variants', 'selectedSizes', 'quickColors',
        ]);
        $this->status = 'draft';
        $this->stock_qty = '0';
        $this->resetErrorBag();
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category', 'images'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.product-manager', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
