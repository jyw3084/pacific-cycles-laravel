<?php

namespace App\Admin\Controllers;

use App\Models\Header;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class HeaderController extends Controller
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
            ->header('Header設定')
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
        $grid = new Grid(new Header);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });

        $grid->disableFilter();
        $grid->disablePagination();
        $grid->disableRowSelector();

        $grid->column('title', '標題');
        $grid->parent_id('上層')->display(function($parent_id){
            if(empty($parent_id))
                return 'Root';
            return \App\Models\Header::find($parent_id)->title;
        });
        $grid->link('連結');
        $grid->locale('語系')->using(['en' => 'En', 'zh-TW' => 'zh-TW']);
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
        $menuModel = \App\Models\Header::class;
        $form = new Form(new Header);

        $form->text('title', '標題')->required();
        $form->text('link', '連結');
        $form->select('parent_id', '上層')->options($menuModel::selectOptions());
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();

        return $form;
    }
}
