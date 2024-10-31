<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Bike;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Head;

class BikesFolding extends Component
{
	public function mount(){

        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->content = Bike::where([['category_id', 1], ['locale', $lang]])->first();
        $this->category = Category::where('locale', $lang)->get();
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();

    }

    public function render()
    {
        return view('livewire.frontend.bikes-folding', [
            'content' => $this->content,
            'category' => $this->category,
            'banner' => Banner::where('link', 'bikes')->first(),
        ])
        ->extends('layouts.bikes-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
