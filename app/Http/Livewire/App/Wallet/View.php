<?php

namespace App\Http\Livewire\App\Wallet;

use App\Models\Wallet;
use Livewire\Component;
use Livewire\WithPagination;

class View extends Component
{
    public Wallet $wallet;

    public $date = '';

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function updatingDate()
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
        return $this->wallet->transactions()->deposits()->filter(["date" => $this->date])->latest()->paginate(11);
    }
}
