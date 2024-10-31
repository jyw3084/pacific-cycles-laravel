<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;

class MyAccountEdit extends Component
{
    public $name, $email, $phone, $address, $password, $password_confirm;

    public function mount(){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone_number;
        $this->address = $user->Address;
    }
    
    public function render()
    {
        return view('livewire.back.my-account-edit')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }

    public function saveAccount()
    {
        $this->rules = [
            'name' => 'required|min:6',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ];
        $this->validate();

        $user = auth()->user();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone_number = $this->phone;
        $user->Address = $this->address;
        $user->save();
        return redirect('my-account');

    }
}
