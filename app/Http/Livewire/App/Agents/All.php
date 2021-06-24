<?php

namespace App\Http\Livewire\App\Agents;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class All extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $query = '';

    public function updatingQuery()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.app.agents.all', ['agents' => $this->agentList()]);
    }

    public function agentList()
    {
        return User::query()->agents()->filter(["search" => $this->query])->latest()->paginate(11);
    }
}
