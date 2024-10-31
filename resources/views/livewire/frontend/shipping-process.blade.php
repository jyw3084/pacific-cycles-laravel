
<div>
    <div class="store-header" id="cart-header">
        <div class="store-header-container">
        </div>
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

                    <a id="payment" href="#" @if($currentStep >= 2) style="color:#000000 !important;" @endif>{{ trans('frontend.store.select_payment') }}</a>

                </li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">

                    <a id="payment" href="#" @if($currentStep >= 3) style="color:#000000 !important;" @endif>{{ trans('frontend.store.payment_detail') }}</a>

                </li>
                <li class="order-process breadcrumb-item breadcrumb-2-items">
                    <a id="order-complete" href="#">{{ trans('frontend.store.order_complete') }}</a>
                </li>
            </ol>
        </div>
    </nav>
    <div class="container">

        <form class="mt-3 {{ $currentStep < 3 ? '' : 'd-none'}}" wire:submit.prevent="goToPayment" id="discount-form">
            <section id="discount_section" class="mt-3">
            @if($currentStep == 2 && auth()->user())
                <h3 class="cart-title border-below">{{ trans('frontend.store.discounts') }}</h3>
                <div class="row section-line">
                    <div class="col-md-5 mt-3">
                        <div class="row pl-2">
                            <div class="col-md-8 form-group">
                                <label>{{ trans('frontend.store.plz_enter_redeem_code') }}</label>
                                <input type="text" class="form-control" id="usr" wire:change="redeem_code($event.target.value)" value="{{ $promo_code }}">
                                @if($promo_id) <span class="text-success">{{ $promo_contect }}</span>
                                @elseif($promo_contect) <span class="text-danger error">{{ $promo_contect }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="apply_coupon" @if(empty($has_coupon)) disabled @else  wire:click="apply_coupon" @endif @if($checked_coupon) checked @endif>
                            <label class="form-check-label" for="apply_coupon">{{ trans('frontend.store.apply_coupon') }}</label>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-8 form-group">
                                <select class="form-control" id="sel1" @if(empty($checked_coupon)) disabled @endif wire:change="select_coupon($event.target.value)">
                                @if($coupon)
                                <option disabled>{{ trans('frontend.store.select_coupon') }}</option>
                                @endif
                                @foreach($coupon as $k => $v)
                                <option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="redeem_member_point" @if(empty($point)) disabled @else  wire:click="use_point" @endif @if($use_point) checked @endif>
                            <label class="form-check-label" for="redeem_member_point">{!! trans('frontend.store.redeem_member_point', ['points' => $point]) !!}</label>
                        </div>

                    </div>
                    <div class="col-md-7 mt-3">

                    </div>
                </div>
            @endif
            </section>
            <section class="mt-5">
                <h3 class="cart-title">{{ trans('frontend.store.cart') }}</h3>
                <div class="row p-2">
                    {{ $currencie_code = '' }}
                    {{ $currencie_symbol = '' }}
                    @if(count($dataCart) > 0)
                        <table id="tbl-cart" class="table table-borderless table-responsive" >
                            <thead>
                            <tr>
                                <th class="text-left" width="25%">{{ trans('frontend.store.product') }}</th>
                                <th class="text-left" width="20%"></th>
                                <th class="text-center">{{ trans('frontend.store.price') }}</th>
                                <th class="text-center">{{ trans('frontend.store.qty') }}</th>
                                <th class="text-center">{{ trans('frontend.store.add2favorite') }}</th>
                                <th>{{ trans('frontend.store.remove') }}</th>
                            </tr>
                            </thead>
                            <tbody id="order_data">
                                @foreach($dataCart as $k => $data)
                                <tr>
                                    <td>
                                        <?php $image = $data->images[0] ?? ''; ?>
                                        <img src="{{ Storage::url($image) }}" width="300px" alt="">
                                        {{-- <span class="text-nowrap">
                                        </span> --}}
                                    </td>
                                    <td class="text-center">
                                        @if($data->product_name)
                                        {{ $data->product_name }} @if($data->color != 'n/a'){{ ' ('.$data->color.')' }}@endif
                                        @else
                                        {{ $data->name }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $currencie_code = $data->currencies->code }}
                                        {{ $currencie_symbol = $data->currencies->symbol }}{{ $data->price }}

                                    </td>
                                    <td>
                                        <div class="form-group pt-3">
                                            <select class="form-control" wire:change="changeQty($event.target.value, '{{$rows[$data->id]}}')">
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option @if($qty[$data->id] == $i) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </td>
                                    <td class="text-center"><a class="text-warning fa @if(auth()->user()){{ in_array($data->id, explode(';',auth()->user()->favourites)) ? 'fa-heart': 'fa-heart-o' }} @else fa-heart-o @endif add_to_favorites" wire:click="add2favorites({{ $data->id }}, {{ $data->type ?? 0 }})" aria-hidden="true"></a></td>
                                    <td><a id="{{$data->id}}" class="btn btn-default" wire:click="deleteId({{ $data->id }})" data-toggle="modal" data-target="#removeFromCart">&times;</a></td>
                                </tr>
                                @endforeach
                                @if($currentStep == 2)
                                    <tr id="summary">
                                        <td></td>
                                        <td class=""></td>
                                        <td colspan="2" class="text-left">{{ trans('frontend.store.amount') }}</td>
                                        <td colspan="2" class="text-right">{{$currencie_code}} {{$currencie_symbol}}{{$total}}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2" class="text-left">{{ trans('frontend.store.shipping_fee') }}</td>
                                        <td colspan="2" class="text-right">{{$currencie_code}} {{$currencie_symbol}}{{$shipping_fee}}</td>
                                    </tr>
                                    @if($discount)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2" class="text-left">{{ trans('frontend.store.full_discount') }}</td>
                                        <td colspan="2" class="text-right">- {{$currencie_code}} {{$currencie_symbol}}{{ $discount }}</td>
                                    </tr>
                                    @endif
                                    @if($promo)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2" class="text-left">{{ trans('frontend.store.redeem_code_promo') }}</td>
                                        <td colspan="2" class="text-right">- {{$currencie_code}} {{$currencie_symbol}}{{ $promo }}</td>
                                    </tr>
                                    @endif
                                    @if($use_coupon)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2" class="text-left">{{ trans('frontend.store.coupon') }}</td>
                                        <td colspan="2" class="text-right">- {{$currencie_code}} {{$currencie_symbol}}{{ $use_coupon }}</td>
                                    </tr>
                                    @endif
                                    @if($use_point)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2" class="text-left">{{ trans('frontend.store.redeem_member_points') }}</td>
                                        <td colspan="2" class="text-right">- {{$currencie_code}} {{$currencie_symbol}}{{ $use_point }}</td>
                                    </tr>
                                    @endif
                                @endif
                            <tr id="total-cart-td">
                                <td></td>
                                <td></td>
                                <td colspan="2" class="pt-4 pb-5 text-left"><h5 class="total">{{ trans('frontend.store.subtotal') }}</h5> <span class="text-muted total-note">{{ trans('frontend.store.exclusive_taxes') }}</span></td>
                                <td colspan="2" class="pt-4 pb-5 text-right"><h3 class="total-amount">{{$currencie_code}} {{$currencie_symbol}}{{ $currentStep == 2 ? $total + $shipping_fee - $discount - $promo - $use_coupon - $use_point : $total }}</h3></td>
                            </tr>
                            </tbody>
                        </table>
                    @else
                        <div class="col section-line">
                            <p class="mt-5 text-center text-muted">{{ trans('frontend.store.no_items_on_cart') }}</p>
                        </div>
                    @endif
                </div>
            </section>
            @if($currentStep == 2)
            <section id="formpayment" class="mt-5">
                <h3>{{ trans('frontend.store.shipping') }}</h3>
                <div class="row section-line">
                    <div class="col mt-5">
                        <div class="form-group">
                            <label>{{ trans('frontend.store.name') }} <sup class="text-danger">*</sup></label>
                            <input type="text" name="name" class="form-control" id="name" wire:model="name">
                            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Email <sup class="text-danger">*</sup></label>
                            <input type="email" name="email" class="form-control" id="email" wire:model="email">
                            @error('email') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>{{ trans('frontend.store.phone') }} <sup class="text-danger">*</sup></label>
                            <input type="text" name="phone" class="form-control" id="phone" wire:model="phone">
                            @error('phone') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col mt-5">
                        <div class="form-group">
                            <label>{{ trans('frontend.store.address') }} <sup class="text-danger">*</sup></label>
                            <input type="text" name="address" class="form-control" id="address" wire:model="address">
                            @error('address') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>{{ trans('frontend.store.note') }}</label>
                            <textarea name="note" class="form-control" rows="5" id="note" wire:model="note"></textarea>
                        </div>
                    </div>
                </div>
            </section>
            @endif

            @if(count($dataCart) > 0)
                <section class="mt-5 mb-5">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div id="step1div" class="row {{ $currentStep != 1 ? 'd-none' : ''}}">
                                <div class="col">
                                    <a class="mybtn btn btn-secondary">{{ trans('frontend.store.continue_shopping') }}</a>
                                </div>
                                <div class="col">
                                    <a wire:click="select_promos" id="proceed_to_checkout_btn" class="mybtn btn btn-warning">{{ trans('frontend.store.checkout') }}</a>
                                </div>
                            </div>

                            <div id="step2div" class="row {{ $currentStep != 2 ? 'd-none' : ''}}">
                                <div class="col">
                                    <a wire:click="back" class="mybtn btn btn-secondary">{{ trans('frontend.store.back2cart') }}</a>
                                </div>
                                <div class="col">
                                    {{-- wire:click="select_payment" onclick="payment_page()"  --}}
                                    <button type="submit" {{ App::getLocale() == 'zh-TW' ? '' : 'wire:click=select_payment onclick=payment_page()' }} class="mybtn btn btn-warning">{{ trans('frontend.store.select_payment') }}</button>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </section>
            @endif
        </form>

    {{-- PAYMENT --}}
        <div class="mt-3 {{ $currentStep == 3 ? '' : 'd-none'}}" id="select_payment_page" data-order_id="{{$order_id}}" data-order_number="{{$order_number}}">
            <section class="mt-3">
                <h3 class="cart-title border-below">{{ trans('frontend.store.confirm_order') }}</h3>
                <div class="row section-line">
                    <div class="col-md-6 mt-3">
                        <div class="row pt-3 pb-3">
                            <div class="col-md-6 ">{{ trans('frontend.store.amount') }}</div>
                            <div class="col-md-6 text-right">{{$currencie_code}} {{$currencie_symbol}}{{$total}}</div>
                        </div>
                        <div class="row pt-3 pb-3">
                            <div class="col-md-6 ">{{ trans('frontend.store.shipping_fee') }}</div>
                            <div class="col-md-6 text-right">{{$currencie_code}} {{$currencie_symbol}}{{$shipping_fee}}</div>
                        </div>
                        @if($discount)
                        <div class="row pt-3 pb-3">
                            <div class="col-md-6">{{ trans('frontend.store.full_discount') }}</div>
                            <div class="col-md-6 text-right">- {{$currencie_code}} {{$currencie_symbol}}{{$discount}}</div>
                        </div>
                        @endif
                        @if($promo)
                        <div class="row pt-3 pb-3">
                            <div class="col-md-6">{{ trans('frontend.store.redeem_code_promo') }}</div>
                            <div class="col-md-6 text-right">- {{$currencie_code}} {{$currencie_symbol}}{{$promo}}</div>
                        </div>
                        @endif
                        @if($use_coupon)
                        <div class="row pt-3 pb-3">
                            <div class="col-md-6">{{ trans('frontend.store.coupon') }}</div>
                            <div class="col-md-6 text-right">- {{$currencie_code}} {{$currencie_symbol}}{{ $use_coupon }}</div>
                        </div>
                        @endif
                        @if($use_point)
                        <div class="row pt-3 pb-3">
                            <div class="col-md-6">{{ trans('frontend.store.redeem_member_points') }}</div>
                            <div class="col-md-6 text-right">- {{$currencie_code}} {{$currencie_symbol}}{{ $use_point }}</div>
                        </div>
                        @endif
                        <div class="row pt-3 pb-3">
                            <div class="col-md-6"><h5 class="total">{{ trans('frontend.store.subtotal') }}</h5><span class="text-muted total-note">{{ trans('frontend.store.exclusive_taxes') }}</span></div>
                            <div class="col-md-6 text-right"><h3 class="total-amount"><span id="currency">{{$currencie_code}}</span> {{$currencie_symbol}}<span id="total">{{ $total + $shipping_fee - $discount - $promo - $use_coupon - $use_point }}</span></h3></div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mt-5" id="shipping-details">
                <h3 class="cart-title border-below">{{ trans('frontend.store.shipping_detail') }}</h3>
                <div class="row section-line">
                    <div class="col-md-6 mt-5">
                        <div class="pb-4">
                            <div class="first-row">{{ trans('frontend.store.name') }}</div>
                            <div class="pl-3">{{ session()->get('order.name') ?? $name }}</div>
                        </div>
                        <div class="pb-4">
                            <div class="first-row">Email</div>
                            <div class="pl-3">{{ session()->get('order.email') ?? $email }}</div>
                        </div>
                        <div class="pb-4">
                            <div class="first-row">{{ trans('frontend.store.phone') }}</div>
                            <div class="pl-3">{{ session()->get('order.phone') ?? $phone}}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <div class="pb-4">
                            <div class="first-row">{{ trans('frontend.store.address') }}</div>
                            <div class="pl-3">{{ session()->get('order.address') ?? $address}}</div>
                        </div>
                        <div class="pb-4">
                            <div class="first-row">{{ trans('frontend.store.note') }}</div>
                            <div class="pl-3">{{ session()->get('order.note') }}</div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mt-5 mb-5">
                <h3 class="cart-title border-below">{{ trans('frontend.store.select_payment_method') }}</h3>
                <div class="row section-line">
                    <div class="col-md-3"></div>
                    <div class="col-md-6 mt-5">
                        <div class="row pb-5">
                            @if(App::getLocale() == 'zh-TW')
                            <div class="col">
                                <div class="form-check">
                                    <input name="payment" type="radio" id="credit_card-chk" checked>
                                    <label class="form-check-label mb-3" for="credit_card-chk">{{ trans('frontend.store.credit_card') }}</label>
                                    <img src="../img/credit_card.png" width="200px" alt="">
                                </div>
                            </div>
                            @else
                            <div class="col">
                                <div class="form-check">
                                    <input name="payment" type="radio" id="paypal-chk" checked>
                                    <label class="form-check-label mb-3" for="paypal-chk">Paypal</label>
                                    <img src="../img/paypal.png" class="img-fluid">
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row mt-5">
                            <div class="col">
                                <a wire:click="back" id="back-discount" class="mybtn btn btn-secondary">{{ trans('frontend.store.back') }}</a>
                            </div>
                            <div class="col" id="pay_btn">
                            @if(App::getLocale() == 'zh-TW') <a wire:click="proceed_to_payment" id="proceed_payment_btn" class="mybtn btn btn-warning">{{ trans('frontend.store.proceed2payment') }}</a> @endif
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3"></div>
                </div>
            </section>
        </div>

        {{-- PAYMENT DETAILS IF CREDIT CARD --}}
        <form action="/order/pay/{{ $order_id }}" method="post">
            @csrf
            <div class="{{ $currentStep == 4 ? '' : 'd-none'}}" id="select_payment_page">

                <section class="mt-5">
                    <h3>{{ __('frontend.store.payment_detail') }}</h3>
                    <div class="row section-line">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 mt-5">
                                <div class="row">
                                    <div class="col-md-9 form-group">
                                        <label>{{ __('frontend.store.card_number') }}</label>
                                        <input type="text" name="card_number" class="form-control" inputmode="numeric" pattern="[0-9\s]{16}" onkeypress="if(event.which < 48 || event.which > 57 ) if(event.which != 8) return false;" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>CVC</label>
                                        <input type="text" name="cvc" class="form-control" pattern="[0-9\s]{3}" onkeypress="if(event.which < 48 || event.which > 57 ) if(event.which != 8) return false;" required>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col form-group">
                                        <label>{{ __('frontend.store.holder_name') }}</label>
                                        <input type="text" name="holder-name" class="form-control" required>
                                    </div>
                                </div>

                                <label>{{ __('frontend.store.expiration_date') }}</label>
                                <div class="row ">

                                    <div class="col-md-3 form-group">
                                        <label>{{ __('frontend.store.month') }}</label>
                                        <input type="text" name="month" class="form-control" pattern="[0-9\s]{2}" onkeypress="if(event.which < 48 || event.which > 57 ) if(event.which != 8) return false;" placeholder="MM" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>{{ __('frontend.store.year') }}</label>
                                        <input type="select" name="year" class="form-control" pattern="[0-9\s]{4}" onkeypress="if(event.which < 48 || event.which > 57 ) if(event.which != 8) return false;" placeholder="YYYY" required>
                                    </div>
                                </div>
                                <div class="text-right">
                                {{ __('frontend.store.total') }}: {{$currencie_symbol}} {{ $total + $shipping_fee - $discount - $promo - $use_coupon - $use_point }} {{$currencie_code}}
                                </div>

                            </div>
                            <div class="col-md-3"></div>

                    </div>
                </section>

                <section class="mt-5 mb-5">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col">
                                    <a wire:click="back" onclick="payment_page()" id="back-discount" class="mybtn btn btn-secondary">{{ trans('frontend.store.back') }}</a>
                                </div>
                                <div class="col">
                                    <button type="submit" class="mybtn btn btn-warning">{{ trans('frontend.store.confirm_payment') }}</button>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </section>
            </div>
        </form>

        <!-- Modal -->
        <div wire:ignore.self class="modal fade" id="removeFromCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('frontend.store.remove_item') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                <div class="modal-body">
                        <p>{{ trans('frontend.store.remove_item_content') }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{ trans('frontend.store.close') }}</button>
                        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-dismiss="modal">{{ trans('frontend.store.yes_delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
