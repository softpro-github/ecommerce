<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use Livewire\Component;

class FaqManager extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $question = '';

    public string $answer = '';

    public string $sort_order = '0';

    public bool $enabled = true;

    public function create(): void
    {
        $this->resetForm();
        $this->sort_order = (string) ((Faq::query()->max('sort_order') ?? 0) + 1);
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $faq = Faq::findOrFail($id);

        $this->editingId = $faq->id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->sort_order = (string) $faq->sort_order;
        $this->enabled = $faq->enabled;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'required|integer|min:0',
            'enabled' => 'boolean',
        ]);

        $data = [
            'question' => $this->question,
            'answer' => $this->answer,
            'sort_order' => $this->sort_order,
            'enabled' => $this->enabled,
        ];

        if ($this->editingId) {
            Faq::findOrFail($this->editingId)->update($data);
        } else {
            Faq::create($data);
        }

        session()->flash('status', 'FAQ saved.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Faq::findOrFail($id)->delete();
        session()->flash('status', 'FAQ deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'question', 'answer']);
        $this->sort_order = '0';
        $this->enabled = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.faq-manager', [
            'faqs' => Faq::orderBy('sort_order')->get(),
        ]);
    }
}
