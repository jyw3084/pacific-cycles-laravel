<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
use App\Models\Setting;
use App\Models\Bonus;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\APIController;

class SocialitesGoogleController extends Controller
{
    //跳轉到google授權頁麵
    public function google()
    {
        if (Auth::check())
            return redirect('my-bike');

        return Socialite::with('google')->redirect();
    }

    //用戶授權後，跳轉回來
    public function callback(Request $request)
    {
        $lang = app()->getLocale();
        if($request->error_code)
            return redirect('login');
        
        $auth = Socialite::driver('google')->user();
        //dump($info);
        $user = User::where([['email', $auth->email], ['user_type', 2]])->first();
        if($user)
        {
            if (Auth::loginUsingId($user->id))
                return redirect('login')->with(['code' => 200]);
        }

        $user = new User;
        $user->active = 1;
        $user->user_type = 2;
        $user->AccountType = 1;
        $user->auth = 'Google';
        $user->auth_id = $auth->id;
        $user->name = $auth->name;
        $user->email = $email = $auth->email;
        $user->locale = $lang;
        $user->created_at = date('Y-m-d H:i:s');
        $user->save();

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
    
        $response = APIController::getMemberData($user);
        $data = json_decode($response);
        if(empty($data->Entries->MemberID))
        {
            APIController::Register($user, $type = 1);
            $response = APIController::getMemberData($user);
            $data = json_decode($response);
            $this->save_data($user, $data);

            Auth::login($user);
            return redirect('login')->with(['code' => 200]);
        }
        else
        {
            $this->save_data($user, $data);
            Auth::login($user);
            return redirect('login')->with(['code' => 200]);
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
}
