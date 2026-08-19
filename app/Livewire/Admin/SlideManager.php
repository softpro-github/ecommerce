<?php

namespace App\Livewire\Admin;

use App\Models\Slide;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SlideManager extends Component
{
    use WithFileUploads;

    public string $type = 'hero';

    public bool $showForm = false;

    public ?int $editingId = null;

    public $image = null;

    public string $heading = '';

    public string $cta_text = '';

    public string $cta_link = '';

    public string $sort_order = '0';

    public bool $enabled = true;

    public function switchType(string $type): void
    {
        $this->type = $type;
        $this->cancel();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->sort_order = (string) ((Slide::query()->where('type', $this->type)->max('sort_order') ?? 0) + 1);
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $slide = Slide::findOrFail($id);

        $this->editingId = $slide->id;
        $this->heading = (string) $slide->heading;
        $this->cta_text = (string) $slide->cta_text;
        $this->cta_link = (string) $slide->cta_link;
        $this->sort_order = (string) $slide->sort_order;
        $this->enabled = $slide->enabled;
        $this->image = null;
        $this->showForm = true;
    }

    public function save(): void
    {
        $rules = [
            'heading' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'enabled' => 'boolean',
            'image' => $this->editingId ? 'nullable|image|max:8192' : 'required|image|max:8192',
        ];

        $this->validate($rules);

        $data = [
            'type' => $this->type,
            'heading' => $this->heading ?: null,
            'cta_text' => $this->cta_text ?: null,
            'cta_link' => $this->cta_link ?: null,
            'sort_order' => $this->sort_order,
            'enabled' => $this->enabled,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('slides', 'public');
        }

        if ($this->editingId) {
            $slide = Slide::findOrFail($this->editingId);
            $oldPath = $slide->image_path;
            $slide->update($data);

            if ($this->image && $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        } else {
            Slide::create($data);
        }

        session()->flash('status', 'Slide saved.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $slide = Slide::findOrFail($id);
        Storage::disk('public')->delete($slide->image_path);
        $slide->delete();

        session()->flash('status', 'Slide deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'image', 'heading', 'cta_text', 'cta_link']);
        $this->sort_order = '0';
        $this->enabled = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $slides = Slide::query()
            ->where('type', $this->type)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.admin.slide-manager', ['slides' => $slides]);
    }
}
