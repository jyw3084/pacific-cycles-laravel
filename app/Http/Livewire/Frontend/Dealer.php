<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Mail;
use App\Models\EmailManagement;
use App\Models\Store;
use App\Models\Banner;
use App\Models\Head;
use App\Mail\SendEmail;

class Dealer extends Component
{
    public $view = 1;
    public $name, $email, $subject, $message;
    public $validated_fields;
    public $location, $radius, $category;

    protected $rules = [
        'name' => 'required|min:6',
        'email' => 'required|email',
    ];

    public function mount() {
        app()->setLocale(session()->get('locale'));
        $lang = app()->getLocale();
        $this->validated_fields = [];
        $this->dealers = Store::get();
        $this->banner = Banner::where('link', 'dealer')->first();
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();

    }

    public function render()
    {
        return view('livewire.frontend.dealer', [
            'dealers' => $this->dealers,
            'banner' => $this->banner,
        ])
        ->extends('layouts.dealer-layout', [
            'head' => $this->head,
            'dealers' => $this->dealers,
        ])
        ->section('content');
    }

    public function setView1(){
        $this->view = 1;
    }
    public function setView2(){
        $this->view = 2;
    }

    public function updating($field) 
	{
		Arr::pull($this->validated_fields, $field);
	}


    public function updated($field)
    {
        $v = $this->validateOnly($field);
		$this->validated_fields[$field] = Arr::get($v, $field, false);	
    }


    public function applyDealerShip(Request $request){
        $data = $request['serverMemo']['data'];
        $name = $data['name'];
        $email = $data['email'];
        $subject = $data['subject'];
        $message = $data['message'];

        ////////    execute the mailer here    |\/|
        $mail_data= '';

        //select email template
        //this email selection should be change
        $mail_data = EmailManagement::where('template_name', 'new_sign_upss')->first();
       
        if($mail_data){
            $data = array(
                'template_name' => $mail_data['template_name'],
                'logo' => $mail_data['logo'],
                'name'=> $mail_data['sender_name'],
                'subject'=> $mail_data['subject'],
                'body' => $mail_data['body']
            );
    
            Mail::to(config('mail.mailers.admin_email'))->send(new SendEmail($data));
            return redirect('/dealer');
           
        }
        else{
            return 'There is no email template configured!';
        }
    }
}
