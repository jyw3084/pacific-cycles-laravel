<?php

namespace App\Http\Controllers;

// use App\Models\;
use App\Http\Controllers\Controller;
use Request;
use Illuminate\Support\Facades\URL;
use Firebase\JWT\JWT;

class APIController extends Controller
{

    protected $baseUrl = "https://demo.shinda.com.tw/PacificWebApi/api/Api";
    protected $bearer = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.e30.4BpdcOpTOxSdR7vj-gHbULadqEQ2bHNkZcx9GD4VLcE';

    public static function makeApi($parameters, $payload = array())
    {
        $baseUrl = "https://demo.shinda.com.tw/PacificWebApi/api/Api";
        $key = '6LevAEEaSHINDAFC8JIOC2MHFrvkqDFOtOwxkMMcn';
        $jwt = JWT::encode((object)$payload, $key, 'HS256');
        
        $bearer = 'Bearer '.$jwt;
        if($parameters){
            $param = json_decode($parameters, true);
            $param ? $param : parse_str($str, $param);
        }
        else{
            $str = file_get_contents("php://input");
            if (!$str) {
                $param = array_merge($_POST, $_GET);
                $str = json_encode($param, 320);

            } else {
                $param = json_decode($str, true);
                $param ? $param : parse_str($str, $param);
            }
        }
        
        $url = $baseUrl.$param['url'];
        $jsonData = (object)$param['data'];
        $headers = array(
            "Content-Type:application/json;charset=utf-8",
            "Authorization:". $bearer,
        );

        $data = json_encode($jsonData);
        $result = APIController::curl($url, $data, $headers);
        return $result;
    }

