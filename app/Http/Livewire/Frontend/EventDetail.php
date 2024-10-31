<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Event;
use App\Models\Banner;

class EventDetail extends Component
{
    public $event ='';
    public function mount($id)
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();

        $this->event = $event = Event::find($id);
        $this->head = $event->head[$lang] ?? '';

        if(!auth()->user())
            session()->put('back', 'news-events/event/'.$id.'/register');
    }
    public function render()
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        return view('livewire.frontend.event-detail', [
            'banner' => Banner::where('link', 'news-events')->first(),
            'lang' => $lang,
        ])
        ->extends('layouts.news-events-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
