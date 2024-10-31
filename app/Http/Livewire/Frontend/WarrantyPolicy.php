<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Banner;
use App\Models\Head;

class WarrantyPolicy extends Component
{
    public $content;
    public function mount()
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->content = \App\Models\WarrantyPolicy::where('locale', $lang)->first()->content;
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
    }

    public function render()
    {
        return view('livewire.frontend.warranty-policy', [
            'banner' => Banner::where('link', 'support')->first(),
        ])
        ->extends('layouts.support-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
