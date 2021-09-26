<?php

namespace App\Http\Livewire\App\Account;

use App\Http\Utils\Resource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Security extends Component
{
    public $action;
    public $current_password;
    public $password;
    public $password_confirmation;
    public $pin;
    public $pin_confirmation;

    public function mount($action)
    {
        $this->action = $action;
    }

    protected $messages = [
        'pin.not_in' => 'Choose a different Pin',
        'current_password.min' => 'Invalid current password',
        'current_password.current_password' => 'Incorrect current password',
    ];

    protected function rules()
    {
        return [
            'current_password' => 'required|current_password|min:8',
            'password' => [Rule::requiredIf($this->action == 'password'), 'nullable', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(3)],
            'password_confirmation' => [Rule::requiredIf($this->action == 'password'), 'same:password'],
            'pin' => [Rule::requiredIf($this->action == 'pin'), 'nullable', 'size:4', 'not_in:0000,1234,' . user()->pin],
            'pin_confirmation' => [Rule::requiredIf($this->action == 'pin'), 'same:pin'],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function changeAction()
    {
        $this->validate();

        user()->update($this->action == 'password' ? ['password' => Hash::make($this->password)] : ['pin' => $this->pin]);

        Resource::logActivity("Changed $this->action");

        return redirect('logout');
    }


    public function render()
    {
        return view('livewire.app.account.security');
    }
}
