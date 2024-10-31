<?php

namespace App\Admin\Controllers;

use App\Models\Contact;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use App\Models\ContactForm;

class ContactController extends Controller
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
            ->header('聯絡我們')
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
        $grid = new Grid(new Contact);

        $grid->model()->orderBy('id', 'desc');
        $grid->created_at('提交日期')->display(function(){
            return date('Y-m-d', strtotime($this->created_at));
        });
        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disableRowSelector();

        $fields = ContactForm::where('locale', 'zh-TW')->get();
        foreach($fields as $field)
        {
            $grid->column($field->filed)->display(function() use($field){
                $comments = json_decode($this->content, true);
                unset($comments['_token']);
                return $comments[$field->key];
            });
        }

        $grid->disableActions();
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
        $form = new Form(new Contact);

        $form->text('name', 'name');
        $form->text('email', 'email');
        $form->text('mobile_number', 'mobile_number');
        $form->text('subject', 'subject');
        $form->text('content', 'content');

        return $form;
    }
}
