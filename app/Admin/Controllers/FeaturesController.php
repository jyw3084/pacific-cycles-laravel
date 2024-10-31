<?php

namespace App\Admin\Controllers;

use App\Models\Features;
use App\Models\Products;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class FeaturesController extends Controller
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
            ->header('Index')
            ->description('description')
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Features);

        $grid->id('ID');
        $grid->column('product_features.product_name','Product');
        $grid->type('Type')->display(function ($type){
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
        $grid->value('Details')->limit(100);
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
        
        $grid->disableColumnSelector();

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
        $show = new Show(Features::findOrFail($id));

        $show->id('ID');
        $show->product_id('Product')->as(function () {
            $id = $this->getKey();
            $feat = Features::find($id);
            $product = Products::where('id', $feat->product_id)->pluck('product_name')->first();
            return $product;
        });
        $show->type('Type')->as(function ($type){
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
        $show->value('Details');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Features);

        $form->select('product_id', 'Product')->options(Products::all()->pluck('product_name','id'));
        $form->select('type')->options(['1'=>'Text', '2'=>'Link', '3'=>'Image']);
        $form->textarea('value');

        return $form;
    }
}
