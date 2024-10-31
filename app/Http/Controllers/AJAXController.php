<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\APIController;
use App\Models\EmailManagement;
use App\Mail\SendEmail;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EventSignUp;
use App\Models\Event;
use App\Models\Order;
use App\Models\Packages;
use App\Models\Promos;
use App\Models\Vouchers;
use App\Models\Bonus;
use App\Models\BonusLog;
use App\Models\Contact;
use App\Models\Dealer;
use App\Models\Setting;
use Mail;
use App\Mail\OrderComplete;
use Gloudemans\Shoppingcart\Facades\Cart;
use Session;

require_once base_path('ctbc_bank/auth_mpi_mac.php');
class AJAXController extends Controller
{

    public function ajax(Request $request)
    {
        $lang = app()->getLocale();
    	$type = $request['type'];
    	switch ($type) {
            case 'registerEmail':
				$data = (object)array(
					'name' =>  $request['name'],
					'email' => $request['email'],
					'Birthday' => $request['Birthday'],
					'Address' => $request['Address'],
					'password' => $request['password'],
					'AccountType' => 1,
					'phone' => '',

				);
				$response = APIController::register($data);
				$data = json_decode($response);
				if(!empty($data->Entries))
				{
					$user = new User;
					$user->name = $request['name'];
					$user->email = $request['email'];
					$user->Birthday = $request['Birthday'];
					$user->Address = $request['Address'];
					$user->password = bcrypt($request['password']);
					$user->is_email_phone = 1;
					$user->active = 1;
					$user->user_type = 2;
					$user->locale = $lang;
					$user->save();
					$response = APIController::getMemberData($user);
					$data = json_decode($response);
					$this->save_data($user, $data);

					$regist_trigger = Setting::where('key', 'regist_trigger')->first()->value;
					if($regist_trigger)
					{
						$amount = Setting::where('key', 'regist_credits_amount')->first()->value;
						$days = Setting::where('key', 'regist_credits_expiration')->first()->value;
	
						$bonus = new Bonus;
						$bonus->user_id = [(string)$user->id];
						$bonus->amount = $amount;
						$bonus->days = $days;
						$bonus->expiration_date = date('Y-m-d', strtotime('+'.$days.' days'));
						$bonus->created_at = date('Y-m-d H:i:s');
						$bonus->save();
					}
					return 'success';
				}

				return 'error';
            break;

            case 'sendCode':
		        $phoneNumber = $request['phone_number'];
				
				$randomCode = str_pad(mt_rand(0, 999999),6,'0',STR_PAD_BOTH);
				Session::put('captcha', $randomCode);
				
        		$message = 'Your Code is '.$randomCode;

				APIController::sendSms($phoneNumber, $message);
		        return $randomCode;
            break;

            case 'registerPhone':
				$data = (object)array(
					'name' =>  $request['name'],
					'email' => '',
					'Birthday' => $request['Birthday'],
					'Address' => $request['Address'],
					'password' => $request['password'],
					'AccountType' => 0,
					'phone' => (string)$request['phone_number'],

				);
				$response = APIController::register($data);
				$data = json_decode($response);
				if(empty($data->StatusCode))
				{
					$user = new User;
					$user->name = $request['name'];
					$user->phone_number = $request['phone_number'];
					$user->Birthday = $request['Birthday'];
					$user->Address = $request['Address'];
					$user->password = bcrypt($request['password']);
					$user->is_email_phone = 2;
					$user->active = 1;
					$user->user_type = 2;
					$user->locale = $lang;
					$user->save();
					$response = APIController::getMemberData($user);
					$data = json_decode($response);
					$this->save_data($user, $data);

					$regist_trigger = Setting::where('key', 'regist_trigger')->first()->value;
					if($regist_trigger)
					{
						$amount = Setting::where('key', 'regist_credits_amount')->first()->value;
						$days = Setting::where('key', 'regist_credits_expiration')->first()->value;
	
						$bonus = new Bonus;
						$bonus->user_id = [(string)$user->id];
						$bonus->amount = $amount;
						$bonus->days = $days;
						$bonus->expiration_date = date('Y-m-d', strtotime('+'.$days.' days'));
						$bonus->created_at = date('Y-m-d H:i:s');
						$bonus->save();
					}
					return 'success';
				}
				return 'error';
            break;
            case 'logout':
	            Auth::logout();
        		return redirect('/');
            break;

            case 'forgotPassword':
				$data = (object)array(
					'account' =>  (string)$request['username'],
				);
				$response = APIController::forgotPassword($data);
				if(!empty(json_decode($response)->Entries->MemberId))
				{
					$data = json_decode($response)->Entries;
					$password = $data->ResetPwd;
					$email = $data->Email;
					if(!empty($email))
					{
						$data = array(
							'subject'=> trans('frontend.dashboard.forgot'),
							'body' => trans('frontend.dashboard.ur_new_pass').$password,
						);
						Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
					}
					else
					{
						$phoneNumber = $request['username'];
						$message = trans('frontend.dashboard.ur_new_pass').$password;
						APIController::sendSms($phoneNumber, $message);
					}
					return 'success';
				}
            break;
		}
	}

