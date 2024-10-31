<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;

class Login extends Component
{
    public $lang;

    public function render()
    {
        if(session()->get('back') && auth()->user())
        {
            $back = session()->get('back');
            session()->forget('back');
            redirect($back);
        }
        app()->setLocale(session()->get('locale'));
        $this->lang = app()->getLocale();

        return view('livewire.back.login')
        ->extends('layouts.back.login-layout', [
            'lang' => $this->lang
        ])
        ->section('content');
    }
}
