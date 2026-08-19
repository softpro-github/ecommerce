<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public ?int $viewingId = null;

    private const PAID_STATUSES = ['paid', 'processing', 'shipped', 'delivered'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function view(int $id): void
    {
        $this->viewingId = $this->viewingId === $id ? null : $id;
    }

    public function render()
    {
        $customers = User::query()
            ->where('role', 'customer')
            ->withCount('orders')
            ->withSum(['orders as total_spent' => function ($q) {
                $q->whereIn('status', self::PAID_STATUSES);
            }], 'total')
            ->when($this->search, function ($q) {
                $term = $this->search;
                $q->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%");
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        $viewingOrders = $this->viewingId
            ? Order::where('user_id', $this->viewingId)->latest()->get()
            : collect();

        return view('livewire.admin.customer-manager', [
            'customers' => $customers,
            'viewingOrders' => $viewingOrders,
        ]);
    }
}
