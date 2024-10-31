<?php

namespace App\Admin\Controllers;

use App\Models\ContactForm;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class ContactFormController extends Controller
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
            ->header('聯絡我們設定')
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
        $grid = new Grid(new ContactForm);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });
        $grid->key('鍵值');
        $grid->filed('欄位');
        $grid->category('類型')->using([1 => 'input', 2 => 'textarea', 3 => 'select']);
        $grid->type('樣式')->using([1 => 'text', 2 => 'email', 3 => 'number', 4 => 'radio', 5 => 'checkbox']);
        $grid->options('項目');
        $grid->placeholder('佔位符');
        $grid->required('必填')->switch();
        $grid->locale('語系')->using(['en' => 'En', 'zh-TW' => 'zh-TW']);

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
        $form = new Form(new ContactForm);

        $form->text('key', '鍵值(只能輸入英文)')->required();
        $form->text('filed', '欄位')->required();
        $form->select('category', '類型')->options([1 => 'input', 2 => 'textarea', 3 => 'select'])->required()
        ->when(1, function (Form $form) { 

            $form->select('type', '樣式')->options([1 => 'text', 2 => 'email', 3 => 'number', 4 => 'radio', 5 => 'checkbox', 6 => 'date']);
    
        })
        ->when(3, function (Form $form) { 

            $form->tags('options', '項目');
    
        });
        $form->text('placeholder', '佔位符');
        $form->switch('required', '必填');
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();

        return $form;
    }
}