    private static function curl($url, $params, $headers)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); //timeout in seconds

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $result = curl_exec($curl);

        curl_close($curl);
        return $result;
    }

    public static function Register($data, $type = 0){
        $PhoneCode = '+886';
        if(!empty($data->phone))
        {
            if(substr($data->phone, 0,1) == '0')
                $PhoneCode = '+886';
            else
                $PhoneCode = substr($data->phone, 0,4);
        }

        $parameters = array();
        $parameters['url'] = '/Register';
        $parameters['data']['AccountType'] = $data->AccountType;     //0:phone 1:email
        $parameters['data']['RegisterType'] = $type;    //0:nomal 1:3party auth
        $parameters['data']['PhoneCode'] = $PhoneCode ?? '';
        $parameters['data']['Phone'] = (string)$data->phone;
        $parameters['data']['Email'] = $data->email;
        $parameters['data']['MemberName'] = $data->name;
        $parameters['data']['Pwd'] = $type == 0 ? $data->password : '';
        $parameters['data']['Sex'] = 0;
        $parameters['data']['Birthday'] = $data->Birthday ?? '';
        $parameters['data']['CountryCode'] = $PhoneCode;
        $parameters['data']['Address'] = $data->Address ?? '';
        $parameters['data']['TimeZoneNo'] = 0;
        $parameters['data']['IsEpaper'] = false;
        $jsonData = json_encode($parameters);
        return APIController::makeApi($jsonData);
    }

    public static function login($data){
        $parameters = array();
        $parameters['url'] = '/login';
        $parameters['data']['Account'] = $data->username;
        $parameters['data']['Pwd'] = $data->password;
        $jsonData = json_encode($parameters);
        return APIController::makeApi($jsonData);
    }


    public static function memberSaveData($user, $new_pwd, $old_pwd){
        $parameters = array();
        $parameters['url'] = '/MemberSaveData';
        $parameters['data']['AccountType'] = 0;
        $parameters['data']['PhoneCode'] = $user->PhoneCode;
        $parameters['data']['Phone'] = $user->Phone;
        $parameters['data']['Email'] = $user->email;
        $parameters['data']['MemberName'] = $user->MemberName;
        $parameters['data']['Sex'] = $user->Sex;
        $parameters['data']['Birthday'] = $user->Birthday;
        $parameters['data']['CountryCode'] = $user->CountryCode;
        $parameters['data']['Address'] = $user->Address;
        $parameters['data']['TimeZoneNo'] = $user->TimeZone;
        $parameters['data']['NewPwd'] = $new_pwd;
        $parameters['data']['OldPwd'] = $old_pwd;
        $parameters['data']['IsPhoneChecked'] = $user->IsPhoneCheck;
        $parameters['data']['IsMailChecked'] = $user->IsMailCheck;
        $parameters['data']['ModifyType'] = 2;

        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function getMemberData($user){
        $parameters = array();
        $parameters['url'] = '/GetMemberData';
        $parameters['data']['MemberId'] = $user->MemberID;
        $parameters['data']['Email'] = '';
        $parameters['data']['Phone'] = '';
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function forgotPassword($data){
        $parameters = array();
        $parameters['url'] = '/ForgetPassword';
        $parameters['data']['Account'] = $data->account;
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function getTimeZone(){
        $parameters = array();
        $parameters['url'] = '/GetTimeZone';
        $parameters['data']['CountryCode'] = 'tw';
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function sendSms($phoneNumber, $message = null){
        if(substr($phoneNumber, 0,1) == '0')
            $phoneNumber = '+886'.substr($phoneNumber, 1);
        
        $parameters = array();
        $parameters['url'] = '/SendSMS';
        $parameters['data']['PhoneNumber'] = $phoneNumber;
        $parameters['data']['Message'] = $message;
        $jsonData = json_encode($parameters);
        
        return APIController::makeApi($jsonData);

    }

    public static function getBicycleType(){
        $parameters = array();
        $parameters['url'] = '/GetBicycleType';
        $parameters['data'] = '';
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function getProductModel($id){
        $parameters = array();
        $parameters['url'] = '/GetProductModel';
        $parameters['data']['BicycleTypeID'] = (string)$id;
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function getProductColor($ProductNo){
        $parameters = array();
        $parameters['url'] = '/getProductColor';
        $parameters['data']['ProductNo'] = $ProductNo;
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function getBuyArea(){
        $parameters = array();
        $parameters['url'] = '/GetBuyArea';
        $parameters['data'] = '';
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }


    public static function getProductCompany(){
        $parameters = array();
        $parameters['url'] = '/GetProductCompany';
        $parameters['data']['AreaNo'] = '123';

        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function saveCerProductData($data){
        $user = auth()->user();
        $payload = array(
            'MemberId' => $user->MemberID,
            'MemberName' => $user->MemberName,
        );
        $parameters = array();
        $parameters['url'] = '/SaveCerProductData';
        $parameters['data']['ProductNo'] = $data->ProductNo ?? null;
        $parameters['data']['BBNo'] = $data->BBNo;
        $parameters['data']['BuyAreaNo'] = $data->BuyAreaNo;
        $parameters['data']['BuyCompanyNo'] = $data->BuyCompanyNo;
        $parameters['data']['BuyDate'] = $data->BuyDate;
        $parameters['data']['BicycleType'] = $data->Type == 1 ? null : $data->BicycleType;
        $parameters['data']['Models'] = $data->Type == 1 ? null : $data->Models;
        $parameters['data']['Color'] = $data->Type == 1 ? null : $data->Color;
        $parameters['data']['CertificatePic'] = $data->CertificatePic;
        $parameters['data']['FullPic'] = $data->FullPic;
        $parameters['data']['BBNoPic'] = $data->BBNoPic;
        $parameters['data']['Type'] = $data->Type;
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData, $payload);
    }

    public static function MyBikeGetList(){
        $user = auth()->user();
        $payload = array(
            'MemberId' => $user->MemberID,
            'MemberName' => $user->MemberName,
        );
        $parameters = array();
        $parameters['url'] = '/MyBikeGetList';
        $parameters['data'] = (object)[];
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData, $payload);
    }

    public static function buyProductWarrant($data){
        $user = auth()->user();
        $payload = array(
            'MemberId' => $user->MemberID,
            'MemberName' => $user->MemberName,
        );
        $parameters = array();
        $parameters['url'] = '/BuyProductWarranty';
        $parameters['data']['ProductNo'] = $data->ProductNo ?? null;
        $parameters['data']['BuyWarrantyYear'] = $data->year ?? null;
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData, $payload);
    }

    public static function getProductInfo($ProductNo){
        $parameters = array();
        $parameters['url'] = '/GetProductInfo';
        $parameters['data']['ProductNo'] = (string)$ProductNo;
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function getProductLogs($ProductNo){
        $parameters = array();
        $parameters['url'] = '/GetProductLogs';
        $parameters['data']['ProductNo'] = (string)$ProductNo;
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function saveProductImages(){
        $parameters = array();
        $parameters['url'] = '/SaveProductImages';
        $parameters['data']['ProductNo'] = '123';
        $parameters['data']['BikeImages'][0] = '1.png';
        $parameters['data']['BikeImages'][1] = '2.png';
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function getQrcodeList(){
        $user = auth()->user();
        $payload = array(
            'MemberId' => $user->MemberID,
            'MemberName' => $user->MemberName,
        );
        $parameters = array();
        $parameters['url'] = '/GetQrcodeList';
        $parameters['data'] = (object)[];
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData, $payload);
    }

    public static function CancelQrcode($No){
        $parameters = array();
        $parameters['url'] = '/CancelQrcode';
        $parameters['data']['No'] = (int)$No;
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function getTransferList(){
        $user = auth()->user();
        $payload = array(
            'MemberId' => $user->MemberID,
            'MemberName' => $user->MemberName,
        );
        $parameters = array();
        $parameters['url'] = '/getTransferList';
        $parameters['data'] = (object)[];
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData, $payload);
    }

    public static function agreeTransfer($ProductNo, $TransferID){
        $parameters = array();
        $parameters['url'] = '/AgreeTransfer';
        $parameters['data']['ProductNo'] = (string)$ProductNo;
        $parameters['data']['TransferID'] = (string)$TransferID;

        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function CancelTransfer($ProductNo){
        $user = auth()->user();
        $parameters = array();
        $parameters['url'] = '/CancelTransfer';
        $parameters['data']['ProductNo'] = (string)$ProductNo;
        $parameters['data']['MemberId'] = (string)$user->MemberID;

        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function productTransfer($target, $ProductNo){
        $user = auth()->user();
        $payload = array(
            'MemberId' => $user->MemberID,
            'MemberName' => $user->MemberName,
        );
        $parameters = array();
        $parameters['url'] = '/ProductTransfer';
        $parameters['data']['ProductNo'] = $ProductNo;
        $parameters['data']['ToMemberID'] = $target->MemberID;
        $parameters['data']['Memo'] = $target->memo ?? '';
        
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData, $payload);
    }

    public static function getCompanyLocationList($code){
        $parameters = array();
        $parameters['url'] = '/GetCompanyLocationList';
        $parameters['data']['BicycleTypeID'] = [1];
        $parameters['data']['FeaturesID'] = [0];
        $parameters['data']['AreaNo'] = $code;
        
        $jsonData = json_encode($parameters);
        
        return APIController::makeApi($jsonData);
    }

    public static function getProductImage(){
        $parameters = array();
        $parameters['url'] = '/getProductImage';
        $parameters['data']['ProductNo'] = '123';
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }

    public static function GetProductQRCodeInfo($data){
        $parameters = array();
        $parameters['url'] = '/GetProductQRCodeInfo';
        $parameters['data']['ProductNo'] = $data->ProductNo;
        $parameters['data']['BBNo'] = $data->BBNo;
        
        $jsonData = json_encode($parameters);

        return APIController::makeApi($jsonData);
    }


    public static function randomNumber($length){
        $random= "";
        srand((double)microtime()*1000000);
    
        $data = "0123456789";
    
        for($i = 0; $i < $length; $i++){
            $random .= substr($data, (rand()%(strlen($data))), 1);
        }
    
        return $random;
    }



 

}
