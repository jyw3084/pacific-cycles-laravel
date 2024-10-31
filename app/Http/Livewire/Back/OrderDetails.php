<?php

namespace App\Http\Livewire\Back;

use Livewire\Component;
use App\Models\Order;
use App\Models\EmailManagement;
use App\Models\Note;
use Mail;
use App\Mail\OrderComplete;

class OrderDetails extends Component
{
    public $order = [];
    public $content;

    public function mount($number){
        app()->setLocale(session()->get('locale'));
        
        $user = auth()->user();

        $this->order = Order::where('number', $number)->first();
    }
    
    public function render()
    {
        return view('livewire.back.order-details')
        ->extends('layouts.back.backend-layout')
        ->section('content');
    }

    public function send()
    {
        $order = $this->order;
        $this->rules = [
            'content' => 'required',
        ];
        $data = $this->validate();
        $data = (object)$data;
        $content = $data->content;

        $note = new Note;
        $note->order_id = $order->id;
        $note->message = $content;
        $note->created_at = date('Y-m-d H:i:s');
        $note->save();
        
        $this->content = '';
        $this->mount($order->number);
        
        $lang = app()->getLocale();
        $mail_data = EmailManagement::where([['type', 1],['template_name', 'order_inquiry'], ['locale', $lang]])->orWhere([['type', 2],['template_name', 'order_inquiry']])->get();

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
    
    public function cancel($id)
    {
        $order = Order::find($id);
        $order->status = 5;
        $order->save();
        $this->mount($order->number);
        
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
    }
}
