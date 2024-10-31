<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;

class PasswordChange extends Component
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
        return view('livewire.back.change_pwd')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }

    public function changePwd()
    {
        $this->rules = [
            'password' => 'required|min:6',
            'password_confirm' => 'min:6|same:password',
        ];
        $data = $this->validate();

        $data = (object)$data;
        $user = auth()->user();
        $user->password = bcrypt($data->password);
        
        if($user->save())
            return redirect('my-account');

    }
}
