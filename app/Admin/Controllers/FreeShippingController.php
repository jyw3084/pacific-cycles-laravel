<?php

namespace App\Admin\Controllers;

use App\Models\FreeShipping;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class FreeShippingController extends Controller
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
            ->header('免運')
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
        $grid = new Grid(new FreeShipping);

        $grid->type('商品類型')->using([1 => 'Package', 2 => 'Bike', 3 => 'Accessories', 4 => 'All']);
        $grid->amount('金額')->display(function(){
            return $this->amount.' ('.$this->currency.')';
        });
        $grid->status('狀態')->switch();
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
        $form = new Form(new FreeShipping);

        $form->select('type', '商品類型')->options([1 => 'Package', 2 => 'Bike', 3 => 'Accessories', 4 => 'All'])->required();
        $form->text('amount', '金額')->required();
        $form->select('currency', '幣別')->options(Currency::all()->pluck('code','code'))->required();
        $form->switch('status', '狀態')->default(1);

        return $form;
    }
}
