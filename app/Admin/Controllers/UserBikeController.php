<?php

namespace App\Admin\Controllers;

use App\Models\UserBike;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class UserBikeController extends Controller
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
        $grid = new Grid(new UserBike);

        $grid->id('ID');
        $grid->BikeImage('BikeImage');
        $grid->IsTransfer('IsTransfer');
        $grid->IsCanBuyWarranty('IsCanBuyWarranty');
        $grid->ProductNo('ProductNo');
        $grid->BBNo('BBNo');
        $grid->BuyAreaNo('BuyAreaNo');
        $grid->BuyAreaName('BuyAreaName');
        $grid->BuyCompanyNo('BuyCompanyNo');
        $grid->BuyCompanyName('BuyCompanyName');
        $grid->BuyDate('BuyDate');
        $grid->BicycleTypeID('BicycleTypeID');
        $grid->BicycleTypeName('BicycleTypeName');
        $grid->ModelsID('ModelsID');
        $grid->Model('Model');
        $grid->Color('Color');
        $grid->WarrantyStartDT('WarrantyStartDT');
        $grid->WarrantyEndDT('WarrantyEndDT');
        $grid->WarrantyStartDT2('WarrantyStartDT2');
        $grid->WarrantyEndDT2('WarrantyEndDT2');
        $grid->BuyWarranty('BuyWarranty');
        $grid->WarrantyCompanyNo('WarrantyCompanyNo');
        $grid->WarrantyCompanyName('WarrantyCompanyName');
        $grid->CustomerProductNo('CustomerProductNo');
        $grid->CustomerProductName('CustomerProductName');
        $grid->CMc('CMc');
        $grid->created_at(trans('admin.created_at'));
        $grid->updated_at(trans('admin.updated_at'));

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserBike);

        $form->display('ID');
        $form->text('BikeImage', 'BikeImage')->rules('image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=1920,max_height=1920');
        $form->text('IsTransfer', 'IsTransfer');
        $form->text('IsCanBuyWarranty', 'IsCanBuyWarranty');
        $form->text('ProductNo', 'ProductNo');
        $form->text('BBNo', 'BBNo');
        $form->text('BuyAreaNo', 'BuyAreaNo');
        $form->text('BuyAreaName', 'BuyAreaName');
        $form->text('BuyCompanyNo', 'BuyCompanyNo');
        $form->text('BuyCompanyName', 'BuyCompanyName');
        $form->text('BuyDate', 'BuyDate');
        $form->text('BicycleTypeID', 'BicycleTypeID');
        $form->text('BicycleTypeName', 'BicycleTypeName');
        $form->text('ModelsID', 'ModelsID');
        $form->text('Model', 'Model');
        $form->text('Color', 'Color');
        $form->text('WarrantyStartDT', 'WarrantyStartDT');
        $form->text('WarrantyEndDT', 'WarrantyEndDT');
        $form->text('WarrantyStartDT2', 'WarrantyStartDT2');
        $form->text('WarrantyEndDT2', 'WarrantyEndDT2');
        $form->text('BuyWarranty', 'BuyWarranty');
        $form->text('WarrantyCompanyNo', 'WarrantyCompanyNo');
        $form->text('WarrantyCompanyName', 'WarrantyCompanyName');
        $form->text('CustomerProductNo', 'CustomerProductNo');
        $form->text('CustomerProductName', 'CustomerProductName');
        $form->text('CMc', 'CMc');
        $form->display(trans('admin.created_at'));
        $form->display(trans('admin.updated_at'));

        return $form;
    }
}
