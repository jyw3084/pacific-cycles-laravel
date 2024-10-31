<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\EventSignUp;
use App\Models\BonusLog;
use App\Models\Vouchers;
use App\Models\Promos;
use App\Models\EmailManagement;
use Mail;
use App\Mail\OrderComplete;
use Gloudemans\Shoppingcart\Facades\Cart;
use Session;

require_once base_path('ctbc_bank/POSAPI.php');

class BankController extends Controller
{

    public function pay(Request $request)
    {
        $order = Order::where('number', $request->order_number)->first();
        if(!empty($request->event_id))
            $order = EventSignUp::find($request->event_id);

        $debug = \Config::get('app.debug');
        $cafile = base_path('ctbc_bank/server.cer');
        $pos_url = $debug ? \Config::get('bank.develoption.url') : \Config::get('bank.production.url');
        $strMerID = $debug ? \Config::get('bank.develoption.MerID') : \Config::get('bank.production.MerID');
        $strMerchantID  = $debug ? \Config::get('bank.develoption.MerchantID') : \Config::get('bank.production.MerchantID');
        $strTerminalID  = $debug ? \Config::get('bank.develoption.TerminalID') : \Config::get('bank.production.TerminalID');
        $strMacKey  = $debug ? \Config::get('bank.develoption.Key') : \Config::get('bank.production.Key');
        
        $Server = array(
            'URL'    => $pos_url ,//UAT
            'MacKey' => $strMacKey ,//必要值
            'Timeout' => 30,
        );

        $Auth = array(
            'MERID' => $strMerID,
            'LID-M' => $order->number, //訂單編號，資料 型態為最長 19 個字元的文字串
            'PAN' => (string)($request->cardnumber), //為信用卡號及卡片背面末三碼值(CVV2/CVC2)，最大長度 19 位數字。
            'ExpDate' => (string)($request->expiry), //效期格式為YYYYMM
            'currency' => '901', //交易幣值代號。長度為 3 個字元的字串。(如台幣”901”)
            'purchAmt' => $order->total,
            'exponent' => '0', //為幣值指數，新台幣為 0。(如美金 1.23 元，purchAmt 給 123 而 amtExp 則給-2)
            'ECI' => $request->eci,
            'CAVV' => $request->cavv,
        );
        $Result = AuthTransac($Server,$Auth); 
        if(!empty($Result['ErrCode']) && $Result['ErrCode'] == '00')
        {
            if($order->point > 0)
            {
				$point = $order->point;
				if($order->currency != 'TWD')
					$point = $order->point * 30;
                
                $log = new BonusLog;
                $log->user_id = $order->user->id;
                $log->point = $point;
                $log->order_id = $order->id;
                $log->use_date = date('Y-m-d H:i:s');
                $log->save();
            }
    
            if($order->voucher_id)
            {
                $rate = $order->find($order->voucher_id);
                $coupon->status = 1;
                $coupon->save();
            }
            $order->paid = 1;
			$order->paid_at = date('Y-m-d H:i:s');
            $order->save();

            if(!empty($request->event_id))
                return redirect('news-events')->with(['code' => 200,'message' => trans('frontend.news.success')]);
            else
            {
                $user = $order->user;
    
                $lang = $user->locale;
                $details = $order->order_detail;
    
                $mail_data = EmailManagement::where([['type', 1],['template_name', 'order_placed'], ['locale', $lang]])->orWhere([['type', 2],['template_name', 'order_placed']])->get();
                foreach($mail_data as $k => $v)
                {
                    $email_str = array(
                        '{Name}' => $order->user->name,
                        '{Title}' => $v->subject,
                        '{FormNo}' => $order->number,
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
                            $email = $order->user->email;
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
    
                session()->forget('_order');
                Cart::destroy();

                return redirect('shopping/order-complete/'.$order->number)->with(['code' => 200,'message' => trans('frontend.store.success')]);
                
            }
        }
    }
}
