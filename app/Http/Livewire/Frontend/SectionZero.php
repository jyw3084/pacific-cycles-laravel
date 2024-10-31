<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\About;
use App\Models\Banner;
use App\Models\Head;

class SectionZero extends Component
{
	public function mount(){

        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->content = About::where([['link', 'about/section-zero'], ['locale', $lang]])->first();
        $this->category = About::where('locale', $lang)->get();
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
    }

    public function render()
    {
        return view('livewire.frontend.section-zero', [
            'content' => $this->content,
            'category' => $this->category,
            'banner' => Banner::where('link', 'about')->first(),
        ])
        ->extends('layouts.about-layout', [
            'head' => $this->head,
        ])
        ->section('content');;
    }
}
