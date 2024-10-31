<?php

namespace App\Admin\Controllers;

use App\Models\Promos;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use App\Models\Category;
use App\Models\BikeModel;
use App\Models\Products;
use App\Models\Order;

class PromosController extends Controller
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
            ->header('優惠代碼')
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
        $grid = new Grid(new Promos);

        $grid->created_at('建立日期')->display(function(){
            return date('Y-m-d', strtotime($this->created_at));
        });
        $grid->code('代碼');
        $grid->category_id('類別')->display(function($category_id) {
            $category = Category::find($category_id);
            return $category->name ?? '';
        });
        $grid->model_id('車型')->display(function($model_id) {
            $model = BikeModel::find($model_id);
            return $model->bike_model ?? '';
        });
        $grid->product_id('車輛')->display(function($product_id) {
            $product = Products::find($product_id);
            return $product->product_name ?? '';
        });
        $grid->status('狀態')->switch();
        $grid->rate('折扣%');
        $grid->currency('幣別');
        $grid->start('開始日期');
        $grid->end('結束日期');
        $grid->id('使用代碼數量')->display(function(){
            return Order::where([['paid', 1], ['promo_id', $this->id]])->count();
        });

        $grid->filter(function($filter){

            $filter->disableIdFilter();

            $filter->like('code', '代碼');

        });
        $grid = $this->sortGridByDate($grid);
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Promos);
        $form->display('code', '代碼');
        $form->rate('rate', '折扣%')->required();
        $form->select('currency', '幣別')->options(['TWD' => 'TWD', 'USD' => 'USD'])->required();
        $form->date('start', '開始日期')->format('YYYY-MM-DD')->required();
        $form->date('end', '結束日期')->format('YYYY-MM-DD')->required();
        $form->select('category_id', '類別')->options(Category::all()->pluck('name', 'id'))->load('model_id', '/api/bike_model')->required();
        $form->select('model_id', '車型')->options(BikeModel::all()->pluck('bike_model', 'id'))->load('product_id', '/api/product');
        $form->select('product_id', '車輛')->options(Products::all()->pluck('product_name', 'id'));
        $form->switch('status', '狀態')->default(0);

        $form->saving(function (Form $form) {
            if(!$form->model()->id)
            {
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';

                for ($i = 0; $i < 8; $i++) {
                    $index = rand(0, strlen($characters) - 1);
                    $randomString .= $characters[$index];
                }
                $form->code = $randomString;
            }
        });
        return $form;
    }
}
