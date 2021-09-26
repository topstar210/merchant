<?php

namespace App\Http\Livewire;

use App\Services\AuthorizationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class TokenResendButton extends Component
{

    public string $message;

    public function resendToken()
    {
        $token = AuthorizationService::handle();
        session(['twoFA.token' => $token]);

//        Log::info($token);
        $this->message = 'Authorization token resent successfully';
    }

    public function render()
    {
        return <<<'blade'
            <div>
                @if(!empty($message))

                                    <div class="alert icon-custom-alert alert-success b-round-sm fade show"
                                         role="alert">

                                        <div class="alert-text font-12">
                                            {{$message }}
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                    </div>

                @endif
                <div><small class="me-2">Haven't received the Authorization Token?</small> <button class=" btn btn-sm btn-primary" wire:click="resendToken"><span wire:loading class="btn-spinner"></span> Resend Token</button></div>
            </div>
        blade;
    }
}
