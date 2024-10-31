<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;
use App\Models\UserBike;
use App\Models\User;
use App\Http\Controllers\APIController;

class BikeDetails extends Component
{
    public $bike = [];

    public function mount($ProductNo){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
        $this->ProductNo = $ProductNo;
        $this->bike = UserBike::where([['user_id', $user->id], ['ProductNo', $ProductNo]])->first();
    }
    
    public function render()
    {
        return view('livewire.back.bike-details')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }
}
