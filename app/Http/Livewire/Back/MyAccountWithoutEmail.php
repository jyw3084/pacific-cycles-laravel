<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;

class MyAccountWithoutEmail extends Component
{
    public function mount(){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
    }
    
    public function render()
    {
        return view('livewire.back.my-account-without-email')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }
}
