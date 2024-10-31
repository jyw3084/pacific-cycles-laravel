<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;
use App\Models\Products;
use App\Models\UserBike;
use App\Models\WarrantyExtension;
use App\Models\BikeModel;

class Extend extends Component
{
    public $price = 0;
    public $currency = 'USD';
    public $currencies = [1 => 'USD', 2 => 'TWD'];
    public $year = 0;
    public $ProductNo;
    protected $listeners = ['checked'];

    public function mount($ProductNo){
        app()->setLocale(session()->get('locale'));
        $locale = app()->getLocale();
        
        $user = auth()->user();
        $bike = UserBike::where('ProductNo', $ProductNo)->first();
        $model = BikeModel::where([['bike_model', $bike->BicycleTypeName], ['locale', $locale]])->first() ?? null;
        if(!$model)
            return redirect('my-bike')->with(['message' => __('frontend.dashboard.product_exist')]);
        
        $product = Products::where('model', $model->id)->first();

        if(!$product)
            return redirect('my-bike')->with(['message' => __('frontend.dashboard.product_exist')]);
        
        $this->bike = $bike;
        $currency = app()->getLocale() == 'en' ? 1 : 2;
        $this->warrantis = WarrantyExtension::where([['product_id', $product->id], ['currency', $currency]])->get();
        $this->ProductNo = $ProductNo;
    }
    
    public function render()
    {
        return view('livewire.back.extend')
        ->extends('layouts.back.backend-layout',[
            'price' => $this->price,
            'currency' => $this->currency,
            'ProductNo' => $this->ProductNo,
            'year' => $this->year,
        ])
        ->section('content');
    }


    public function checked()
    {

    }

    public function extend($id)
    {
        $user = auth()->user();
        if(is_numeric($id))
        {
            $extend = WarrantyExtension::find($id);
            
            $this->price = $extend->price;
            $this->currency = $this->currencies[$extend->currency];
            $this->year = $extend->duration;
        }
        $this->emit('checked');
    }
}
