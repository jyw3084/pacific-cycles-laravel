<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;
use App\Models\Products;
use App\Models\UserBike;
use App\Http\Controllers\APIController;

class Mybikes extends Component
{
    public $my_wish_products = [];
    public $my_wish_accessories = [];
    public $productPage = 1;
    public $productPerPage = 6;
    public $accessoryPage = 1;
    public $accessoryPerPage = 6;
    public $products = [];
    public $accessories = [];
    public $bikes = [];
    public $transfer = [];
    public $unckeck = [];
    

    public function mount(){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
        $favourites = explode(';',$user->favourites);
        $products = [];
        $accessories = [];
        foreach($favourites as $k => $v)
        {
            if($v)
            {
                if(strpos($v, 'product') == true)
                {
                    $products[] = str_replace('-product', '', $v);
                }
                if(strpos($v, 'accessory') == true)
                {
                    $accessories[] = str_replace('-accessory', '', $v);
                }
            }
        }

        $this->products = $products;
        $this->accessories = $accessories;
        $this->my_wish_products = Products::whereIn('id', $products)->skip(0)->take($this->productPerPage)->get();
        $this->my_wish_accessories = Products::whereIn('id', $accessories)->skip(0)->take($this->accessoryPerPage)->get();
        $this->bikes = UserBike::where([['user_id', $user->id], ['status', 2]])->get();
        $this->unckeck = UserBike::where([['user_id', $user->id], ['status', '<', 2]])->get();
        //$response = APIController::GetTransferList();
        //dd($response);
        $this->transfer = json_decode(APIController::GetTransferList())->Entries;

    }

    public function render()
    {
        return view('livewire.back.mybikes')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }

    public function viewMoreBike()
    {
        $this->productPage += 1;
        $this->productPerPage = $this->productPerPage * $this->productPage;

        $this->mount();
    }

    public function deleteProduct($id)
    {
        $del_str = $id.'-product;';
        $user = auth()->user();
        $user->favourites = str_replace($del_str, '', $user->favourites);
        $user->save();

        $this->mount();
    }

    public function deleteAccessory($id)
    {
        $del_str = $id.'-accessory;';
        $user = auth()->user();
        $user->favourites = str_replace($del_str, '', $user->favourites);
        $user->save();

        $this->mount();
    }

    public function agree($ProductNo, $TransferID, $ToMemberID)
    {
        $user = auth()->user();
        if($user)
        {
            $response = APIController::AgreeTransfer($ProductNo, $TransferID);
    
            if($response)
            {
                $bike = UserBike::where([['ProductNo', $ProductNo], ['IsTransfer', 1], ['MemberId', $ToMemberID]])->first();
                $bike->user_id = $user->id;
                $bike->IsTransfer = 0;
                $bike->TransferID = null;
                $bike->MemberId = $user->MemberID;
                $bike->save();
    
                $this->mount();
            }
        }
        else
        {
            return redirect('login');
        }
    }

    public function cancel_transfer($ProductNo, $ToMemberID)
    {
        $response = APIController::CancelTransfer($ProductNo);

        if($response)
        {
            $bike = UserBike::where([['ProductNo', $ProductNo], ['IsTransfer', 1], ['TransferID', $ToMemberID]])->first();
            $bike->IsTransfer = 0;
            $bike->TransferID = null;
            $bike->save();

            return redirect('my-bike');
        }
    }

    public function cancel($id, $No)
    {
        $response = APIController::CancelQrcode($No);

        if(json_decode($response)->StatusCode == 0)
        {
            UserBike::find($id)->delete();

            return redirect('my-bike');
        }
    }
    
}
