<?php

namespace App\Admin\Controllers;

use App\Models\WarrantyExtension;
use App\Models\Products;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class WarrantyExtensionController extends Controller
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
            ->header('延長保固')
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
        $grid = new Grid(new WarrantyExtension);

        $grid->duration('持續時間(年)');
        $grid->price('價格')->display(function($price){
            return $this->currencies->code.' '.$price;
        });
        $grid->product_id('商品')->display( function($id){
            $product = Products::where('id',$id)->first();
            if($product!=null) {
                return $product->product_name;
            }
        });
        $grid = $this->sortGridByDate($grid);
        $grid->disableFilter();
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WarrantyExtension);

        $form->select('product_id', '商品')->options(Products::where('type',1)->pluck('product_name','id'))->required();
        $form->number('duration', '持續時間(年)')->required();
        $form->currency('price', '價格')->required();
        $form->select('currency', '幣別')->options(Currency::all()->pluck('code','id'))->required();

        return $form;
    }
}
