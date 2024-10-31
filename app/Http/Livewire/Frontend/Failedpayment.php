<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Head;

class Failedpayment extends Component
{
    public function mount()
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
    }

    public function render()
    {
        return view('livewire.frontend.failedpayment')
        ->extends('layouts.frontend-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
