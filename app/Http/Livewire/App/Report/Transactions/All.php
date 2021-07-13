<?php

namespace App\Http\Livewire\App\Report\Transactions;

use App\Exports\TransactionExport;
use App\Exports\TransactionReceipt;
use App\Http\Utils\Resource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class All extends Component
{
    public $date = '';
    public $status = '';
    public $wallet = '';
    public $wallets;
    public $initiator = '';
    public $service = '';

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->wallets = \user()->wallets;
    }

    public function updatedInitiator()
    {
        $this->wallet = '';
        if (!empty($this->initiator)) {
            $this->wallets = User::find($this->initiator)->wallets;
        } else {
            $this->wallets = \user()->wallets;
        }

        Log::info($this->wallets);

        $this->dispatchBrowserEvent('set_wallets', $this->wallets);
    }

    public function updating()
    {
        $this->resetPage();
    }

    public function export()
    {
        $filename = (empty($this->date) ? Carbon::now()->toDateString() : $this->date) . "_transactions.xlsx";
        return (new TransactionExport(Resource::sortTransactionCollection($this->transactionsList()->get())))->download($filename);
    }

    public function render()
    {
        return view('livewire.app.report.transactions.all', ['transactions' => $this->transactionsList()->paginate(20)]);
    }

    public function transactionsList()
    {
        if (user()->isMerchant()) {
            return user()->merchant->transactions()->addSelect(['user' => User::select('first_name')
                ->whereColumn('id', 'merchant_payments_rev.initiator_id')
                ->limit(1)
            ])->filter(["date" => empty($this->date) ? Carbon::now() : $this->date, "status" => $this->status, "wallet" => $this->wallet, 'initiator' => $this->initiator, 'service' => $this->service])->latest();
        } else {
            return user()->transactions()->filter(["date" => empty($this->date) ? Carbon::now() : $this->date, "status" => $this->status, "wallet" => $this->wallet, 'service' => $this->service])->latest();
        }

    }
}
