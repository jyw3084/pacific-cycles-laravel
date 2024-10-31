<?php

namespace App\Admin\Controllers;

use App\Models\EmailManagement;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Storage;

class EmailManagementControler extends Controller
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
            ->header('郵件樣版')
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
        $grid = new Grid(new EmailManagement);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('type', '類別', [1 => '給會員', 2 => '給管理者']);
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });

        $grid->type('類別')->using([1 => '給會員', 2 => '給管理者']);
        $grid->template_name('樣版名稱');
        $grid->subject('主旨');
        $grid->body('內容')->display(function($body){
            return nl2br($body);
        })->width(600);
        $grid->sms('簡訊內容')->display(function($sms){
            return nl2br($sms);
        })->width(400);
        $grid->locale('語系')->using(['en' => 'En', 'zh-TW' => 'zh-TW']);

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });
        $grid->filter(function($filter){

            $filter->disableIdFilter();

            $filter->like('subject', '主旨');
            $filter->like('template_name', '樣版名稱');

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
        $form = new Form(new EmailManagement);

        $form->hidden('id');
        $form->radio('type', 'type')->options([1 => '給會員', 2 => '給管理者'])->required()->when(2, function(Form $form){

            $form->text('manager_mail', '管理者Email');
        });
        $form->text('template_name', '樣版名稱')->required();
        $form->text('subject', '主旨')->required();
        $form->textarea('body', '內容')->required();
        $form->textarea('sms', '簡訊內容');
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();

        // $form->saving(function ($form) {
        //     print_r($form);die;
        //         // $form->logo = return json_encode($paths);

        // });

        return $form;
    }
}
