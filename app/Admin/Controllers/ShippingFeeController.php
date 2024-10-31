<?php

namespace App\Admin\Controllers;

use App\Models\ShippingFee;
use App\Models\Currency;
use App\Models\Area;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use DB;

class ShippingFeeController extends Controller
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
            ->header('地區')
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
        $grid = new Grid(new ShippingFee);

        $grid->column('title', '地區');
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
        $form = new Form(new ShippingFee);

        $form->text('title', '名稱')->required();
        $form->table('area', '地區', function ($table) {
            $table->select('country', '國家')->options(Area::select(DB::raw('CONCAT(AreaCnName, " - ", AreaEngName) AS full_name, AreaEngName'))->pluck('full_name','AreaEngName'))->required();
        });
        $form->table('range', '範圍', function ($table) {
            $table->text('size_range', '尺寸')->required();
            $table->text('shipping_fee', '運費')->required();
            $table->select('currency', '幣別')->options(Currency::all()->pluck('code','code'))->required();
        });

        return $form;
    }
}
