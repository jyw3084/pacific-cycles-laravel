<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;
use App\Models\UserBike;
use App\Models\User;
use App\Http\Controllers\APIController;

class Transfer extends Component
{
    public $account, $name, $memo, $ProductNo;
    public $bike = [];

    public function mount($ProductNo){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
        $this->ProductNo = $ProductNo;
        $this->bike = UserBike::where([['user_id', $user->id], ['ProductNo', $ProductNo]])->first();
    }
    
    public function render()
    {
        return view('livewire.back.transfer')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }
    
    public function ProductTransfer()
    {
        $user = auth()->user();
        $this->rules = [
            'account' => 'required',
            'name' => 'required|min:2',
            'memo' => 'nullable',
        ];
        $data = $this->validate();
        $data = (object)$data;

        $target = User::where('email', $data->account)->orWhere('phone', $data->account)->first();
        if($target)
        {
            $target->memo = $data->memo;
            $response = APIController::ProductTransfer($target, $this->ProductNo);
            if($response)
            {
                $bike = UserBike::find($this->bike->id);
                $bike->IsTransfer = 1;
                $bike->TransferID = $target->id;
                $bike->save();
    
                return redirect('my-bike');
            }
        }

    }
    
    public function bring()
    {
        $user = User::where('email', $this->account)->orWhere('phone', $this->account)->first();

        if($user)
            $this->name = $user->name;
        else
            $this->name = trans('frontend.dashboard.no_user');

        
    }
}
