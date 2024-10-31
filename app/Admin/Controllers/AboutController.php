<?php

namespace App\Admin\Controllers;

use App\Models\About;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;

class AboutController extends Controller
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
        $grid = new Grid(new About);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });
        Admin::script('
            $(".grid-modal .modal-dialog").css("width", "80%");
            $(".modal-body").css("overflow-x", "scroll");
        ');

        $grid->column('title', '標題');
        $grid->link('連結');
        $grid->content('內容')->display(function(){
            return '顯示';
        })->modal('items', function ($model) {

            return $this->content;
        });
        $grid->locale('語系')->using(['en' => 'En', 'zh-TW' => 'zh-TW']);


        $grid->disableFilter();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
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
        $form = new Form(new About);

        $form->text('title', '標題');
        if ($form->isEditing()){
            $form->tools(function (Form\Tools $tools) {
                // Disable `Delete` btn.
                $tools->disableDelete();
            });
            $form->display('link', '連結');
        }
        else
            $form->text('link', '連結')->required();
        $form->editor('content', '內容');
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();


        return $form;
    }
}
