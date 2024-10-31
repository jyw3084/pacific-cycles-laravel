<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
Use Dcat\Admin\Admin;

class UserController extends Controller
{
    public $id;
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
            ->header('會員')
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
            ->header('Details')
            ->description('Customer')
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
        $this->id = $id;
        return $content
            ->header('Edit')
            ->description('Customer')
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
        $this->id = '';
        return $content
            ->header('Add')
            ->description('Customer')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);
        $grid->quickSearch('name');
        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });
        $grid->created_at('註冊日期')->display(function(){
            return date('Y-m-d', strtotime($this->created_at));
        });
        $grid->auth('註冊方式')->display(function($auth){
            if($auth)
                return $auth;

            return empty($this->email) ? '手機註冊': 'Email註冊';
        });
        $grid->name('姓名')->sortable();
        $grid->MemberID('會員編號')->sortable();
        $grid->column('email')->sortable();
        $grid->phone_number('手機')->sortable();
        $grid->active('狀態')->switch();
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
        $form = new Form(new User);

        $form->text('name', '姓名');
        $form->email('email');
        $form->mobile('phone_number', '手機')->options(['mask' => '0987 654 321']);

        //$form->switch('subscription', 'Subscribe to News Letter');

        $form->switch('active', '狀態');
        $form->hidden('user_type');
        $form->saving(function ($form) {
            $form->user_type = 2;
            // print_r($form->bikes);die;
            if ($form->password && $form->model()->get('password') != $form->password) {
                $form->password = bcrypt($form->password);
            }

        });

        return $form;
    }
}
