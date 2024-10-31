<?php

namespace App\Admin\Controllers;

use App\Models\Products;
use App\Models\BikeModel;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;

class BikesController extends Controller
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
            ->header(trans('admin.index'))
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
        $grid = new Grid(new Products);
        $grid->model()->where('product_type', 1);
        
        $grid->product_name('Bike Name');
        $grid->product_code('Product Code');
        $grid->quantity('Quantity');
        $grid->price('Price');
        $grid->color('Color')->label('primary');
        $grid->images('Images')->image();
        $grid->is_featured('Featured')->display( function($featured){
            if ($featured == 1){
                return "<span class='label label-success'>Featured</span>";
            }
            else{
                return "<span class='label label-primary'>Not Featured</span>";
            }
        });
        // $grid->type('Type');
        $grid->column('model','Model')->display( function($model_id){
            $model_name = BikeModel::where('id',$model_id)->pluck('bike_model')->first();
            return "<span class='label label-primary'>".$model_name."</span>";

        });
        

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Products::findOrFail($id));

        $show->product_name('Bike Name');
        $show->product_code('Product Code');
        $show->quantity('Quantity');
        $show->price('Price');
        $show->color('Color');
        $show->images('Images')->image();
        $show->is_featured('Featured')->as(function($f){
            if($f == 1){
                return "True";
            }
            else{
                return "False";
            }
        });
        // $show->type('Type');
        $show->model('Model')->as( function($model_id){
            $model_name = BikeModel::where('id',$model_id)->pluck('bike_model')->first();
            return $model_name;

        });
        $show->currency('Currency')->as( function($currency_id){
            $currency_name = Currency::where('id',$currency_id)->pluck('code')->first();
            return $currency_name;

        });
        $show->shipping_size('Shipping Size');
        $show->features('Features', function ($features){
            $features->resource('/admin/features');
            $features->disableExport();
            $features->disableColumnSelector();
            $features->disableRowSelector();
            $features->disableFilter();
            $features->disableActions();
            $features->disableCreateButton();

            $features->type('Type')->display(function ($type){
                if($type == 1){
                    return "Text";
                }
                if($type == 2){
                    return "Link";
                }
                if($type == 3){
                    return "Image";
                }
            });
            $features->value('Details')->limit(100);
        });
        $show->criterias('Criterias', function ($criterias){
            $criterias->resource('/admin/features');
            $criterias->disableExport();
            $criterias->disableColumnSelector();
            $criterias->disableRowSelector();
            $criterias->disableFilter();
            $criterias->disableActions();
            $criterias->disableCreateButton();

            
            $criterias->criteria('Criteria');
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Products);

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
        $form->text('product_name', 'Bike Name');
        $form->hidden('product_type')->default(1);
        $form->number('quantity', 'Quantity');
        $form->currency('price', 'Price');
        $form->select('color', 'Color')->options(['white' => 'White', 
                                                  'black' => 'Black', 
                                                  'red' => 'Red', 
                                                  'blue' => 'Blue',
                                                  'yellow' => 'Yellow',
                                                  'n/a' => 'N/A']);
        $form->multipleImage('images', 'Images')->sortable()->removable()->rules('image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=1920,max_height=1920')->autoUpload();
        $states = [
            'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
        ];
        $form->switch('is_featured', 'Featured')->states($states);
        // $form->select('type', 'type');
        $form->select('model', 'Model')->options(BikeModel::all()->pluck('bike_model','id'));
        $form->textarea('specifications','Specifications');
        $form->select('currency', 'Currency')->options(Currency::all()->pluck('code','id'));
        $form->number('shipping_size','Shipping Size');
        $form->hasMany('features', 'Features', function (Form\NestedForm $form){
            $form->select('type', 'Type')->options(['1'=>'Text', '2'=>'Link']);
            $form->textarea('value', 'Details');
        });
        $form->hasMany('criterias', 'Criterias', function (Form\NestedForm $form){
            $form->text('criteria', 'Criteria');
        });
        $form->saved(function (Form $form) {
            $product = $form->model();
            $id = $product->id;
            $getLength = strlen($id);
            $zeroes = "";
            for($x = $getLength; $x <= 6; $x ++){
                $zeroes.='0';
            }
            $product->product_code = 'EC_P'.$zeroes.$id;
            $product->save();
        });

        return $form;
    }
}
