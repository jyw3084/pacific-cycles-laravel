<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;
use App\Models\Bonus;
use App\Models\BonusLog;

class MyCredits extends Component
{
    public $points = [];
    public $point;

    public function mount(){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
        $all_point = Bonus::where([['user_id', 'like', '%"'.$user->id.'"%'], ['expiration_date', '>=', date('Y-m-d')]])->get();
        $use_point = BonusLog::select('use_date as created_at', 'point as amount', 'order_id')->where('user_id', $user->id)->get();
        $collection = collect();
        if($all_point)
        {
            foreach ($all_point as $point)
                $collection->push($point);
        }
        
        if($use_point)
        {
            foreach ($use_point as $point)
                $collection->push($point);
        }

        $this->points = $collection;
        $this->point = $all_point->sum('amount') - $use_point->sum('amount');
    }
    
    public function render()
    {
        return view('livewire.back.my-credits')->extends('layouts.back.backend-layout')
        ->section('content');
    }
}
