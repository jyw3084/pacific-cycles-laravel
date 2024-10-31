<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Packages;
use App\Models\Store;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class PackageDetails extends Component
{
    public $product = [];
    public $product_id;
    public $quantity = 1;
    public $show_add2cart = 0;
    public $show_vendor = 0;
    public $area;
    public $country;
    public $packages;
    public $head;
    public $vendor = [];
    public $in_my_wishlist = 0;
    public $overlay = 0;

    public function mount($id){
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $product = Packages::where([['id', $id], ['locale', $lang]])->first();
        if(!$product)
        {
            $product = Packages::find($id);
            $product = Packages::select('*, head as content')->where([['name', $product->name], ['locale', $lang]])->first();
            if($product)
                return redirect('store/package/'.$product->id);
            
            return redirect('store');
        }
        $this->product = $product;
        $this->packages = $product->products;
        $this->head = $this->product;
        $this->area = Store::groupBy('country')->get();
        $this->product_id = $id;
        $user = auth()->user();
        if($user)
            $this->in_my_wishlist = in_array($this->product_id.'-package', explode(';',$user->favourites));
    }

    public function render(){
        return view('livewire.frontend.product-details', ['product' => $this->product, 'packages' => $this->packages, 'area' => $this->area])
        ->extends('layouts.frontend-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }

    public function addToCart(){

        $qty = $this->quantity;
        $product = $this->product;
        $price = $product->price;
        $total = $price;
        Cart::add($this->product_id, $product->name, $qty, $total, $product->shipping_size, ['area' => $this->country, 'type' => 1]);
        
        $this->emit('cart_count');
    }

    public function add2wishlist()
    {
        if($user = auth()->user())
        {
            $favourites = explode(';',$user->favourites);
            if(!in_array($this->product_id, $favourites))
            {
                $user->favourites = $user->favourites.$this->product_id.'-package;';
                $user->save();
            }
        }
        else
        {
            session()->put('back', 'store/package/'.$this->product_id);
            return redirect('login');
        }

        $this->in_my_wishlist = in_array($this->product_id.'-package', explode(';',$user->favourites));
    }
    
    public function changeEvent($value)
    {
        $vendors = $this->product->vendors;
        $this->show_vendor = 0;
        foreach ($vendors as $k => $v) {
            if(in_array($value, $v))
            {
                $this->show_vendor = 1;
                $this->vendor = [
                    'name' => $v['store'],
                    'email' => $v['email'],
                    'phone' => $v['phone'],
                    'address' => $v['address'],
                ];
            }
        }
        
        if($this->show_vendor == 0)
            $this->show_add2cart = 1;
        else
            $this->show_add2cart = 0;

        $this->country = $value;
    }
}
