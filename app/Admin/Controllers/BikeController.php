<?php

namespace App\Admin\Controllers;

use App\Models\Bike;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use App\Models\Category;
use Dcat\Admin\Widgets\Table;
use Dcat\Admin\Admin;
use Storage;
use Dcat\Admin\Http\Controllers\AdminController;

class BikeController extends Controller
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
            ->header('車輛分類')
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
        $grid = new Grid(new Bike);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });
        Admin::script('
            $(".grid-modal .modal-dialog").css("width", "80%");
            $(".modal-body").css("overflow-x", "scroll");
        ');

        if(request()->is('*/pages'))
        {
            $grid->model()->where('type', 1);
            $grid->column('category_id', '類別')->display(function(){
                return $this->category->name;
            });
            $grid->column('title', '標題');
            $grid->column('locale', '語系');
        }
        else
        {
            $grid->model()->where('type', 0);
            $grid->column('category_id', '類別')->display(function(){
                return $this->category->name;
            });
            $grid->column('title', '標題');
            $grid->column('content', '內容')->width(800);
            $grid->column('items', '項目')->display(function(){
                return '顯示';
            })->modal('項目', function ($model) {

                return $this->items;
            });
            $grid->column('locale', '語系');

            $grid->disableCreateButton();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableView();
            });
        }

        $grid->disableFilter();
        $grid->filter(function($filter){

            $filter->disableIdFilter();

            $filter->like('title', '標題');
            $filter->like('content', '內容');

        });
        $grid = $this->sortGridByDate($grid);

        $grid->setDialogFormDimensions('90%', '95%');
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Bike);

        $form->select('category_id', '類別')->options(Category::all()->pluck('name', 'id'))->required();
        $form->text('title', '標題');
        if(request()->is('*/pages/*'))
        {
            $form->editor('content', 'header');
            $form->editor('items', 'footer');
        }
        else
        {
            $form->text('name', '分類名稱');
            $form->textarea('content', '內容');
            $form->editor('items', '項目');
        }
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();

        return $form;
    }
}
