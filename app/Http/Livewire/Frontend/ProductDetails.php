<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Products;
use App\Models\Store;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Storage;

class ProductDetails extends Component
{
    public $product = [];
    public $product_id;
    public $quantity = 1;
    public $show_add2cart = 0;
    public $show_vendor = 0;
    public $area;
    public $country;
    public $main_img;
    public $vendor = [];
    public $in_my_wishlist = 0;
    public $overlay = 0;

    public function mount($product_code){
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->product = Products::where([['product_code', $product_code], ['locale', $lang]])->first();
        if(!$this->product)
            return redirect('store');
        $this->head = $this->product;
        $this->color = $this->product->color;
        $this->area = Store::groupBy('country')->get();
        $this->product_id = $this->product->id;
        $user = auth()->user();
        if($user)
        {
            switch($this->product->type)
            {
                case 1:
                    $this->in_my_wishlist = in_array($this->product_id.'-product', explode(';',$user->favourites));
                    break;
                case 2:
                    $this->in_my_wishlist = in_array($this->product_id.'-accessory', explode(';',$user->favourites));
                    break;
            }
        }
    }

    public function render(){
        return view('livewire.frontend.product-details', ['product' => $this->product, 'area' => $this->area])
        ->extends('layouts.frontend-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }

    public function addToCart(Request $request){

        $qty = $this->quantity;
        $product = $this->product;
        $price = $product->price;
        $total = $price;
        Cart::add($this->product_id, $product->product_name, $qty, $total, $product->shipping_size, ['area' => $this->country, 'type' => 2]);

        $this->emit('cart_count');
    }
    
    public function add2wishlist()
    {
        if($user = auth()->user())
        {
            $favourites = explode(';',$user->favourites);
            if(!in_array($this->product_id, $favourites))
            {
                switch($this->product->type)
                {
                    case 1:
                        $user->favourites = $user->favourites.$this->product_id.'-product;';
                        break;
                    case 2:
                        $user->favourites = $user->favourites.$this->product_id.'-accessory;';
                        break;
                }
                $user->save();
            }
        }
        else
        {
            session()->put('back', 'store/products/'.$this->product_code);
            return redirect('login');
        }

        switch($this->product->type)
        {
            case 1:
                $this->in_my_wishlist = in_array($this->product_id.'-product', explode(';',$user->favourites));
                break;
            case 2:
                $this->in_my_wishlist = in_array($this->product_id.'-accessory', explode(';',$user->favourites));
                break;
        }
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
    
    public function select_product($id)
    {
        $this->product = Products::find($id);
        $this->product_id = $id;
        $this->main_img = Storage::url($this->product->images[0]);
        $this->overlay = 0;
    }
    
    public function change_main_img($image, $overlay)
    {
        $this->main_img = Storage::url($image);
        $this->overlay = $overlay;
    }
    
}
