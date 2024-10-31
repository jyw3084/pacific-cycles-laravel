<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Banner;
use App\Models\ContactForm;
use App\Models\Head;

class Contact extends Component
{
    public $name, $email, $phone, $subject, $message;
    public $form = [];

	public function mount(){

        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->form = ContactForm::where('locale', $lang)->get();
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();

    }
    public function render()
    {
        return view('livewire.frontend.contact', [
            'banner' => Banner::where('link', 'contact')->first(),
        ])
        ->extends('layouts.contact-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
