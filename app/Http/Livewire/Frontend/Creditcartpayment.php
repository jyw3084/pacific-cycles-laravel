<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;

class Creditcartpayment extends Component
{
    public function render()
    {
        return view('livewire.frontend.creditcartpayment')
        ->extends('layouts.frontend-layout')
        ->section('content');
    }
}
