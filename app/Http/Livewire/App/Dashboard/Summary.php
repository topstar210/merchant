<?php

namespace App\Http\Livewire\App\Dashboard;

use App\Models\MerchantPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Summary extends Component
{
    public $summary;
    public $wallets;

    public function mount()
    {
        $this->wallets = user()->wallets;
    }

    public function getSummary()
    {
        $today = Carbon::now();

        $_summary = [];
        foreach ($this->wallets as $wallet) {
            $wallet->depositSum = MerchantPayment::summary(['type' => 1, 'date' => $today, 'wallet' => $wallet->id])->sum('amount');
            $wallet->withdrawalSum = MerchantPayment::summary(['type' => 2, 'date' => $today, 'wallet' => $wallet->id])->sum('amount');
            $wallet->commissionSum = MerchantPayment::summary(['date' => $today, 'wallet' => $wallet->id])->sum('commission');
            $_summary[] = $wallet;
        }
        $this->summary = $_summary;
    }

    public function render()
    {
        return view('livewire.app.dashboard.summary');
    }
}
