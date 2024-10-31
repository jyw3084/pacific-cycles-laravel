<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;
use App\Models\Order;
use App\Models\EmailManagement;
use Mail;
use App\Mail\OrderComplete;

class MyOrders extends Component
{
    public $orders = [];

    public function mount(){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();
        $this->orders = $user->order;
    }
    
    public function render()
    {
        return view('livewire.back.my-orders')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }
    
    public function cancel($id)
    {
        $order = Order::find($id);
        $order->status = 4;
        $order->save();
        
        $lang = app()->getLocale();
        $mail_data = EmailManagement::where([['type', 1],['template_name', 'order_cancel'], ['locale', $lang]])->orWhere([['type', 2],['template_name', 'order_cancel']])->get();

        foreach($mail_data as $k => $v)
        {
            $email_str = array(
                '{Name}' => $order->user->name,
                '{Title}' => $v->subject,
                '{FormNo}' => $order->number,
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
                    Mail::to($email)->send(new OrderComplete($data));
                    break;
                case 2:
                    $emails = explode(',',$v->manager_mail);
                    foreach($emails as $email)
                    {
                        Mail::to($email)->send(new OrderComplete($data));
                    }
                    break;
            }
        }
        $this->mount();
    }
    
    public function return($id)
    {
        $order = Order::find($id);
        $lang = app()->getLocale();
        $mail_data = EmailManagement::where([['type', 1],['template_name', 'order_return'], ['locale', $lang]])->orWhere([['type', 2],['template_name', 'order_return']])->get();

        foreach($mail_data as $k => $v)
        {
            $email_str = array(
                '{Name}' => $order->user->name,
                '{Title}' => $v->subject,
                '{FormNo}' => $order->number,
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
                    Mail::to($email)->send(new OrderComplete($data));
                    break;
                case 2:
                    $emails = explode(',',$v->manager_mail);
                    foreach($emails as $email)
                    {
                        Mail::to($email)->send(new OrderComplete($data));
                    }
                    break;
            }
        }
    }
}
