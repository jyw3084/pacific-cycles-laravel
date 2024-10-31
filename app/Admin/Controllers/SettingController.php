<?php

namespace App\Admin\Controllers;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class SettingController extends Controller
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
            ->header('參數設定')
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
        $grid = new Grid(new Setting);

        $grid->key('鍵值(只能輸入英文)');
        $grid->value('設定值')->display(function ($title, $column) {

            switch($this->key)
            {
                case 'birthday_trigger':
                    return $column->switch();
                    break;
                case 'regist_trigger':
                    return $column->switch();
                    break;
            }

            return $column->editable();
        });
        $grid->remark('備註');
        $grid = $this->sortGridByDate($grid);
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->disableActions();

        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->text('key', '鍵值(只能輸入英文)');
            $create->text('value', '設定值');
            $create->text('remark', '備註');
        });
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Setting);

        $form->text('key', '鍵值(只能輸入英文)');
        $form->text('value', '設定值');
        $form->text('remark', '備註');

        return $form;
    }
}
