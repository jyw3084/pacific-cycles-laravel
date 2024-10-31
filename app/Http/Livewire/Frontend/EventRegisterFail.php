<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Event;
use App\Models\Banner;
use App\Models\Head;

class EventRegisterFail extends Component
{
    public $event ='';
    public $method = 1;
    public function mount($id)
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->event = Event::find($id);
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
        $this->lang = $lang;
    }
    public function render()
    {
        return view('livewire.frontend.event-register-fail', [
            'banner' => Banner::where('link', 'news-events')->first(),
        ])
        ->extends('layouts.news-events-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
