<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class Header extends Component
{
    public $cart_count = 0;
    public $headers;

    protected $listeners = ['cart_count', 'cart_count'];

    public function mount()
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->headers = \App\Models\Header::where([['parent_id', 0], ['locale', $lang]])->orderBy('order', 'asc')->get();
    }

    public function cart_count()
    {
        $this->cart_count = Cart::count();
    }

    public function render()
    {
        return view('livewire.frontend.header', [
            'headers' => $this->headers,
            'cart' => Cart::count(),
        ])
        ->extends('layouts.frontend-layout')
        ->section('header');
    }
}
