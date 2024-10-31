<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;

class AsideNav extends Component
{
    public function mount(){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
    }
    
    public function render()
    {
        return view('livewire.back.aside-nav')
        ->extends('layouts.back.backend-layout')
        ->section('aside-nav');
    }
}
