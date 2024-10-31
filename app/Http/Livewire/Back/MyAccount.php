<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;

class MyAccount extends Component
{
    public $user = [];

    public function mount(){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.back.my-account')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }
}
