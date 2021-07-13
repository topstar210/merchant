<?php

namespace App\Http\Livewire\App\Agents;

use App\Http\Utils\Resource;
use App\Models\MerchantPayment;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class View extends Component
{
    public User $agent;
    public  $agent_wallets;
    public $summary;

    public function mount(User $agent)
    {
        $this->agent = $agent;
        $this->agent_wallets = $agent->wallets;
    }


    public function deleteAgent()
    {
        Resource::logActivity('Deleted Agent : '.$this->agent->full_name);

        $this->agent->delete();

        request()->session()->flash(
            'error',
            false
        );
        request()->session()->flash(
            'error_message',
            'Agent deleted successfully'
        );

        return redirect('app/agents');
    }

    public function updateAgentStatus()
    {
        $this->agent->status = $this->agent->status =='Active' ? 'Inactive' : 'Active';
        $this->agent->save();

        request()->session()->flash(
            'error',
            false
        );
        request()->session()->flash(
            'error_message',
            'Agent updated successfully'
        );

        Resource::logActivity('Updated Agent status to '.$this->agent->status.' : '.$this->agent->full_name);

        $this->emit('showAlert');

    }

    public function updateWalletStatus(Wallet $wallet)
    {
        $wallet->lock = !$wallet->lock;
        $wallet->save();

        $this->agent->refresh();

        request()->session()->flash(
            'error',
            false
        );
        request()->session()->flash(
            'error_message',
            'Wallet updated successfully'
        );

        $this->emit('showAlert');

        Resource::logActivity(($wallet->lock ? 'Locked' : 'Unlocked'). ' agent wallet : '.$this->agent->full_name);
    }

    public function getSummary()
    {
        $today = Carbon::now();

        $_summary = [];
        foreach ($this->agent_wallets as $wallet) {
            $wallet->depositSum = MerchantPayment::summary(['type' => 1, 'date' => $today, 'wallet' => $wallet->id])->sum('amount');
            $wallet->withdrawalSum = MerchantPayment::summary(['type' => 2, 'date' => $today, 'wallet' => $wallet->id])->sum('amount');
            $wallet->commissionSum = MerchantPayment::summary(['date' => $today, 'wallet' => $wallet->id])->sum('commission');
            $_summary[] = $wallet;
        }
        $this->summary = $_summary;
    }

    public function render()
    {
        return view('livewire.app.agents.view');
    }
}