	public function login(Request $request)
	{
		$data = (object)array(
			'username' =>  (string)$request['username'],
			'password' =>  $request['password'],
		);
		$response = APIController::login($data);
		if(empty(json_decode($response)->StatusCode))
		{
			$data = json_decode($response)->Entries;
			$member_id = $data->MemberID;
			$user = USER::where('MemberID', $member_id)->first();
			if($user)
			{
				Auth::login($user);
				return redirect('sync/bikes');
			}
			else
			{
				$lang = app()->getLocale();
				$user = new User;
				$user->name = $data->MemberName;
				$user->email = $data->Email;
				$user->phone_number = '0'.(string)$data->Phone;
				$user->Phone = (string)$data->Phone;
				$user->Birthday = $data->Birthday;
				$user->Address = $data->Address;
				$user->is_email_phone = empty($data->Email) ? 2 : 1;
				$user->user_type = 2;
				$user->active = 1;
				$user->locale = $lang;
				$user->MemberID = $data->MemberID;
				$user->save();
				$response = APIController::getMemberData($user);
				$data = json_decode($response);
				$this->save_data($user, $data);
				Auth::login($user);
				return redirect('sync/bikes');
			}
		}
		else
			return redirect()->back()->with(['code' => 0]);
	}

	public function logout()
	{
		Auth::logout();
		return redirect('/');
	}

    public function signup(Request $request)
    {
        $event = Event::find($request->event_id);
		$fields = $event->fields;

		$validate = [];
		foreach($fields as $field)
		{
			if(!empty($field['required']))
			{
				$ext = $field['type'] == 'email' ? '|email': '';
				$validate[$field['key']] = 'required'.$ext;
			}
		}

        $request->validate($validate);
        $user = auth()->user();
        $sign = EventSignUp::where([['user_id', $user->id], ['event_id', $request->event_id]])->first();
        if(!$sign)
        {
			$data = $request->input();
			unset($data['card_number']);
			unset($data['cvc']);
			unset($data['holder-name']);
			unset($data['month']);
			unset($data['year']);
            $signup = new EventSignUp;
            $signup->number = date('YmdHis').str_pad(random_int(1, 9999),5,"0",STR_PAD_LEFT);
            $signup->event_id = $request->event_id;
            $signup->user_id = $user->id;
            $signup->name = $user->name;
            $signup->email = $user->email;
            $signup->payment = $request->payment;
            $signup->content = json_encode($data);
            $signup->total = $event->price;
            if($event->price == 0)
            {
                $signup->paid = 1;
                $signup->paid_at = date('Y-m-d H:i:s');
                $signup->save();
    
				$lang = $user->locale;
				$mail_data = EmailManagement::where([['type', 1],['template_name', 'event_registration_success'], ['locale', $lang]])->orWhere([['type', 2],['template_name', 'event_registration_success']])->get();

				foreach($mail_data as $k => $v)
				{
					$email_str = array(
						'{Name}' => $request->name,
						'{Title}' => $v->subject,
						'{Month}' => date('m'),
						'{event name}' => $event->title,
						'{活動名稱}' => $event->title,
					);
					$body = nl2br(strtr($v->body, $email_str));
					$data = array(
						'subject'=> $v->subject,
						'body' => $body,
					);
	
					switch($v->type)
					{
						case 1:
							$email = $request->email;
							Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
							break;
						case 2:
							$emails = explode(',',trim($v->manager_mail));
							foreach($emails as $email)
							{
								Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
							}
							break;
					}
				}
				return redirect()->back()->with(['code' => 200,'message' => trans('frontend.news.success')]);
            }
            $signup->save();
        }
        else if($sign->paid == 1)
			return redirect()->back()->with(['message' => trans('frontend.news.has_signup')]);
			
		if($request->payment == '線上刷卡')
		{
			$request->id = $sign->id;
			$this->pay($request);
		}
		else
			return redirect()->back()->with(['message' => trans('frontend.news.please_paid')]);
    }

