<?php

namespace App\Admin\Controllers;

use App\Models\Packages;
use App\Models\Category;
use App\Models\BikeModel;
use App\Models\Products;
use App\Models\Currency;
use App\Models\Store;
use App\Models\Area;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;
use DB;

class PackagesConrtoller extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('組合商品')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.detail'))
            ->description('列表')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.edit'))
            ->description('列表')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('admin.create'))
            ->description('列表')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Packages);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });
        /*
        $grid->column('category_id', 'Category')->display(function(){
            return "<span class='label label-primary'>".$this->category->name."</span>";
        });
        $grid->column('model','Model')->display( function($model_id){
            return "<span class='label label-primary'>".$this->bike_model->bike_model."</span>";

        });
        */
        $grid->name('組合名稱');
        $grid->product_code('商品編號');
        $grid->quantity('數量');
        $grid->currency('幣別')->display( function($currency_id){
            $currency_name = Currency::where('id',$currency_id)->pluck('code')->first();
            return $currency_name;

        });
        $grid->price('價格');
        $grid->products('組合商品')->display(function () {

            $data = '';
            $result = Products::whereIn('id',$this->products)->get();
            foreach($result as $product)
            {
                $data .= "<p>{$product->product_name} - {$product->color} - {$product->locale}<p>";
            }

            return $data;
        });
        $grid->images('圖片')->image();
        $grid->locale('語系')->using(['en' => 'En', 'zh-TW' => 'zh-TW']);

        $grid->shipping_size('運送尺寸');

        $grid = $this->sortGridByDate($grid);

        $grid->disableFilter();

        $grid->setDialogFormDimensions('90%', '95%');
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Packages);

        Admin::script('
        var _URL = window.URL || window.webkitURL;

        $(\'input[type="file"]\').change(function(e) {
            var file, img;

            if ((file = this.files[0])) {
                img = new Image();
                img.onload = function() {
                    if(this.width > 1600 || this.height > 1600)
                    {

                        alert("圖片尺寸超過限制 :" + this.width + " x " + this.height);

                        window.location.reload();
                    }
                };

                img.src = _URL.createObjectURL(file);
            }

        });
        ');

        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();
        $form->text('name', '組合名稱')->required();
        $form->text('product_code', '商品編號')->required();
        $form->text('description', '商品描述');
        $form->textarea('Head', 'Head內容');
        $form->number('quantity', '數量');
        $form->select('currency', '幣別')->options(Currency::all()->pluck('code','id'))->required();
        $form->currency('price', '價格')->required();
        $form->multipleImage('images', '圖片')->removable()->rules('image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=1920,max_height=1920')->autoUpload();
        $form->multipleSelect('products','組合商品')->options(Products::select(DB::raw('CONCAT(product_name, " - ", COALESCE(color,""), " - ", locale) AS product, id'))->get()->pluck('product','id'))->required();
        $form->saving(function (Form $form) {
            $products = Products::whereIn('id',$form->products)->get();
            $shipping_size = 0;
            foreach( $products as $prod){
                $shipping_size+=$prod->shipping_size;
            }
            $form->shipping_size = $shipping_size;
        });
        $form->display('shipping_size', '運送尺寸');
        $form->table('vendors', '代理商', function ($table) {
            $table->select('country', '國家')->options(Area::all()->pluck('AreaEngName','AreaEngName'));
            $table->text('store', '名稱');
            $table->email('email');
            $table->text('phone', '電話');
            $table->text('address','地址');
        });
        $form->editor('content', '內容');
        $form->editor('specifications','規格表');
        return $form;
    }
}
