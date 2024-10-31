<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Event;
use App\Models\Banner;
use App\Models\EventSignUp;

class EventRegister extends Component
{
    public $event;
    public $method = 1;
    public $event_id, $lang;
    public $signup = 0;
    public $free = 0;
    public $post = [];

    public function mount($id)
    {
        app()->setLocale(session()->get('locale'));
        $this->lang = $lang = app()->getLocale();
        $user = auth()->user();
        if(!$user)
        {
            session()->put('back', '/news-events/event/'.$id.'/register');
            return redirect('login');
        }
        
        $sign = EventSignUp::where([['user_id', $user->id], ['event_id', $id]])->first();

        $this->event = $event = Event::find($id);
        $this->free = empty($event->price) ? 1: 0;
        $this->event_id = $id;
        $this->head = $event->head[$lang] ?? '';

        if($sign)
        {
            if($sign->paid == 0)
            {
                $fields = $event->fields;

                $this->signup = empty($sign->paid) ? 1: 0;
                $data = json_decode($sign->content);
                $validate = [];
                foreach($fields as $field)
                {
                    $validate[$field['key']] = $data->{$field['key']};
                }
                $this->post = $validate;
            }
            else
            {
                if(!session('message'))
                    session()->flash('message', trans('frontend.news.has_signup'));
            }
        }
        else
        {
            $fields = $event->fields;
            
            $this->signup = empty($event->price) ? 1: 0;
            $this->post = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'birthday' => $user->Birthday,
                'address' => $user->Address,
            ];
        }
    }
    public function render()
    {
        return view('livewire.frontend.event-register', [
            'banner' => Banner::where('link', 'news-events')->first(),
        ])
        ->extends('layouts.news-events-layout', [
            'head' => $this->head,
        ])
        ->section('content');
    }
}
