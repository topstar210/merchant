<?php

namespace App\Http\Livewire;

use App\Http\Utils\Rules;
use Livewire\Component;

class CompleteSetupForm extends Component
{
    public $password;
    public $password_confirmation;
    public $pin;

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, Rules::completeSetupRules());
    }

}
