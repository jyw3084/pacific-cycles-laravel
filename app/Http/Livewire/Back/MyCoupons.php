<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;

class MyCoupons extends Component
{
    public $coupons = [];
    public $coupon;

    public function mount(){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
        $this->coupons = $user->coupons;
        $this->coupon = $user->coupon;
    }
    
    public function render()
    {
        return view('livewire.back.my-coupons')->extends('layouts.back.backend-layout')
        ->section('content');
    }
}
