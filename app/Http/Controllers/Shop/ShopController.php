<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\BikeModel;
use App\Models\Category;
use App\Models\Products;
use Livewire\WithPagination;
use function GuzzleHttp\Promise\all;

class ShopController extends Controller {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $filter_attributes = [
        'color'
    ];

    public function index(){

    }

    public function category($category){
        app()->setLocale(session()->get('locale'));
        $categoryObject = Category::where([['locale', app()->getLocale()],['slug', $category]])->first();
        $modelObject = BikeModel::where([['locale', app()->getLocale()],['slug', $category]])->first();
        if($categoryObject==null && $modelObject==null){
            abort(404);
        }
        if($categoryObject!=null) {
            $products = Products::where([['locale', app()->getLocale()]])
                ->selectRaw('*, MAX(priority) as pry')
                ->whereRaw($this->getCategoryQuery([$categoryObject->id]))
                ->groupBy('product_name')
                ->orderByRaw('MAX(priority) desc');
            $models = BikeModel::where('locale',  app()->getLocale())->get()->pluck('bike_model', 'id');
        } else {
            $products = Products::where([['locale', app()->getLocale()]])
                ->selectRaw('*, MAX(priority) as pry')
                ->whereRaw($this->getModelQuery([$modelObject->id]))
                ->groupBy('product_name')
                ->orderByRaw('MAX(priority) desc');
            $models = null;
        }

        //check filters
        foreach($this->filter_attributes as $filter_key){
            if(!empty(request()->all()[$filter_key]))
            {
                $products->whereIn('color', request()->all()[$filter_key]);
            }
        }

        //category filter
        if(!empty(request()->all()['category']))
        {
            $products->whereRaw($this->getCategoryQuery(request()->all()['category']));
        }

        //bikemodel filter
        if(!empty(request()->all()['type']))
        {
            $products->whereRaw($this->getModelQuery(request()->all()['type']));
        }

        if(!empty(request()->all()['search'])){
            $products->where([['product_name', 'like', '%'.request()->all()['search'].'%'],['description', 'like', '%'.request()->all()['search'].'%']]);
        }

        $products = $products->paginate(20);
        return view('shop.category', [
            'title' => $categoryObject->name ?? $modelObject->bike_model,
            'filters' => $this->makeFilters($categoryObject->id ?? $modelObject->id),
            'products' => $products,
            'categories' => $this->joinCategory($categoryObject->id ?? 'zz', Category::where('locale',  app()->getLocale())->get()->pluck('id')),
            'types' => $models
        ]);
    }

    public function getCategoryQuery($categories = []){
        $i = 0;
        $queryString = "";
        foreach ($categories as $category) {
            if($i!=0){
                $queryString .= " || ";
            }
            $queryString .= "FIND_IN_SET('$category', category_id)";
            $i++;
        }
        return $queryString;
    }

    public function getModelQuery($models = []){
        $i = 0;
        $queryString = "";
        foreach ($models as $model) {
            if($i!=0){
                $queryString .= " || ";
            }
            $queryString .= "FIND_IN_SET('$model', model)";
            $i++;
        }
        return $queryString;
    }

    public function joinCategory($category, $categories){
        $category_array = [];
        foreach($categories as $cat) {
            if($cat == $category){
                continue;
            }
            $products = Products::where([['locale', app()->getLocale()]])
                ->whereRaw($this->getCategoryQuery([$category, $cat]))
                ->groupBy('product_name');
            if(count($products->get()) > 0) {
                $category_array[] = $cat;
            }
        }
        return Category::whereIn('id',  $category_array)->get()->pluck('name', 'id');
    }

    public function makeFilters($category){
        $filter_array = [];

        $products = Products::where([['locale', app()->getLocale()]])
            ->whereRaw($this->getCategoryQuery([$category]))
            ->groupBy('product_name')->get();

        foreach ($this->filter_attributes as $filter_attribute) {
            $options = [];
            foreach ($products as $product){
                $option = $product->{$filter_attribute};
                if($option!='' && $option!='n/a' && !in_array($option, $options)){
                    array_push($options, $option);
                }
            }
            $filter_array[$filter_attribute] = $options;
        }

        return $filter_array;
    }

}
