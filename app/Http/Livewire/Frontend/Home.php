<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Index;
use App\Models\Packages;
use App\Models\Products;
use App\Models\BikeModel;
use App\Models\News;
use App\Models\Event;
use App\Models\Head;
use Illuminate\Support\Facades\App;


class Home extends Component
{

    public $featureBikes = [];
    public $news = [];
    public $events = [];


	public function mount(){

        app()->setLocale(session()->get('locale'));
        $this->lang = $lang = app()->getLocale();
        $contents = Index::where('locale', $this->lang)->get();
        foreach($contents as $k => $v)
        {
            switch($v->area)
            {
                case 'splash_area':
                    $this->splash_area = $v;
                    break;
                case 'register_area':
                    $this->register_area = $v;
                    break;
                case 'warranty_area':
                    $this->warranty_area = $v;
                    break;
                case 'dealer_area':
                    $this->dealer_area = $v;
                    break;
                case 'about_area':
                    $this->about_area = $v;
                    break;
                case 'head':
                    $this->head = $v;
                    break;
            }
        }

        $this->featureBikes = Products::where([['type', 1], ['is_featured', 1], ['locale', $this->lang]])->groupBy('product_name')->orderBy('id', 'desc')->take(4)->get();
        $this->news = News::where('locale', $lang)->orderBy('id','DESC')->take(3)->get();
        $this->events = Event::where('locale', $lang)->orWhere('locale', 'all')->orWhere('locale', '')->orderBy('id','DESC')->take(3)->get();

    }

    public function render()
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();

        return view('livewire.frontend.home', [
                'splash_area' => $this->splash_area ?? null,
                'register_area' => $this->register_area ?? null,
                'warranty_area' => $this->warranty_area ?? null,
                'dealer_area' => $this->dealer_area ?? null,
                'about_area' => $this->about_area ?? null,
                'featureBikes' => $this->featureBikes ?? null,
                'news' => $this->news ?? null,
                'events' => $this->events ?? null,
                'lang' => $this->lang ?? null,
            ])
        ->extends('layouts.home-layout', [
            'head' => Head::where([['link', ''], ['locale', $lang]])->first(),
        ])
        ->section('content');
    }
}
