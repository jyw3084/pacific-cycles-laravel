<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;

class SignUpWithEmail extends Component
{
    public function mount(){
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
    }
    
    public function render()
    {
    	$captcha = $this->randomPrefix(4);
        return view('livewire.back.sign-up-with-email',['captcha' => $captcha])
        ->extends('layouts.back.login-layout')
        ->section('content');
    }

    function randomPrefix($length){
        $random= "";
        srand((double)microtime()*1000000);
    
        $data = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    
        for($i = 0; $i < $length; $i++){
            $random .= substr($data, (rand()%(strlen($data))), 1);
        }
    
        return $random;
    }
}
