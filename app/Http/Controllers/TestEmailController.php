<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mail;
use App\Models\EmailManagement;
use App\Mail\SendEmail;
class TestEmailController extends Controller
{
    public function test_email(){
        return view('sendemail');
    }
    // public function test_email(){
    //     return view('emails.template');
    // }
    

    public function sendMail(){
        $mail_data= '';

        //-->>> new sign-up
        $mail_data = EmailManagement::where('template_name', 'new_sign_up')->first();

    //     //-->>> order placed
    //     $mail_data = EmailManagement::where('template_name', 'order_placed')->first();

    //     //-->>> order confirmed
    //     $mail_data = EmailManagement::where('template_name', 'order_confirmed')->first();

    //     //-->>> order delivered to customer
    //     $mail_data = EmailManagement::where('template_name', 'order_delivered_to_customer')->first();

    //     //-->>> order delivered to store
    //     $mail_data = EmailManagement::where('template_name', 'order_delivered_to_store')->first();

    //     //-->>> order cancel
    //     $mail_data = EmailManagement::where('template_name', 'order_cancel')->first();

    //     //-->>> event signup confirmed
    //     $mail_data = EmailManagement::where('template_name', 'event_signup_confirmed')->first();

    //     //-->>> event signup canceled
    //     $mail_data = EmailManagement::where('template_name', 'event_signup_canceled')->first();

    //     //-->>> event canceled
    //     $mail_data = EmailManagement::where('template_name', 'event_canceled')->first();

    //     //-->>> bike warranty extension successful
    //     $mail_data = EmailManagement::where('template_name', 'bike_warranty_extension_successful')->first();

    //     //-->>> bike registration result
    //     $mail_data = EmailManagement::where('template_name', 'bike_registration_result')->first();
        
    //    //-->>> customer credit expiration notice
    //     $mail_data = EmailManagement::where('template_name', 'customer_credit_expiration_notice')->first();

        // //-->>> reward birthday credit to customer
        // $mail_data = EmailManagement::where('template_name', 'reward_birthday_credit_to_customer')->first();

        if($mail_data){
            $data = array(
                'template_name' => $mail_data['template_name'],
                'logo' => $mail_data['logo'],
                'name'=> $mail_data['sender_name'],
                'subject'=> $mail_data['subject'],
                'body' => $mail_data['body']
            );
    
            Mail::to(config('mail.mailers.admin_email'))->send(new SendEmail($data));
            return redirect('/email');
           
        }
        else{
            return 'There is no email template configured!';
        }
       
    }
}
