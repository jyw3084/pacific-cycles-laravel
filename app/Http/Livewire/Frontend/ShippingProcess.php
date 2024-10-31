<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Currency;
use App\Models\Products;
use App\Models\Packages;
use App\Models\ShippingFee;
use App\Models\FreeShipping;
use App\Models\Promos;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Vouchers;
use App\Models\Bonus;
use App\Models\BonusLog;
use App\Models\Setting;
use App\Models\Head;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ShippingProcess extends Component
{
    public $currentStep = 1;
    public $dataCart = [];
    public $qty = [];
    public $rows = [];
    public $deleteId = '';
    public $name, $email, $phone, $address, $note, $card_number, $cvc, $holder_name, $month, $year, $total, $shipping_fee, $country, $currency, $head;
    public $validated_fields;
	public $selectPaymentBTN = true;
    public $rules = [];
    public $checked_coupon = 0;
    public $has_coupon = 0;
    public $coupon = [];
    public $discount = 0;
    public $promo = 0;
    public $point = 0;
    public $use_point = 0;
    public $rate = 0;
    public $use_coupon = 0;
    public $coupon_id = null;
    public $order_id = null;
    public $order_number = null;
    public $promo_id = null;
    public $credit = null;
    public $paypal = null;
    public $promo_contect = null;
    public $promo_code = null;
    

    public function mount() {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();

        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
        $bikes_total = 0;
        $fittings_total = 0;
        $packages_total = 0;
        foreach($products as $k => $v)
        {
            if(!empty($v->products))
                $packages_total += $v->price;
            if($v->type == 1)
                $bikes_total += $v->price;
            if($v->type == 2)
                $fittings_total += $v->price;
            $this->currency = $v->currencies->code;
        }
        $shipping_size = $products->sum('shipping_size');
        $ships = ShippingFee::where('area', 'like', '%"country":"'.$this->country.'"%')->first();
        $shipping_fee = 0;
        if($ships)
        {
            foreach($ships->range as $k => $v)
            {
                $v = (object)$v;
                if($v->size_range <= $shipping_size && $v->currency == $this->currency)
                    $shipping_fee = $v->shipping_fee;
            }
        }
        $this->shipping_fee = $shipping_fee;
        $free = FreeShipping::where('status', 1)->get();
        foreach($free as $k => $v)
        {
            switch($v->type)
            {
                case 1:
                    $this->shipping_fee = $packages_total >= $v->amount ? 0 : $this->shipping_fee;
                    break;
                case 2:
                    $this->shipping_fee = $bikes_total >= $v->amount ? 0 : $this->shipping_fee;
                    break;
                case 3:
                    $this->shipping_fee = $fittings_total >= $v->amount ? 0 : $this->shipping_fee;
                    break;
                case 4:
                    $this->shipping_fee = $this->total >= $v->amount ? 0 : $this->shipping_fee;
                    break;
            }
        }
        $discount = Discount::where([['status', 1], ['currency', $this->currency]])->first();
        $rate = $discount->rate ?? 0;
        $this->discount = round($rate * $this->total / 100);
        $this->validated_fields = [];
    }

    public function render()
    {
        $this->selectPaymentBTN = count($this->validated_fields) <> 4;
        return view('livewire.frontend.shipping-process', ['dataCart' => $this->dataCart])
        ->extends('layouts.frontend-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
    
    public function changeQty($value, $rowId)
    {
        Cart::update($rowId, $value);
        
        $this->mount();
        $this->emit('cart_count');
    }

    public function select_promos(){
        if(!auth()->user())
        {
            session()->put('back', '/shopping/cart');
            return redirect('login');
        }
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone_number;
        $this->address = $user->Address;
        $all_point = Bonus::where([['user_id', 'like', '%"'.$user->id.'"%'], ['expiration_date', '>=', date('Y-m-d')]])->sum('amount');
        $use_point = BonusLog::where('user_id', $user->id)->sum('point');

        $this->point = $all_point > 0 ? $all_point - $use_point : 0;
        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
        $ids = [];
        $packs = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                    $packs[] = $v->id;
                if($v->options['type'] == 2)
                    $ids[] = $v->id;
            }
            if(count($ids) > 0)
                $products = Products::whereIn('id', $ids)->get();
            if(count($packs) > 0)
            {
                $package = Packages::whereIn('id', $packs)->get();
                foreach($package as $k => $v)
                {
                    $ids[] = $v->product->id;
                }
                $products = Products::whereIn('id', $ids)->get();
            }
            $cates = [];
            $models = [];
            foreach($products as $k => $v)
            {
                $cates[] = $v->category_id;
                $models[] = $v->model;
            }
        }
        $coupons = $user->coupons;
        $i = 0;
        foreach($coupons as $k => $v)
        {
            if(empty($v->model_id) && empty($v->product_id) && in_array($v->category_id, $cates))
            {
                $i += 1;
            }
            elseif(empty($v->product_id) && in_array($v->model_id, $models))
            {
                $i += 1;
            }
            elseif($v->product_id)
            {
                $product = Products::find($v->product_id);
                if($product)
                {
                    $i += 1;
                }
            }
        }
        $this->has_coupon = $i;

        $this->currentStep = 2;
        $this->rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'note' => 'nullable',
        ];
    }

    public function apply_coupon(){
        $user = auth()->user();
        if($this->checked_coupon == 0)
        {
            $this->checked_coupon = 1;

            $carts = Cart::content();
            $ids = [];
            $packs = [];
            if(Cart::count() > 0)
            {
                foreach($carts as $k => $v)
                {
                    if($v->options['type'] == 1)
                        $packs[] = $v->id;
                    if($v->options['type'] == 2)
                        $ids[] = $v->id;
                }
                if(count($ids) > 0)
                    $products = Products::whereIn('id', $ids)->get();
                if(count($packs) > 0)
                {
                    $packages = Packages::whereIn('id', $packs)->get();
                    foreach($packages as $k => $v)
                    {
                        $ids[] = $v->product->id;
                    }
                }
                $cates = [];
                $models = [];
            }
            $collection = collect();
            if(!empty($products))
            {
                foreach ($products as $bike)
                    $collection->push($bike);
            }
            if(!empty($packages))
            {
                foreach ($packages as $package)
                    $collection->push($package);
            }
    
            $this->dataCart = $products = $collection;
            foreach($products as $k => $v)
            {
                $cates[] = $v->category_id;
                $models[] = $v->model;
            }
            $coupons = $user->coupons;
            $coupon = [];
            foreach($coupons as $k => $v)
            {
                if(empty($v->model_id) && empty($v->product_id) && in_array($v->category_id, $cates))
                {
                    $coupon[] = $v;
                }
                elseif(empty($v->product_id) && in_array($v->model_id, $models))
                {
                    $coupon[] = $v;
                }
                elseif($v->product_id)
                {
                    $product = Products::find($v->product_id);
                    if($product)
                    {
                        $coupon[] = $v;
                    }
                }
            }
            $this->coupon = $coupon;
        }
        else
        {
            $this->checked_coupon = 0;
            $this->use_coupon = 0;
            $this->coupon_id = null;
        }
        
        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
    }

    public function select_coupon($value){
        $voucher = Vouchers::find($value);
        $this->rate = $voucher->rate;
        $this->use_coupon = round($voucher->rate * $this->total/100);
        $this->coupon_id = $value;
    
        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
    }

    public function use_point(){
        $percent = Setting::where('key', 'conversion_rate')->first()->value;
        if($this->use_point == 0)
        {
            $point = $this->point;
            if($this->currency != 'TWD')
                $point = $this->point * 30;
            $this->use_point = $point < ($this->total * $percent) ? $this->point: round($this->total * $percent);
        }
        else
            $this->use_point = 0;
    
        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
    }

    public function goToPayment(){

        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
        
        $validatedData = $this->validate();
        
        $this->select_payment();
    }

    public function updating($field) 
	{
		Arr::pull($this->validated_fields, $field);

        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
	}


    public function updated($field)
    {
        $v = $this->validateOnly($field);
		$this->validated_fields[$field] = Arr::get($v, $field, false);	
        session()->put('order', $this->validated_fields);
    }



    public function select_payment(){
        $user = auth()->user();

        $date = date('Y-m-d H:i:s');
        $data = (object)session('order');
        $order = new Order;
        $order->user_id = $user->id;
        $order->number = date('YmdHis').str_pad(random_int(1, 9999),5,"0",STR_PAD_LEFT);
        $order->shipping = $this->shipping_fee;
        $order->discount = $this->discount;
        $order->coupon = $this->use_coupon;
        $order->voucher_id = $this->coupon_id;
        $order->promo = $this->promo;
        $order->promo_id = $this->promo_id;
        $order->point = $this->use_point;
        $order->total = $this->total + $this->shipping_fee - $this->discount - $this->promo - $this->use_coupon - $this->use_point;
        $order->currency = $this->currency;
        $order->name = $this->name;
        $order->email = $this->email;
        $order->phone = $this->phone;
        $order->note = $data->note ?? null;
        $order->country = $this->country;
        $order->address = $this->address;
        $order->created_at = $date;
        $order->save();
        $this->order_id = $order->id;
        $this->order_number = $order->number;

        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        foreach($carts as $k => $v)
        {
            $orderDetail = new OrderDetail;
            $orderDetail->order_id = $order->id;
            $orderDetail->quantity = $v->qty;
            $orderDetail->price = $v->price;
            $orderDetail->created_at = $date;
            $orderDetail->product_type = $v->options['type'];
            switch($v->options['type'])
            {
                case 1:
                    $orderDetail->package_id = $v->id;
                    $packages[] = $v->id;
                    break;
                case 2:
                    $orderDetail->product_id = $v->id;
                    $bikes[] = $v->id;
                    break;
                case 3:
                    $orderDetail->warranty_extens_id = $v->id;
                    break;
            }
            $orderDetail->save();
        }

        $this->currentStep = 3;
    }

    public function proceed_to_payment(){

        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;

        $this->rules = [
            'card_number' => 'required|min:16|max:16',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'note' => 'nullable',
        ];

        $this->currentStep = 4;

        //redirect('shopping/pay/'.$this->order_id);
    }

    public function back(){
        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;

        $this->currentStep = $this->currentStep - 1;
    }


    public function deleteId($id)
    {
        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
        $this->deleteId = $id;
    }

    public function delete()
    {
        $rowId = $this->rows[$this->deleteId];
        Cart::remove($rowId);
        $this->mount();
        $this->emit('cart_count');

        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
    }

    public function add2favorites($id, $type)
    {
        if($user = auth()->user())
        {
            $favourites = explode(';',$user->favourites);
            if(!in_array($id, $favourites))
            {
                switch($type)
                {
                    case 0:
                        $user->favourites = $user->favourites.$id.'-package;';
                        break;
                    case 1:
                        $user->favourites = $user->favourites.$id.'-product;';
                        break;
                    case 2:
                        $user->favourites = $user->favourites.$id.'-accessory;';
                        break;
                }
                $user->save();
            }
        }
        else
        {
            session()->put('back', 'shopping/cart');
            return redirect('login');
        }

        $carts = Cart::content();
        $bikes = [];
        $packages = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                {
                    $packages[] = $v->id;
                }
                if($v->options['type'] == 2)
                {
                    $bikes[] = $v->id;
                }
                $qty[$v->id] = $v->qty;
                $rows[$v->id] = $v->rowId;
                $this->country = $v->options['area'];
            }
            $this->qty = $qty;
            $this->rows = $rows;
            $this->total = Cart::subtotal(0,'','');
        }
        $collection = collect();
        $bikes = Products::whereIn('id', $bikes)->get();
        if($bikes)
        {
            foreach ($bikes as $bike)
                $collection->push($bike);
        }
        $packages = Packages::whereIn('id', $packages)->get();
        if($packages)
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
    }

    public function redeem_code($value)
    {
        $carts = Cart::content();
        $ids = [];
        $packs = [];
        if(Cart::count() > 0)
        {
            foreach($carts as $k => $v)
            {
                if($v->options['type'] == 1)
                    $packs[] = $v->id;
                if($v->options['type'] == 2)
                    $ids[] = $v->id;
            }
            if(count($ids) > 0)
                $products = Products::whereIn('id', $ids)->get();
            if(count($packs) > 0)
            {
                $packages = Packages::whereIn('id', $packs)->get();
                foreach($packages as $k => $v)
                {
                    $ids[] = $v->product->id;
                }
            }
            $cates = [];
            $models = [];
        }
        $collection = collect();
        if(!empty($products))
        {
            foreach ($products as $bike)
                $collection->push($bike);
        }
        if(!empty($packages))
        {
            foreach ($packages as $package)
                $collection->push($package);
        }

        $this->dataCart = $products = $collection;
        foreach($products as $k => $v)
        {
            $cates[] = $v->category_id;
            $models[] = $v->model;
        }
        if($value)
        {
            $promo = Promos::where([['code', $value], ['status', 1], ['start', '<=', date('Y-m-d')], ['end', '>=', date('Y-m-d')], ['currency', $this->currency]])->first();
            if(!empty($promo))
            {
                if(empty($promo->model_id) && empty($promo->product_id) && in_array($promo->category_id, $cates))
                {
                    $this->promo = round(($this->total * $promo->rate)/100);
                    $this->promo_id = $promo->id;
                    $this->promo_code = $value;
                    $this->promo_contect = trans('frontend.store.promo_success');
                }
                else if(empty($promo->product_id) && in_array($promo->model_id, $models))
                {
                    $total = 0;
                    foreach($products as $k => $v)
                    {
                        if($v->model == $promo->model_id)
                            $total += $v->price;
                    }
    
                    $this->promo = round(($total * $promo->rate)/100);
                    $this->promo_id = $promo->id;
                    $this->promo_code = $value;
                    $this->promo_contect = trans('frontend.store.promo_success');
                }
                else if($promo->product_id)
                {
                    $product = Products::find($promo->product_id);
                    if($product)
                    {
                        $this->promo = round(($product->price * $promo->rate)/100);
                        $this->promo_id = $promo->id;
                        $this->promo_code = $value;
                        $this->promo_contect = trans('frontend.store.promo_success');
                    }
                }
                else 
                {
                    $this->promo = null;
                    $this->promo_id = null;
                    $this->promo_code = null;
                    $this->promo_contect = trans('frontend.store.not_applicable');
                }
            }
            else {
                $this->promo = null;
                $this->promo_id = null;
                $this->promo_code = null;
                $this->promo_contect = trans('frontend.store.promo_fail');
            }
        }
        else
        {
            $this->promo = null;
            $this->promo_id = null;
            $this->promo_code = null;
            $this->promo_contect = '';
        }
    }

    public function checked_credit()
    {
        $this->credit = true;
        $this->paypal = false;
    }

    public function checked_paypal()
    {
        $this->paypal = true;
        $this->credit = false;
    }

}
