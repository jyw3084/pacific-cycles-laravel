<?php

namespace App\Admin\Controllers;

use App\Models\Vouchers;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use App\Models\Products;
use App\Models\User;
use App\Models\Category;
use App\Models\BikeModel;
use Mail;
use App\Models\EmailManagement;
use App\Mail\SendEmail;
use App\Mail\OrderComplete;

class VoucherController extends Controller
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
            ->header('折價券')
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
        $grid = new Grid(new Vouchers);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('status', '狀態', [
                1 => '已使用',
                0 => '未使用'
            ]);
        });
        $grid->created_at('建立日期')->display(function(){
            return date('Y-m-d', strtotime($this->created_at));
        });
        $grid->user('使用者')->display(function(){
            return $this->user->email ?? '';
        });
        $grid->name('折價券名稱');
        $grid->category_id('類別')->display(function($category_id) {
            $category = Category::find($category_id);
            return $category->name ?? '';
        });
        $grid->model_id('車型')->display(function($model_id) {
            $model = BikeModel::find($model_id);
            return $model->bike_model ?? '';
        });
        $grid->product_id('車輛')->display(function($product_id) {
            $product = Products::find($product_id);
            return $product->product_name ?? '';
        });
        $grid->status('使用')->switch();
        $grid->enable('啟用')->switch();
        $grid->rate('折扣%');
        $grid->currency('幣別');
        $grid->start('開始日期');
        $grid->end('結束日期');

        $grid->showFilterButton();
        $grid->filter(function($filter){
            $filter->panel();

            $filter->like('user', '使用者');
            $select = Vouchers::groupBy('name')->pluck('name', 'name');
            $filter->equal('name', '折價券名稱')->select($select);

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
        $form = new Form(new Vouchers);

        if (basename(request()->path()) == 'edit') {
            $form->select('user_id', '使用者')->options(User::all()->pluck('email', 'id'))->disable();
        }
        else {
            //$form->listbox('user_id', '使用者')->options(User::all()->pluck('email', 'id'))->required();
            $userList = array_merge(['all' => '所有使用者'], User::all()->pluck('email', 'id')->toArray());
            $form->multipleSelect('user_id', '使用者')->options($userList);
            $form->checkbox('all', '')->options([1 => '所有使用者'])->script('$(this).on("change", function(){ if($(".field_all").prop("checked")){ $(".field_user_id").prop("disabled", true); } else { $(".field_user_id").prop("disabled", false) }});');
        }
        $form->text('name', '折價券名稱')->required();
        $form->rate('rate', '折扣%')->required();
        $form->select('currency', '幣別')->options(['TWD' => 'TWD', 'USD' => 'USD'])->required();
        $form->select('category_id', '類別')->options(Category::all()->pluck('name', 'id'))->load('model_id', '/api/bike_model')->required();
        $form->select('model_id', '車型')->options(BikeModel::all()->pluck('bike_model', 'id'))->load('product_id', '/api/product');
        $form->select('product_id', '車輛')->options(Products::all()->pluck('product_name', 'id'));
        $form->date('start', '開始日期')->format('YYYY-MM-DD')->required();
        $form->date('end', '結束日期')->format('YYYY-MM-DD')->required();
        //if (basename(request()->path()) == 'edit') {
            $form->switch('status', '使用')->default(0);
        //}
        $form->switch('enable', '啟用')->default(1);

        $form->saving(function (Form $form) {
            if($form->all[0]!=null){
                $users_id = User::all()->pluck('id');
            } else {
                $users_id = [$form->user_id];
            }
            unset($form->all);
            if(!$form->model()->id && $users_id)
            {
                $data = $form;
                foreach($users_id as $id)
                {
                    if($id)
                    {
                        $coupon = new Vouchers;
                        $coupon->name = $data->name;
                        $coupon->user_id = $id;
                        $coupon->currency = $data->currency;
                        $coupon->rate = $data->rate;
                        $coupon->category_id = $data->category_id;
                        $coupon->model_id = $data->model_id;
                        $coupon->product_id = $data->product_id;
                        $coupon->start = $data->start;
                        $coupon->end = $data->end;
                        $coupon->status = $data->status;
                        $coupon->enable = 1;
                        $coupon->save();
                    }
                }

                // todo: re-enable email at appropriate time
                $users = USER::whereIn('id', $users_id)->get();
                /* if($users)
                {
                    foreach($users as $user)
                    {
                        $mail_data = EmailManagement::where([['type', 1],['template_name', 'vouchers'], ['locale', $user->locale]])->orWhere([['type', 2],['template_name', 'vouchers']])->get();
                        foreach($mail_data as $k => $v)
                        {
                            $email_str = array(
                                '{Name}' => $user->name,
                                '{Title}' => $v->subject,
                                '{Month}' => date('m'),
                            );
                            $body = !empty($v->body) ? nl2br(strtr($v->body, $email_str)) : '' ;
                            $data = array(
                                'subject'=> $v->subject,
                                'body' => $body,
                            );

                            switch($v->type)
                            {
                                case 1:
                                    $email = $user->email;
                                    Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
                                    break;
                                case 2:
                                    $emails = explode(',',$v->manager_mail);
                                    foreach($emails as $email)
                                    {
                                        Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
                                    }
                                    break;
                            }
                        }
                    }
                } */
            }

            return $form->response()->success('新增成功')->refresh();
        });

        return $form;
    }
}
