<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManager extends Component
{
    use WithPagination;

    public ?int $viewingId = null;

    public string $statusFilter = '';

    public string $search = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function view(int $id): void
    {
        $this->viewingId = $this->viewingId === $id ? null : $id;
    }

    public function updateStatus(int $id, string $status): void
    {
        Order::findOrFail($id)->update(['status' => $status]);
        session()->flash('status', 'Order status updated.');
    }

    protected function filteredQuery()
    {
        return Order::query()
            ->with(['items', 'user'])
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->search, function ($q) {
                $term = trim($this->search);
                $q->where(function ($q) use ($term) {
                    $q->where('shipping_name', 'like', "%{$term}%")
                        ->orWhere('customer_email', 'like', "%{$term}%")
                        ->orWhere('shipping_phone', 'like', "%{$term}%")
                        ->orWhere('tx_ref', 'like', "%{$term}%")
                        ->orWhere('flutterwave_tx_id', 'like', "%{$term}%");

                    if (is_numeric($term)) {
                        $q->orWhere('id', (int) $term);
                    }
                });
            })
            ->latest();
    }

    public function render()
    {
        $orders = $this->filteredQuery()->paginate(15);

        return view('livewire.admin.order-manager', ['orders' => $orders]);
    }
}
