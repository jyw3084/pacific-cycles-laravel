<div class="body_container">
    <!-- body nav start -->
    <div class="body_nav">
        <ul>
            <li><a href="/">{{ trans('frontend.Home') }}</a></li>
            <li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
            <li><a href="{{route('mybike')}}">{{ trans('frontend.dashboard.my_bikes') }}</a></li>
            <li>{{ trans('frontend.dashboard.extent') }}</li>
        </ul>
    </div>
    <!-- body nav start -->
    <div class="heading_title">
        <h2>{{ trans('frontend.dashboard.my_bikes') }} > {{ trans('frontend.dashboard.extent') }}</h2>
    </div>
    <div class="product_card_area">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="details_title">
                    產品編號
                </div>
                <div class="details_text">
                    10003585
                </div>
                <div class="details_title">
                    車架編號
                </div>
                <div class="details_text">
                    6PG16010616
                </div>
                <div class="details_title">
                    產品名稱
                </div>
                <div class="details_text">
                    BIRDY
                </div>
                <div class="details_title">
                    產品車型
                </div>
                <div class="details_text">
                    NEW BIRDY Standard 9 Speed
                </div>
                <div class="details_title">
                    產品顏色
                </div>
                <div class="details_text">
                    CHARCOAL / 灰
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="details_title">
                    製造廠商
                </div>
                <div class="details_text">
                    太平洋自行車股份有限公司
                </div>
                <div class="details_title">
                    購買店家
                </div>
                <div class="details_text">
                    E-WALKER (SHOP)
                </div>
                <div class="details_title">
                    購買日期
                </div>
                <div class="details_text">
                    2021/07/01 02:00
                </div>
                <div class="details_title">
                    保固店家
                </div>
                <div class="details_text">
                    E-WALKER (SHOP)
                </div>
                <div class="details_title">
                    保固期間
                </div>
                <div class="details_text">
                    2021/07/01~2021/10/01
                </div>
            </div>
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

        </div>

    </div>

    <div class="product_card_area">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12">
                <div class="row">
                    <div class="col-md-12">
                        <label id="labelFont" for="usd">您可購買延長保固：選擇您要購買延長的年限</label>
                        <select class="form-control outline" name="" id="usd">
                            <option value="">2年 2021/7/1~2023/10/1 USD$ 199</option>
                            <option value="">2年 2021/7/1~2023/10/1 USD$ 188</option>
                            <option value="">2年 2021/7/1~2023/10/1 USD$ 155</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <input id="check1" class="box" type="checkbox">
                        <label for="check1"> 紅利折抵</label>
                    </div>
                    <div class="col-md-12">
                        <span class="points"><span class=" text-style-1">1000</span> point(s)</span>
                    </div>
                    <div class="col-md-12">
                        <input id="check2" class="box" type="checkbox">
                        <label for="check2"> 折價劵</label>
                    </div>
                    <div class="col-md-12">
                        <select class="form-control outline" name="" id="coupon">
                            <option value="">Coupon 1</option>
                            <option value="">Coupon 2</option>
                            <option value="">Coupon 3</option>
                        </select>
                    </div>
                    <hr class="line">
                    <div class="col-md-7 col-sm-8 title-left"> Amount </div>
                    <div class="col-md-5 col-sm-4 title-right">$199 USD</div>
                    <div class="col-md-7 col-sm-8 title-left">Redeem member points</div>
                    <div class="col-md-5 col-sm-4 title-right">$5 USD</div>
                    <div class="col-md-7 col-sm-8 title-left">Redeem code discount</div>
                    <div class="col-md-5 col-sm-4 title-right">$5 USD</div>
                    <div class="total_price">
                        <div class="col-md-12 title-right">Total: $189 USD</div>
                    </div>
                    <hr class="line">
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12"></div>
            <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12"></div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="row">

                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12 ">

                        <div class="col-md-12 border border-dark">
                            
                            <center>
                                <img src="{{ asset('backend/images/icon') }}/error@2x.png"
                                    style="height: 105px; width:105px; margin-top:45px;" alt="">
                            </center>

                            <div>
                                <p class=" text-center p-5">Something went wrong with <br> your payment. Please try
                                    again <br> or contact us for support.</p>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12 ">
                        <div class="right-text">付款成功即跳回產品履歷</div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12"></div>
                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12">
                        <div class="row">
                            {{-- <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12"></div> --}}
                            <div class="col-xl-8 offset-2 col-lg-8 offset-2 col-md-8 offset-2 col-sm-8 offset-2">
                                <button class="confirm_button">Confirm</button>
                            </div>
                            {{-- <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12"></div> --}}
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <!-- body container end -->
</div>
