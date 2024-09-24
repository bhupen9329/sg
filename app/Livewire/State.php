<?php

namespace App\Livewire;

use App\Models\CityState;
use Livewire\Component;

class State extends Component
{
    public $states;
    public $cities;
    public $selectedState = null;
    public $selectedCity = null;

    public function mount($selectedState = null)
    {
        $this->states = CityState::select('state')->distinct()->get();
        $this->cities = collect();

        if (!is_null($selectedState)) {
            $this->selectedState = $selectedState;
            $this->cities = CityState::where('state', $selectedState)->select('city')->distinct()->get();
        }
    }

    public function updatedSelectedState($state)
    {
        $this->selectedCity = null; // Reset the selected city when state changes

        if (!is_null($state)) {
            $this->cities = CityState::where('state', $state)->select('city')->distinct()->get();
        } else {
            $this->cities = collect();
        }
    }

    public function render()
    {
        return view('livewire.state');
    }
}