    public function warranty_extension(Request $request)
    {
		$data = (object)array(
			'ProductNo' =>  $request['ProductNo'],
			'year' => (int)$request['BuyWarrantyYear'],
		);
		$response = APIController::buyProductWarrant($data);
		$data = json_decode($response);
			
		return response()->json([
			'code' => 200,
			'message' => trans('frontend.dashboard.BuyWarrantySuccess'),
		]);
    }

    public function extend(Request $request)
    {
        $event = Event::find($request->event_id);
		$fields = $event->fields;

		$validate = [];
		foreach($fields as $field)
		{
			if(!empty($field['required']))
			{
				$ext = $field['type'] == 'email' ? '|email': '';
				$validate[$field['key']] = 'required'.$ext;
			}
		}

        $request->validate($validate);
        $user = auth()->user();
        $sign = EventSignUp::where([['user_id', $user->id], ['event_id', $request->event_id]])->first();
        if(!$sign)
        {
			
			$request->id = $sign->id;
			$this->pay($request);
        }
    }

    public function event_pay(Request $request)
    {
        $user = auth()->user();
        $event = Event::find($request->event_id);
        $signup = EventSignUp::where([['user_id', $user->id], ['event_id', $request->event_id]])->first();
        if($signup)
        {
			$signup->paid = 1;
			$signup->paid_at = date('Y-m-d H:i:s');
			$signup->save();

			$lang = $user->locale;
			$mail_data = EmailManagement::where([['type', 1],['template_name', 'event_registration_success'], ['locale', $lang]])->orWhere([['type', 2],['template_name', 'event_registration_success']])->get();

			$user = json_decode($signup->content);

			foreach($mail_data as $k => $v)
			{
				$email_str = array(
					'{Name}' => $user->name,
					'{Title}' => $v->subject,
					'{Month}' => date('m'),
					'{event name}' => $event->title,
					'{活動名稱}' => $event->title,
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
						$emails = explode(',',trim($v->manager_mail));
						foreach($emails as $email)
						{
							Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
						}
						break;
				}
			}
			
			return response()->json([
				'code' => 200,
				'message' => trans('frontend.news.success'),
			]);
        }
    }

    public function order_pay(Request $request)
    {
        $user = auth()->user();
        $order = Order::find($request->order_id);
        if($order)
        {
			$order->paid = 1;
			$order->paid_at = date('Y-m-d H:i:s');
			$order->save();

            if($order->voucher_id)
            {
                $coupon = Vouchers::find($order->voucher_id);
                $coupon->status = 1;
                $coupon->save();
            }
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
                        $emails = explode(',',trim($v->manager_mail));
                        foreach($emails as $email)
                        {
							Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
                        }
                        break;
                }
            }

            session()->forget('_order');
			Cart::destroy();
			
