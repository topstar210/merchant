<?php

namespace App\Http\Livewire\App\Agents;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Utils\Rules;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Add extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $country;
    public $phone;
    public $phone_code;
    public $phone_country;
    public $address;
    public $city;
    public $state;
    public $gender;

    public function mount()
    {
        $this->country = strtoupper(user()->defaultCountry);
        $this->phone_code = (int)user()->carrierCode;
        $this->phone_country = $this->country;
    }

    public function getCountriesProperty()
    {
        return Country::query()->orderBy('name')->get();
    }

    public function getGendersProperty()
    {
        return config('constants.gender');
    }

    public function setCountry($country)
    {
        $this->country = $country[0];
        $this->phone_code = (int)$country[1];
        $this->phone_country = $country[0];
    }

    public function rules()
    {
        return Rules::addAgentRules();
    }

    public function addAgent()
    {
        $validated = $this->validate();

        $response = (new RegisterController())->createAgent($validated);

        request()->session()->flash(
            'error',
            $response['error']
        );
        request()->session()->flash(
            'error_message',
            $response['error_message']
        );

        return redirect('app/agents');
    }

    public function render()
    {
        return view('livewire.app.agents.add');
    }


}
