<?php

namespace App\Admin\Controllers;

use App\Models\Bonus;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use App\Models\User;
use http\Env\Request;

class BonusController extends Controller
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
            ->header('會員點數')
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
        $grid = new Grid(new Bonus);

        $grid->user_id('使用者')->display(function($ids){
            if(count($ids) > 10){
               $string = "+" . count($ids);
            }
            $email = '';
            $c = 0;
            foreach($ids as $id)
            {
                $c++;
                $user = User::find($id);
                $email .= isset($user) ? $user->email : '';
                $email .= '<br>';
                if($c==10){
                    $email .= $string ?? "";
                    break;
                }
            }
            return $email;
        });
        $grid->amount('點數');
        $grid->days('天數');
        $grid->expiration_date('使用期限');
        $grid = $this->sortGridByDate($grid);
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
        $form = new Form(new Bonus);

        $form->multipleSelect('user_id', '使用者')->options(User::all()->pluck('email', 'id'));
        $form->checkbox(null, '')->options([1 => '所有使用者'])->script('$(this).on("change", function(){ if($(".field_all").prop("checked")){ $(".field_user_id").prop("disabled", true); } else { $(".field_user_id").prop("disabled", false) }});')->setElementName('all[]');
        //$form->select('currency', 'currency')->options(['TWD' => 'TWD', 'USD' => 'USD'])->required();
        $form->text('amount', '點數')->required();
        $form->text('days', '天數')->required();
        $form->hidden('expiration_date');
        $form->saving(function ($form) {

            $form->expiration_date = date('Y-m-d', strtotime($form->created_at ." +".$form->days." days"));

            if($form->all[0]!=null){
                $users_id = User::all()->pluck('id')->toArray();
            } else {
                $users_id = $form->user_id;
            }
            if($users_id==null || empty($users_id) || (count($users_id)==1 && $users_id[0]==null)){
                throw new \Exception('Users cant be empty');
            }
            $form->user_id = $users_id;

        });

        return $form;
    }
}