			return response()->json([
				'code' => 200,
				'title' => trans('frontend.dashboard.success').'!',
				'message' => trans('frontend.store.success'),
			]);
        }
    }

    public function contact(Request $request)
    {
        $contact = new Contact;
		$contact->content = json_encode($request->input());
		$contact->created_at = date('Y-m-d H:i:s');
        if($contact->save())
        {
			$lang = app()->getLocale();
			$mail_data = EmailManagement::where([['type', 1],['template_name', 'contact'], ['locale', $lang]])->orWhere([['type', 2],['template_name', 'contact']])->get();
            foreach($mail_data as $k => $v)
            {
                $email_str = array(
                    '{Name}' => $request->name,
                    '{Title}' => $request->subject,
                    '{Phone}' => $request->phone,
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
                        $email = $request->email;
						Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
                        break;
                    case 2:
                        $emails = explode(',',trim($v->manager_mail));
                        foreach($emails as $email)
                        {
							Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
                        }
                        break;
                }
            }

			return redirect()->back()->with([
				'code' => 200,
				'message' => trans('frontend.dealer.success'),
			]);
        }
    }

    public function apply(Request $request)
    {
        $dealer = new Dealer;
		$dealer->content = json_encode($request->input());
		$dealer->created_at = date('Y-m-d H:i:s');
        if($dealer->save())
        {
			$lang = app()->getLocale();
			$mail_data = EmailManagement::where([['type', 1],['template_name', 'dear_apply'], ['locale', $lang]])->orWhere([['type', 2],['template_name', 'dear_apply']])->get();
            foreach($mail_data as $k => $v)
            {
                $email_str = array(
                    '{Name}' => $request->name,
                    '{Title}' => $request->subject,
                    '{Phone}' => $request->phone,
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
                        $emails = explode(',',trim($v->manager_mail));
                        foreach($emails as $email)
                        {
							Mail::to($email)->later(now()->addMinutes(1), new OrderComplete($data));
                        }
                        break;
                }
            }

			return redirect()->back()->with([
				'code' => 200,
				'message' => trans('frontend.dealer.success'),
			]);
        }
    }

    public function save_data($user, $data)
    {
        $user->MemberID = $data->Entries->MemberID;
        $user->AccountType = $data->Entries->AccountType;
        $user->PhoneCode = $data->Entries->PhoneCode ?? null;
        $user->Phone = $data->Entries->Phone ?? null;
        $user->MemberName = $data->Entries->MemberName;
        $user->Sex = $data->Entries->Sex;
        $user->Birthday = $data->Entries->Birthday;
        $user->CountryCode = $data->Entries->CountryCode;
        $user->Address = $data->Entries->Address;
        $user->IsMailCheck = $data->Entries->IsMailChecked;
        $user->IsPhoneCheck = $data->Entries->IsPhoneChecked;
        $user->save();
    }

    public static function pay($request)
    {
        $event = EventSignUp::find($request->id);

        $debug = \Config::get('app.debug');
        $cafile = base_path('ctbc_bank/server.cer');
        $strMerID = $debug ? \Config::get('bank.develoption.MerID') : \Config::get('bank.production.MerID');
        $MerchantID  = $debug ? \Config::get('bank.develoption.MerchantID') : \Config::get('bank.production.MerchantID');
        $TerminalID  = $debug ? \Config::get('bank.develoption.TerminalID') : \Config::get('bank.production.TerminalID');
        $Key  = $debug ? \Config::get('bank.develoption.Key') : \Config::get('bank.production.Key');
        $strURL = $debug ? \Config::get('bank.develoption.mpi_url') : \Config::get('bank.production.mpi_url');
        
        $AcquireBIN = '';
        $CardNo = $request->card_number;
        $ExpYear = $request->year;
        $ExpMonth = $request->month;
        $authAmt = $event->total;
        $lidm = $event->number;
        $RetURL = url('/event/return');  
        $debug = '0';

            
        $MACString = mpiauth_in_mac($MerchantID,$TerminalID,$AcquireBIN,$CardNo,$ExpYear,$ExpMonth,$authAmt,$lidm,$Key,$RetURL,$debug);
        $MPIEnc = get_mpi_urlenc($MerchantID,$TerminalID,$AcquireBIN,$CardNo,$ExpYear,$ExpMonth,$authAmt,$lidm,$Key,$RetURL,$MACString,$debug);

        $strHTML='<form id="__Form" method="post" action="'.$strURL.'">';
        $strHTML.='<input type="hidden" name="merid" value="'.$strMerID.'" />';
        $strHTML.='<input type="hidden" name="MPIEnc" value="'.$MPIEnc.'" />';
         
        $strHTML.='<script type="text/javascript">document.getElementById("__Form").submit();</script>';
        $strHTML.='</form>';
         
        print $strHTML;
    }

    public function return(Request $request)
    {
        $debug = \Config::get('app.debug');
        $Key  = $debug ? \Config::get('bank.develoption.Key') : \Config::get('bank.production.Key');
        $EncRes = $request->MPIEnc;
		$dsTransID = $request->dsTransID;
        $debug = '0';
        $EncArray = genmpidecrypt($EncRes,$Key,$debug);
        $event_number = substr($EncArray['xid'], -19);
        $event = EventSignUp::where('number', $event_number)->first();

        $CardNo = $EncArray['cardnumber'] ?? "";
        $ExpDate = $EncArray['expiry'] ?? "";
        $lidm = $EncArray['xid'] ?? "";
        $ECI = $EncArray['eci'] ?? "";
        $CAVV = $EncArray['cavv'] ?? "";
        $errCode = $EncArray['errorcode'] ?? null;
        $MACString = mpiauth_out_mac($CardNo,$ExpDate,$lidm,$ECI,$CAVV,$errCode,$Key,$debug);
		
        if($MACString == $EncArray['outmac'] && empty($errCode))
        {
            $strHTML='<form id="__Form" method="post" action="'.url('/shopping/pay/').'">';
            $strHTML.='<input type="hidden" name="dsTransId" value="'.$dsTransID.'" />';
            $strHTML.='<input type="hidden" name="dsTransID" value="'.$dsTransID.'" />';
            $strHTML.='<input type="hidden" name="cavv" value="'.$EncArray['cavv'].'" />';
            $strHTML.='<input type="hidden" name="eci" value="'.$EncArray['eci'].'" />';
            $strHTML.='<input type="hidden" name="cardnumber" value="'.$EncArray['cardnumber'].'" />';
            $strHTML.='<input type="hidden" name="expiry" value="'.$EncArray['expiry'].'" />';
            $strHTML.='<input type="hidden" name="event_id" value="'.$event->id.'" />';
            
            $strHTML.='<script type="text/javascript">document.getElementById("__Form").submit();</script>';
            $strHTML.='</form>';
            
            print $strHTML;
        }
        else
            return redirect('/news-events/event/'.$event->event_id.'/register/failed');

    }

	public function birthday(Request $request)
	{
		$birthday_trigger = Setting::where('key', 'birthday_trigger')->first()->value;
		if($birthday_trigger)
		{
			$amount = Setting::where('key', 'birthday_credits_amount')->first()->value;
			$days = Setting::where('key', 'birthday_credits_expiration')->first()->value;

			$users = User::where('Birthday', 'like', '%-'.date('m-d'))->get();
			$data = [];
			foreach($users as $user)
			{
				$data[] = (string)$user->id;
			}
			$bonus = new Bonus;
			$bonus->user_id = $data;
			$bonus->amount = $amount;
			$bonus->days = $days;
			$bonus->expiration_date = date('Y-m-d', strtotime('+'.$days.' days'));
			$bonus->created_at = date('Y-m-d H:i:s');
			$bonus->save();

			foreach($users as $user)
			{
				$mail_data = EmailManagement::where([['type', 1],['template_name', 'birthday'], ['locale', $user->locale]])->orWhere([['type', 2],['template_name', 'birthday']])->get();
				foreach($mail_data as $k => $v)
				{
					$email_str = array(
						'{Name}' => $user->name,
						'{Title}' => $v->subject,
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
							$emails = explode(',',trim($v->manager_mail));
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

	public function randomstring($length = 10)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
