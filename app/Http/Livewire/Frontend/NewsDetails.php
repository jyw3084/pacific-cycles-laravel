<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\News;
use App\Models\Banner;

class NewsDetails extends Component
{
    public $news ='';
    public function mount($id)
    {
        app()->setLocale(session()->get('locale'));
        $this->news = $news = News::find($id);
        $this->head = $news->head;
    }
    public function render()
    {
        return view('livewire.frontend.news-details', [
            'banner' => Banner::where('link', 'news-events')->first(),
        ])
        ->extends('layouts.news-events-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
