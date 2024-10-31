<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;

class Footer extends Component
{
	public function mount(){

        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->footer = \App\Models\Footer::where('locale', $lang)->first();

    }

    public function render()
    {
        return view('livewire.frontend.footer', [
            'footer' => $this->footer
        ]);
    }
}
