<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;

class MyAccountWithoutPhone extends Component
{
    public function mount(){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
    }
    
    public function render()
    {
        return view('livewire.back.myaccount-without-phone')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }
}
