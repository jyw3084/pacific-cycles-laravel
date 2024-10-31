<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Banner;
use App\Models\Head;

class Faq extends Component
{
    public $birdy = [];
    public $carry = [];
    public $if = [];
    public $reach = [];
    public $supportive = [];
    public function mount()
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $faqs = \App\Models\Faq::where('locale', $lang)->get();
        foreach($faqs as $k => $v)
        {
            switch($v->type)
            {
                case 1:
                    $birdy[] = $v;
                    break;
                case 2:
                    $carry[] = $v;
                    break;
                case 3:
                    $if[] = $v;
                    break;
                case 4:
                    $reach[] = $v;
                    break;
                case 5:
                    $supportive[] = $v;
                    break;
            }
        }
        $this->birdy = $birdy;
        $this->carry = $carry;
        $this->if = $if;
        $this->reach = $reach;
        $this->supportive = $supportive;
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
    }

    public function render()
    {
        return view('livewire.frontend.faq', [
            'banner' => Banner::where('link', 'support')->first(),
        ])
        ->extends('layouts.support-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
