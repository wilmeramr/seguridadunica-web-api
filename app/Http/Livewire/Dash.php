<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Dash extends Component
{
    public $salesBuMonth_Data = [], $top5Data = [],$weekSales_Data= [],$year;

    public function mount()
    {
       $this->year = date('Y');
    }
    public function render()
    {
        return view('livewire.dashboard.component')
        ->extends('layouts.theme.app')
        ->section('content');
    }

}
