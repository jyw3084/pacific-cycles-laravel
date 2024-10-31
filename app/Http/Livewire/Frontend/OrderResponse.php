<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Head;

class OrderResponse extends Component
{
    public $order = [];
    public $detail = [];

    public function mount($number)
    {
        app()->setLocale(session()->get('locale'));
        $this->lang = $lang = app()->getLocale();
        $this->head = Head::where([['link', request()->path()], ['locale', $lang]])->first();
        $order = Order::where([['number', $number], ['paid', 1]])->first();
        if($order)
        {
            $this->order = $order;
            $this->detail = OrderDetail::where('order_id', $order->id)->get();
        }
        else
            return redirect('/');

    }

    public function render()
    {
        return view('livewire.frontend.order-response',[
                'order' => $this->order,
                'detail' => $this->detail,
            ])
            ->extends('layouts.frontend-layout', [
                'head' => $this->head,
            ])
            ->section('content');
    }
}
