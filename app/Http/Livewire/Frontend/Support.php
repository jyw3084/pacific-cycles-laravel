<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\ManualFiles;
use App\Models\Banner;
use App\Models\Head;

class Support extends Component
{
	public function mount(){

        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $files = ManualFiles::where('locale', $lang)->get();
        $data = [];
        foreach($files as $k => $v)
        {
            switch($v->type)
            {
                case 1:
                    $type = 'manuals';
                    break;
                case 2:
                    $type = 'catalogs';
                    break;
                case 3:
                    $type = 'others';
                    break;
            }
            $data[$type][] = $v;
        }
        $this->data = $data;
        $this->data['banner'] = Banner::where('link', 'support')->first();
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
    }

    public function render()
    {
        return view('livewire.frontend.support', $this->data)
        ->extends('layouts.support-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
