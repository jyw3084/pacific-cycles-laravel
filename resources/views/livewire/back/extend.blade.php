<div class="body_container">
    <!-- body nav start -->
    <div class="body_nav">
        <ul>
            <li><a href="/">{{ trans('frontend.Home') }}</a></li>
            <li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
            <li><a href="{{route('mybike')}}">{{ trans('frontend.dashboard.my_bikes') }}</a></li>
            <li>Extent</li>
        </ul>
    </div>
    <!-- body nav start -->
    <div class="heading_title">
        <h2>My Bikes > Extent</h2>
    </div>
    <div class="product_card_area">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="details_title">
                {{ trans('frontend.dashboard.product_number') }}
                </div>
                <div class="details_text">
                    {{$bike->ProductNo}}
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.frame_number') }}
                </div>
                <div class="details_text">
                    {{$bike->BBNo}}
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.product_name') }}
                </div>
                <div class="details_text">
                    {{$bike->BicycleTypeName}}
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.model') }}
                </div>
                <div class="details_text">
                    {{$bike->Model}}
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.color') }}
                </div>
                <div class="details_text">
                    {{$bike->Color}}
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="details_title">
                {{ trans('frontend.dashboard.manufacturer') }}
                </div>
                <div class="details_text">
                    太平洋自行車股份有限公司
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.BuyCompanyName') }}
                </div>
                <div class="details_text">
                    {{$bike->WarrantyCompanyName ?? '　'}}
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.BuyDate') }}
                </div>
                <div class="details_text">
                    {{$bike->BuyDate}}
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.WarrantyCompanyName') }}
                </div>
                <div class="details_text">
                    {{$bike->WarrantyCompanyName ?? '　'}}
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.WarrantyDate') }}
                </div>
                <div class="details_text">
                    {{$bike->WarrantyStartDT2 ?? $bike->WarrantyStartDT}}~{{$bike->WarrantyEndDT2 ?? $bike->WarrantyEndDT}}
                </div>
            </div>
            {{--
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="details_title">
                    延長保固
                </div>
                <div class="details_text">
                    &nbsp;
                </div>
                <div class="details_title">
                    客製車型
                </div>
                <div class="details_text">
                    &nbsp;
                </div>

            </div>
            <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12">
                <div class="card_box">
                    <figure class="card_img">
                        <img src="{{ asset('images') }}/product/bikes_1.jpg" alt="">
                    </figure>
                </div>
            </div>
            --}}
        </div>

    </div>

    <div class="product_card_area">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12">
                <div class="row">
                    <div class="col-md-12">
                        <label id="labelFont" for="usd">{{ trans('frontend.dashboard.select_warranty') }}</label>
                        <select class="form-control outline" wire:change="extend($event.target.value)" >
                        @foreach($warrantis as $k => $v)
                            <option></option>
                            <option value="{{$v->id}}">{{$v->duration}}{{ trans('frontend.dashboard.years') }} {{$v->currency == 1 ? 'USD' : 'TWD'}} $ {{$v->price}}</option>
                        @endforeach
                        </select>
                    </div>
                    <input type="hidden" id="paypalAmount" value="{{$price}}" />
                    <input type="hidden" id="paypalCurrency" value="{{$currency}}" />
                    <input type="hidden" id="ProductNo" value="{{$bike->ProductNo}}" />
                    <input type="hidden" id="year" value="{{$year}}" />
                    <hr class="line">
                    <div class="col-md-12">&nbsp;</div>
                    <div class="col-md-8">
                        <img src="{{ asset('img/paypal.png') }}" alt="">
                    </div>
                    <div class="col-md-12">&nbsp;</div>
                    <hr class="line">
                    @if($price)
                    <div class="total_price col-md-12">
                        <div class="col-md-12 title-right">Total: ${{$price}} {{$currency}}</div>
                    </div>
                    @endif
                    <div class="col-md-12">&nbsp;</div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <button class="confirm_button" id="paypal-btn"></button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <button class="cancel_button">{{ trans('frontend.dashboard.cancel')}}</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>


    <!-- body container end -->
</div>
