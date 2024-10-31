<?php

namespace App\Admin\Controllers;

use App\Models\OrderDetail;
use App\Models\Note;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class OrderDetailController extends Controller
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
        $grid = new Grid(new OrderDetail);

        $grid->id('ID');
        $grid->order_id('order_id');
        $grid->product_type('product_type');
        $grid->package_id('package_id');
        $grid->product_id('product_id');
        $grid->quantity('quantity');
        $grid->delivery_type('delivery_type');
        $grid->delivery_date('delivery_date');
        $grid->warranty_extens_id('warranty_extens_id');

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
        $show = new Show(OrderDetail::findOrFail($id));

        $show->id('ID');
        $show->order_id('order_id');
        $show->product_type('product_type');
        $show->package_id('package_id');
        $show->product_id('product_id');
        $show->quantity('quantity');
        $show->delivery_type('delivery_type');
        $show->delivery_date('delivery_date');
        $show->warranty_extens_id('warranty_extens_id');
        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OrderDetail);

        $form->display('ID');
        $form->text('order_id', 'order_id');
        $form->text('product_type', 'product_type');
        $form->text('package_id', 'package_id');
        $form->text('product_id', 'product_id');
        $form->text('quantity', 'quantity');
        $form->text('delivery_type', 'delivery_type');
        $form->text('delivery_date', 'delivery_date');
        $form->text('warranty_extens_id', 'warranty_extens_id');
        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
