<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Http\Controllers\Controller;
use Dcat\Admin\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class AreaController extends Controller
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
        $grid = new Grid(new Area);

        $grid->id('ID');
        $grid->AreaNo('AreaNo');
        $grid->AreaName('AreaName');
        $grid->AreaCnName('AreaCnName');
        $grid->AreaEngName('AreaEngName');
        $grid->AreaJpName('AreaJpName');
        $grid->AreaKrName('AreaKrName');
        $grid->created_at(trans('admin.created_at'));
        $grid->updated_at(trans('admin.updated_at'));

        $grid->disableFilter();
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
        $show = new Show(Area::findOrFail($id));

        $show->id('ID');
        $show->AreaNo('AreaNo');
        $show->AreaName('AreaName');
        $show->AreaCnName('AreaCnName');
        $show->AreaEngName('AreaEngName');
        $show->AreaJpName('AreaJpName');
        $show->AreaKrName('AreaKrName');
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
        $form = new Form(new Area);

        $form->display('ID');
        $form->text('AreaNo', 'AreaNo');
        $form->text('AreaName', 'AreaName');
        $form->text('AreaCnName', 'AreaCnName');
        $form->text('AreaEngName', 'AreaEngName');
        $form->text('AreaJpName', 'AreaJpName');
        $form->text('AreaKrName', 'AreaKrName');
        $form->display(trans('admin.created_at'));
        $form->display(trans('admin.updated_at'));

        return $form;
    }
}
