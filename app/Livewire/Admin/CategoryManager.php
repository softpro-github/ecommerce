<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CategoryManager extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|exists:categories,id')]
    public ?int $parent_id = null;

    #[Validate('nullable|string')]
    public string $description = '';

    public bool $enabled = true;

    #[Validate('integer|min:0')]
    public string $sort_order = '0';

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->parent_id = $category->parent_id;
        $this->description = (string) $category->description;
        $this->enabled = $category->enabled;
        $this->sort_order = (string) $category->sort_order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $slugBase = Str::slug($this->name);
        $slug = $slugBase;
        $i = 1;
        while (Category::query()->where('slug', $slug)->where('id', '!=', $this->editingId)->exists()) {
            $slug = $slugBase.'-'.(++$i);
        }

        $data = [
            'name' => $this->name,
            'slug' => $slug,
            'parent_id' => $this->parent_id,
            'description' => $this->description ?: null,
            'enabled' => $this->enabled,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
        } else {
            Category::create($data);
        }

        session()->flash('status', 'Category saved.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
        session()->flash('status', 'Category deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'parent_id', 'description', 'sort_order']);
        $this->enabled = true;
        $this->sort_order = '0';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categoryList' => Category::orderBy('parent_id')->orderBy('sort_order')->get(),
        ]);
    }
}
