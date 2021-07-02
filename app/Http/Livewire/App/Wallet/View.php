<?php

namespace App\Http\Livewire\App\Wallet;

use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class View extends Component
{
    public Wallet $wallet;

    public $date = '';
    public $status = '';

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function updatingDate()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function mount(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function render()
    {
        return view('livewire.app.wallet.view', ['transactions' => $this->depositList()]);
    }

    public function depositList()
    {
        return $this->wallet->transactions()->deposits()->filter(["date" => $this->date, "status" => $this->status])->latest()->paginate(11);
    }
}
