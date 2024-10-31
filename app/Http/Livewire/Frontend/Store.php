<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Category;
use App\Models\BikeModel;
use App\Models\Packages;
use App\Models\Products;
use App\Models\Banner;
use App\Models\Head;
use Livewire\WithPagination;
use App\Http\Controllers\Shop\ShopController;

class Store extends Component
{
	use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $categories = [];
    public $types = [];
    public $packages = [];
    public $features = [];
    public $search;
    public $category = [];
    public $type = [];

    public function mount(){
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();

        $this->categories = Category::where('locale', $lang)->get()->pluck('name', 'id');
        $this->types = BikeModel::where('locale', $lang)->get()->pluck('bike_model', 'id');

        $this->showCategories = Category::where([['locale', $lang],['show_on_home', true]])->orderBy('priority', 'desc')->get();
        $this->showModels = BikeModel::where([['locale', $lang],['show_on_home', true]])->orderBy('priority', 'desc')->get();

        $this->packages = Packages::where('locale', $lang)->get();

        $this->features = Products::where([['type', 1], ['locale', $lang], ['is_featured', 1]])->groupBy('product_name')->get();

        $this->search = request()->get('search');
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
    }
    public function render()
    {
        $shop = new ShopController();
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
    	$query = '%' . trim($this->search) . '%';
        if(!empty(request()->all()['category']))
        {
            $this->category = request()->all()['category'];
        }
        if(!empty(request()->all()['type']))
        {
            $this->type = request()->all()['type'];
        }
        $modelCollection = array();
        $categoryCollection = array();
        foreach ($this->showModels as $model){
            $modelCollection[] = [
                'model' => $model->bike_model,
                'products' => $this->getModelCollection($model->id),
                'slug' => $model->slug
            ];
        }
        foreach ($this->showCategories as $cat){
            $categoryCollection[] = [
                'category' => $cat->name,
                'products' => $this->getCategoryCollection($cat->id),
                'slug' => $cat->slug
            ];
        }
        return view('livewire.frontend.store', [
                'packages' => $this->packages,
                'features' => $this->features,
                'displayCats' => $categoryCollection,
                'displayModels' => $modelCollection,
                'banner' => Banner::where('link', 'store')->first(),
                'bikes' => Products::where([['type', 1], ['locale', $lang], ['product_name','like', $query]])->where(function($q) {
                    if($this->category)
                    {
                        $q->whereRaw($shop->getCategoryQuery($this->category));
                    }
                    if($this->type)
                    {
                        $q->whereRaw($shop->getModelQuery($this->type));
                    }
                })->groupBy('product_name')->paginate(6),
                'accessories' => Products::where([['type', 2], ['locale', $lang], ['product_name','like', $query]])->where(function($q) {
                    if($this->category)
                    {
                        $q->whereRaw($shop->getCategoryQuery($this->category));
                    }
                    if($this->type)
                    {
                        $q->whereRaw($shop->getModelQuery($this->type));
                    }
                })->groupBy('product_name')->paginate(6),
                'categories' => $this->categories,
                'types' => $this->types
            ]
        )->extends('layouts.store-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }

    public function getCategoryCollection($category){
        $shop = new ShopController();
        $lang = app()->getLocale();
        $products = Products::where([['locale', $lang]])
            ->whereRaw($shop->getCategoryQuery([$category]))
            ->orderByRaw('priority desc')
            ->paginate(6);

        return $products;
    }

    public function getModelCollection($model){
        $shop = new ShopController();
        $lang = app()->getLocale();
        $products = Products::where([['type', 1], ['locale', $lang]])
            ->whereRaw($shop->getModelQuery([$model]))
            ->orderByRaw('priority desc')
            ->paginate(6);

        return $products;
    }


}


