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
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Table;
use Storage;

class ShipmentController extends Controller
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
            ->header('出貨')
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
        $grid = $this->sortGridByDate($grid);
        $grid->disableFilter();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
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
        $builder = Order::with('order_detail');

        Admin::style('.box.box-solid.box-default {
            border: 0px
        }
        .box {box-shadow: 0 0px 0px;}');

        Admin::script('
        $(\'.add\').remove();
        $(\'.remove\').remove();
        ');

        return Form::make($builder, function (Form $form) {

            $form->hidden('id');
            $form->display('number', '訂單編號');
            $form->display('name', '收件人');
            $form->display('email', 'email');
            $form->display('phone', '手機')->options(['mask' => '0987 654 321']);
            $form->display('address', '地址');
            $form->radio('status', '訂單狀態')->options([ 2=>'已出貨', 3 => '已取消'])->required();

            $form->hasMany('order_detail', '訂單明細', function (Form\NestedForm $form) {

                $form->select('ship_vendor', '物流')->options(
                    [
                        1 => '國際快捷-EMS',
                        2 => '黑貓宅急便',
                        3 => '大榮貨運',
                        4 => '中華郵政',
                        5 => 'Fedex',
                        6 => 'TNT',
                        7 => '順豐',
                        8 => 'DHL',
                        9 => '自送/自取',
                    ]
                );
                $form->text('ship_number', '出貨單號');
                $form->display('delivery_date', '出貨日期');
                $form->hidden('delivery_date')->default(date('Y-m-d H:i:s'));
                $form->display('id', '商品')->with(function ($id) {
                    if($id)
                    {
                        $details = OrderDetail::find($id);
                        switch($details->product_type)
                        {
                            case 1:
                                return '<img src="'.Storage::url($details->package->images[0]).'" style="width:120px;"> '.$details->package->name;
                                break;
                            case 2:
                                return '<img src="'.Storage::url($details->product->images[0]).'" style="width:120px;"> '.$details->product->product_name;
                                break;
                        }
                    }
                });
                $form->display('quantity', '數量');
            });

            $form->textarea('remark', '備註');

            $form->saved(function ($form) {
                $id = $form->model()->id;

                $order = Order::find($id);

                if($form->status != 3)
                {
                    $order->ship_sataus = 1;
                    $order->save();

                    $details = OrderDetail::where('order_id', $id)->get();
                    foreach($details as $k => $v)
                    {
                        if($v->ship_vendor)
                        {
                            $v->delivery_status = 1;
                            $v->delivery_date = date('Y-m-d H:i:s');
                            $v->save();

                        }
                    }
                }
                else
                {
                    $order->ship_sataus = 0;
                    $order->save();

                    $details = OrderDetail::where('order_id', $id)->get();
                    foreach($details as $k => $v)
                    {
                        if($v->ship_vendor)
                        {
                            $v->delivery_status = 0;
                            $v->delivery_date = null;
                            $v->save();

                        }
                    }

                }
            });
        });
    }
}
