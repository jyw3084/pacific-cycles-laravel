<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mail;
use App\Models\EmailManagement;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\WarrantyExtension;
use App\Models\Event;
use App\Models\User;
use App\Models\Products;
use App\Models\Packages;
use App\Mail\OrderComplete;
use Illuminate\Support\Facades\URL;

require_once base_path('ctbc_bank/auth_mpi_mac.php');

class OrderController extends Controller
{
    public function pay(Request $request)
    {
        $order = Order::find($request->id);

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
        $authAmt = $order->total;
        $lidm = $order->number;
        $RetURL = url('/order/return');  
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
        $dsTransID = $request->dsTransID;
        $EncRes = $request->MPIEnc;
        $debug = '0';
        $EncArray = genmpidecrypt($EncRes,$Key,$debug);
        $order_number = substr($EncArray['xid'], -19);

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
            $strHTML.='<input type="hidden" name="order_number" value="'.$order_number.'" />';
            
            $strHTML.='<script type="text/javascript">document.getElementById("__Form").submit();</script>';
            $strHTML.='</form>';
            
            print $strHTML;
        }
        else
            return redirect('shopping/failed-payment');

    }
}
