<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Models\Packages;
use App\Models\Products;
use App\Models\WarrantyExtension;
use App\Models\Event;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\Currency;
use App\Models\Note;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Table;
use Mail;
use App\Models\EmailManagement;
use App\Mail\SendEmail;
use App\Mail\OrderComplete;
use Storage;

class OrderController extends Controller
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
            ->header('訂單')
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
        $grid = new Grid(new Order);

        $grid->model()->where('paid', 1);
        $grid->created_at('訂單日期')->display(function($date){
            return date('Y-m-d', strtotime($date));
        });
        $grid->column('number', '訂單編號');
        $grid->total('金額');
        $grid->user_id('訂購者')->display(function($user_id){
            $user = User::find($user_id);
            return $user->MemberID.' / '.$user->name;
        });
        $grid->ship_type('交付方式')->display(function($type){
            switch($type)
            {
                case 1:
                    return '宅配';
                    break;
                case 2:
                    return '店取';
                    break;
            }
        });
        $grid->status('訂單狀態')->display( function($status){
            switch($status)
            {
                case 0:
                    return "<span class='text-warning'>訂單成立</span>";
                    break;
                case 1:
                    return "<span class='text-primary'>訂單確認</span>";
                    break;
                case 2:
                    return "<span class='text-success'>已出貨</span>";
                    break;
                case 3:
                    return "<span class='text-danger'>管理員取消</span>";
                    break;
                case 4:
                    return "<span class='text-danger'>客戶取消</span>";
                    break;
            }
        });
        $grid->paid('付款狀態')->display( function($paid){
            switch($paid)
            {
                case 0:
                    return "<span class='text-danger'>未付款</span>";
                    break;
                case 1:
                    return "<span class='text-success'>已付款</span>";
                    break;
            }
        });
        $grid->id('出貨狀態')->display( function($id){
            $order = Order::find($id);
            switch($order->ship_sataus)
            {
                case 0:
                    return '未出貨 '. $order->order_detail->sum('delivery_status').'/'.$order->order_detail->count();
                    break;
                case 1:
                    return '已出貨'. $order->order_detail->sum('delivery_status').'/'.$order->order_detail->count();
                    break;
            }

        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });
        $grid = $this->sortGridByDate($grid);
        $grid->disableCreateButton();
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
        $builder = Order::with('message');
        Admin::style('
        .box.box-solid.box-default {
            border: 0px
        }
        .box {box-shadow: 0 0px 0px;}
        .response {width: 93% !important}
        ');

        Admin::script('
        $(\'.btn-primary\').remove();
        var status = $(\'input[name="status"]\').val();
        if(status == "0")
        {
            $(\'.layui-layer-btn0\').text(\'訂單確認\');
            $(\'input[name="status"]\').val(1);
        }
        $(\'.remove\').remove();
        $(\'.btn-danger\').remove();
        $(\'.field_response\').each(function(i){
            if($(this).val() != "")
            {
                var response = $(this).val();
                $(this).parent(\'.input-group\').html(response);
            }
            else
            {
                $(this).parent(\'.input-group\').append(\' <button type="submit" class="btn btn-danger pull-right">回應</button>\');
            }
        });
        ');

        return Form::make($builder, function (Form $form) {

            $form->hidden('id');
            $form->display('number', '訂單編號');
            $form->display('user_id', '訂購者')->with(function($id){
                $user = User::find($id);
                return $user->MemberID.' / '.$user->name;
            });
            $form->display('created_at', '訂單日期')->with(function($date){
                return date('Y-m-d', strtotime($date));
            });
            $form->display('total', '總金額');
            $form->display('ship_type', '交付方式')->with(function($type){
                switch($type)
                {
                    case 1:
                        return '宅配';
                        break;
                    case 2:
                        return '店取';
                        break;
                }
            });
            $form->display('status', '訂單狀態')->with(function($status){
                switch($status)
                {
                    case 0:
                        return "<span class='text-warning'>訂單成立</span>";
                        break;
                    case 1:
                        return "<span class='text-primary'>訂單確認</span>";
                        break;
                    case 2:
                        return "<span class='text-success'>已出貨</span>";
                        break;
                    case 3:
                        return "<span class='text-danger'>管理員取消</span>";
                        break;
                    case 4:
                        return "<span class='text-danger'>客戶取消</span>";
                        break;
                }
            });
            $form->display('paid', '付款狀態')->with( function($paid){
                switch($paid)
                {
                    case 0:
                        return "<span class='text-danger'>未付款</span>";
                        break;
                    case 1:
                        return "<span class='text-success'>已付款</span>";
                        break;
                }
            });
            $form->display('paid_at', '付款時間');
            $form->text('name', '收件人');
            $form->email('email', 'email');
            $form->mobile('phone', '手機')->options(['mask' => '0987 654 321']);
            $form->text('address', '地址');

            $form->select('invoice_type', '發票類型')->options([2 => '二聯式', 3 => '三聯式']);
            $form->text('invoice_title', '發票抬頭');
            $form->text('invoice_serial', '統編');

            $form->html(function(Form $form){
                $id = $form->model()->id;

                $headers = ['Id', '出貨日期', '出貨單號', '出貨狀態', '商品', '價格', '數量', '小計'];
                $details = OrderDetail::where('order_id', $id)->get();
                $rows = [];
                foreach($details as $k => $v)
                {
                    switch($v->product_type)
                    {
                        case 1:
                            $currency = isset($v->package->currency) ? Currency::find($v->package->currency)->code : '';
                            $product_name = '<img src="'.Storage::url($v->package->images[0]).'" style="width:120px;"> '.$v->package->name;
                            break;
                        case 2:
                            $currency = isset($v->package->currency) ? Currency::find($v->product->currency)->code : '';
                            $product_name = '<img src="'.Storage::url($v->product->images[0]).'" style="width:120px;"> '.$v->product->product_name;
                            break;
                    }
                    $status = '';
                    if($v->product_type < 3)
                    {
                        switch($v->delivery_status)
                        {
                            case 0:
                                $status = '未出貨';
                                break;
                            case 1:
                                $status = '已出貨';
                                break;
                        }
                    }
                    $rows[] = array(
                        $k+1,
                        $v->delivery_date,
                        $v->ship_number,
                        $status,
                        $product_name,
                        $currency.' '.$v->price,
                        $v->quantity,
                        $currency.' '.number_format($v->quantity * $v->price),
                    );
                }

                $table = new Table($headers, $rows);
                return $table->render();
            }, '訂單明細');

            $form->html(function(Form $form){
                $id = $form->model()->id;

                $headers = ['記錄', '備註', '日期'];
                $order = Order::find($id);
                $rows = [];
                for($i= 0; $i <= $order->status; $i++)
                {
                    switch($i)
                    {
                        case 0:
                            $rows[] = array(
                                '訂單成立',
                                '',
                                $order->paid_at,
                            );
                            break;
                        case 1:
                            $rows[] = array(
                                '訂單確認',
                                '',
                                $order->confirmed_at,
                            );
                            break;
                    }
                }
                $table = new Table($headers, $rows);
                return $table->render();
            }, '歷史記錄');


            $form->textarea('remark', '備註');

            $form->hasMany('message','訂單詢問', function (Form\NestedForm $form) {
                $form->display('message','訊息');
                $form->text('response','回覆');
            });

            $form->hidden('confirmed_at');
            $form->hidden('status');
            $form->hidden('user_id');
            $form->hidden('number');

            $form->saving(function ($form) {
                if($form->status == 3)
                {
                    $id = $form->model()->id;
                    $details = OrderDetail::where('order_id', $id)->update(['delivery_status', 2]);
                }


                $user = User::find($form->user_id);
                if($form->message)
                {
                    foreach($form->message as $message)
                    {
                        if(!empty($message['response']))
                        {
                            $note = Note::find($message['id']);
                            $note->response = $message['response'];
                            $note->save();

                            $mail_data = EmailManagement::where([['type', 1],['template_name', 'order_inquiry_reply'], ['locale', $user->locale]])->orWhere([['type', 2],['template_name', 'order_inquiry_reply']])->get();
                            foreach($mail_data as $k => $v)
                            {
                                $email_str = array(
                                    '{Name}' => $user->name,
                                    '{Title}' => $v->subject,
                                    '{FormNo}' => $form->number,
                                    '{Month}' => date('m'),
                                );
                                $body = nl2br(strtr($v->body, $email_str));
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
                    }
                }

            });
        });
    }
}
