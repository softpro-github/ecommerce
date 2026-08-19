<?php

namespace App\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CouponManager extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    #[Validate('required|string|max:50')]
    public string $code = '';

    #[Validate('required|in:percent,fixed')]
    public string $type = 'percent';

    #[Validate('required|numeric|min:0')]
    public string $value = '';

    #[Validate('nullable|numeric|min:0')]
    public string $min_order_amount = '';

    #[Validate('nullable|integer|min:1')]
    public string $usage_limit = '';

    #[Validate('nullable|date')]
    public string $expires_at = '';

    public bool $enabled = true;

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $coupon = Coupon::findOrFail($id);
        $this->editingId = $coupon->id;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = (string) $coupon->value;
        $this->min_order_amount = (string) $coupon->min_order_amount;
        $this->usage_limit = (string) $coupon->usage_limit;
        $this->expires_at = $coupon->expires_at?->format('Y-m-d') ?? '';
        $this->enabled = $coupon->enabled;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'min_order_amount' => $this->min_order_amount !== '' ? $this->min_order_amount : null,
            'usage_limit' => $this->usage_limit !== '' ? $this->usage_limit : null,
            'expires_at' => $this->expires_at !== '' ? $this->expires_at : null,
            'enabled' => $this->enabled,
        ];

        if ($this->editingId) {
            Coupon::findOrFail($this->editingId)->update($data);
        } else {
            Coupon::create($data);
        }

        session()->flash('status', 'Coupon saved.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Coupon::findOrFail($id)->delete();
        session()->flash('status', 'Coupon deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'code', 'value', 'min_order_amount', 'usage_limit', 'expires_at']);
        $this->type = 'percent';
        $this->enabled = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.coupon-manager', [
            'coupons' => Coupon::latest()->get(),
        ]);
    }
}
