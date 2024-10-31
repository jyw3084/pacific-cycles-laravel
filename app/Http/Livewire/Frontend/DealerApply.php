<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Banner;
use App\Models\DealerForm;
use App\Models\Head;

class DealerApply extends Component
{
    public $banner, $name, $email, $subject, $message;
    public $form = [];

    public function mount(){
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->banner = Banner::where('link', 'dealer')->first();
        $this->form = DealerForm::where('locale', $lang)->get();
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
    }

    public function render()
    {
        return view('livewire.frontend.dealer-apply')
        ->extends('layouts.dealer-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }

    public function applyDealerShip()
    {
        dd('test');
    }
}
