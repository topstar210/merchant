<?php

namespace App\Http\Livewire\App\Agents;

use App\Models\Country;
use App\Models\Currency;
use Livewire\Component;

class Add extends Component
{
    public $country;
    public $currency;
    public $state;
    public $first_name;
    public $note;
    public $phone;
    public $phone_code;

    public function getCountriesProperty()
    {
        return Country::query()->orderByDesc('name')->get();
    }

    public function getCurrenciesProperty()
    {
        return Currency::query()->orderByDesc('name')->get();
    }

    public function render()
    {
        return view('livewire.app.agents.add');
    }
}
