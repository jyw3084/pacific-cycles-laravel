<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Banner;
use App\Models\Header;

class BikesHeader extends Component
{
	public function mount(){

        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->headers = Header::where([['parent_id', 0], ['locale', $lang]])->get();
        $this->banner = Banner::where('link', 'bikes')->first();

    }

    public function render()
    {
        return view('livewire.frontend.bikes-header', [
            'headers' => $this->headers,
            'banner' => $this->banner,
        ]);
    }
}
