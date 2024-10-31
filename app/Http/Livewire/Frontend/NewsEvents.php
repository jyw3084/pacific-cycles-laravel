<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\News;
use App\Models\Event;
use App\Models\Banner;
use App\Models\Head;
use Livewire\WithPagination;

class NewsEvents extends Component
{
	use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
    }

    public function render()
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        return view('livewire.frontend.news-events', [
            'news' => News::where('locale', $lang)->orderBy('id', 'desc')->paginate(6),
            'events' => Event::where('locale', $lang)->orWhere('locale', 'all')->orWhere('locale', '')->orderBy('id', 'desc')->paginate(6),
            'banner' => Banner::where('link', 'news-events')->first(),
            'lang' => $lang,
        ])
        ->extends('layouts.news-events-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
