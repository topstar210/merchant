<?php

namespace App\Http\Livewire;

use App\Services\AuthorizationService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class TokenResendButton extends Component
{

    public function resendToken()
    {
        $token = AuthorizationService::handle();
        session(['twoFA.token'=> $token]);

        session()->flash('message', 'Authorization token resent successfully');
    }

    public function render()
    {
        return <<<'blade'
            <button class=" btn btn-sm btn-primary" wire:click="resendToken" wire:loading.attr="disabled"><span wire:loading class="btn-spinner"></span> Resend Token</button>
        blade;
    }
}
