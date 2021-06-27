<?php

namespace App\Http\Livewire\App\Agents;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class View extends Component
{
    public User $agent;

    public function mount(User $user)
    {
        $this->agent = $user;
    }


    public function deleteAgent()
    {
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

    }

    public function render()
    {
        return view('livewire.app.agents.view');
    }
}
