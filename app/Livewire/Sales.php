<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\User;
use Livewire\Component;

class Sales extends Component
{
    public $search = '';
    public function render()
    {
        $results = [];
        if(strlen($this->search) >=1){
            $results = Company::where('company_name', 'like', '%' . $this->search . '%')->limit(9)->get();
        }

        return view('livewire.sales', [
            'users' => $results,
        ]);
    }
}
