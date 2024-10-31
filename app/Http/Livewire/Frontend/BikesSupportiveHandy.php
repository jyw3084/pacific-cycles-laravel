<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Banner;
use App\Models\Products;
use App\Models\BikeModel;
use App\Models\Category;
use App\Models\Bike;
use App\Models\Head;
use Livewire\WithPagination;

class BikesSupportiveHandy extends Component
{
	use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $bike = Bike::where([['title', 'HANDY Foldable'], ['locale', $lang]])->first();
        $header = $bike->content;
        $footer = $bike->items;

    	$bikeModel = new BikeModel;
    	$categoryModel = new Category;
    	$productModel = new Products;
    	$getBikeModel = $bikeModel->where([['bike_model', 'HANDY Foldable'], ['locale', $lang]])->first();
    	$getCategory = $categoryModel->where('id', $getBikeModel->category_id)->first();
    	$getProducts = $productModel->where([['category_id', $getCategory->id], ['model',$getBikeModel->id], ['type', 1]])->paginate(4);
        $head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
        return view('livewire.frontend.bikes-supportive-handy', [
            'banner' => Banner::where('link', 'bikes')->first(),
            'category' => $getCategory->name,
            'bike_model' => $getBikeModel->bike_model,
            'products' => $getProducts,
            'header' => $header,
            'footer' => $footer,
        ])
        ->extends('layouts.bikes-layout', [
            'head' => $head,
        ])
        ->section('content');
    }
}
