<div>
    <div class="store-header" id="cart-header">
    </div>
        <nav aria-label="breadcrumb" class="breadcrumb-1 p-3">
        <div class="container">
            <ol class="breadcrumb mb-0 breadcrumb-1">
            <li class="breadcrumb-item"><a href="#">{{ trans('frontend.Home') }}</a></li>
            <li class="breadcrumb-item"><a href="/store">{{ trans('frontend.store.title') }}</a></li>
            <li class="breadcrumb-item breadcrumb-item-active"><a href="/shopping/cart">{{ trans('frontend.store.cart') }}</a></li>
            </ol>
        </div>
    </nav>
    
    <nav aria-label="breadcrumb" class="breadcrumb-2">
        <div class="container">
            <ol class="breadcrumb breadcrumb-2">
                <li class="order-process breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active"><a href="#" style="color:#000000 !important;">{{ trans('frontend.store.order_detail') }}</a></li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="payment" href="#" style="color:#000000 !important;">{{ trans('frontend.store.select_payment') }}</a>
                </li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="payment" href="#" style="color:#000000 !important;">{{ trans('frontend.store.payment_detail') }}</a>
                </li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="order-complete" href="#" style="color:#000000 !important;">{{ trans('frontend.store.order_complete') }}</a>
                </li>
            </ol>
        </div>
    </nav>
    <div class="container">
    <section class="mt-5">
        <h3 class="cart-title">{{ trans('frontend.store.ur_order') }}</h3>
        <div class="row p-2">
            <table id="tbl-cart" class="table table-borderless table-responsive" >
                <thead>
                  <tr>
                    <th class="text-left" width="45%">{{ trans('frontend.store.product') }}</th>
                    <th class="text-center">{{ trans('frontend.store.price') }}</th>
                    <th class="text-center">{{ trans('frontend.store.qty') }}</th>
                    <th class="text-right">{{ trans('frontend.store.subtotal') }}</th>
                  </tr>
                </thead>
                <tbody id="order_data">
                <?php $amount = 0; ?>
                @foreach($detail as $k => $v)
                <?php
                    switch($v->product_type)
                    {
                        case 1:
                            $name = $v->package->name;
                            $img = !empty($v->package->images[0]) ? Storage::url($v->package->images[0]) : '';
                            break;
                        case 2:
                            $name = $v->product->product_name;
                            $img = !empty($v->product->images[0]) ? Storage::url($v->product->images[0]) : '';
                            break;
                    }
                    $amount += $v->price * $v->quantity;
                ?>
                  <tr >
                    <td><img src="{{ url($img) }}" width="300px" alt="" style="float: left;"> {{$name}}</td>
                    <td class="text-center">{{$order->currency}} ${{$v->price}}</td>
                    <td class="text-center">{{$v->quantity}}</td>
                    <td class="text-right">{{$order->currency}} ${{$v->price * $v->quantity}}</td>
                  </tr>
                @endforeach
                  <tr id="summary">
                    <td class="pt-4"></td>
                    <td colspan="2" class="pt-4 text-left">{{ trans('frontend.store.amount') }}</td>
                    <td colspan="2" class="pt-4 text-right">{{$order->currency}} ${{$amount}}</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="text-left">{{ trans('frontend.store.shipping_fee') }}</td>
                    <td colspan="2" class="text-right">{{$order->currency}} ${{$order->shipping}}</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="text-left">{{ trans('frontend.store.full_discount') }}</td>
                    <td colspan="2" class="text-right">{{$order->currency}} ${{$order->discount}}</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="text-left">{{ trans('frontend.store.redeem_code_promo') }}</td>
                    <td colspan="2" class="text-right">{{$order->currency}} ${{$order->promo}}</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="text-left">{{ trans('frontend.store.coupon') }}</td>
                    <td colspan="2" class="text-right">{{$order->currency}} ${{$order->coupon}}</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="text-left">{{ trans('frontend.store.redeem_member_points') }}</td>
                    <td colspan="2" class="text-right">{{$order->currency}} ${{$order->point}}</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2" class="text-left "><h5 class="total">{{ trans('frontend.store.total') }}</h5><span class="text-muted total-note">{{ trans('frontend.store.exclusive_taxes') }}</span></td>
                    <td colspan="2" class="text-right"><h3 class="total-amount">{{$order->currency}} ${{$order->total}}</h3></td>
                  </tr>
                </tbody>
              </table>
        </div>
    </section>
    <section class="mt-5" id="shipping-details">
        <h3 class="cart-title border-below">{{ trans('frontend.store.shipping_detail') }}</h3>
        <div class="row section-line">
            <div class="col-md-6 mt-5">
                <div class="pb-4">
                    <div class="first-row">{{ trans('frontend.store.name') }}</div>
                    <div class="pl-3">{{$order->name}}</div>
                </div>
                <div class="pb-4">
                    <div class="first-row">Email</div>
                    <div class="pl-3">{{$order->email}}</div>
                </div>
                <div class="pb-4">
                    <div class="first-row">{{ trans('frontend.store.phone') }}</div>
                    <div class="pl-3">{{$order->phone}}</div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="pb-4">
                    <div class="first-row">{{ trans('frontend.store.address') }}</div>
                    <div class="pl-3">{{$order->address}}</div>
                </div>
                <div class="pb-4">
                    <div class="first-row">{{ trans('frontend.store.note') }}</div>
                    <div class="pl-3">{{$order->note}}</div>
                </div>
            </div>
        </div>
    </section>
    <section class="mt-5 mb-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col"></div>
                    <div class="col">
                        <a href="/" class="mybtn btn btn-warning">{{ trans('frontend.store.back2home') }}</a>
                    </div>
                    <div class="col"></div>
                </div>
                
            </div>
            <div class="col-md-3"></div>
        </div>
    </section>
    </div>
</div>
