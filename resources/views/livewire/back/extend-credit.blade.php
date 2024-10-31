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
                    <div class="col-md-9 col-sm-8">
                        <label class="labelcolor" id="cart_number" for="cart_number">Card number</label>
                        <input id="cart_number" class="form-control outline" type=" text"
                            placeholder="1111  2222  3333  9999" />
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <label class="labelcolor" id="cvc" for="cvc">CVC</label>
                        <input id="cvc" class="form-control outline" type=" text" placeholder="888" />
                    </div>
                    <div class="col-md-12">
                        <label class="labelcolor" for="holder">Holder name</label>
                        <input class="form-control outline" type="text" placeholder="Anne Smith" />
                    </div>
                    <div class="col-md-12">
                    <label class="labelcolor" for="expiration_date">Expiration date</label>
                    </div>
                    <div class="col-md-2 col-sm-3">
                        <select class="form-control outline" name="">
                            <option value="">01</option>
                            <option value="">02</option>
                            <option value="">03</option>
                            <option value="">04</option>
                            <option value="">05</option>
                            <option value="">06</option>
                            <option value="">07</option>
                            <option value="">08</option>
                            <option value="">09</option>
                            <option value="">10</option>
                            <option value="">11</option>
                            <option value="">12</option>
                            <option value="">13</option>
                            <option value="">14</option>
                            <option value="">15</option>
                        </select>
                    </div>
                    <div class="col-md-7 col-sm-9">
                        <select class="form-control outline" name="">
                            <option value="">January</option>
                            <option value="">February</option>
                            <option value="">March</option>
                            <option value="">April</option>
                            <option value="">June</option>
                            <option value="">July</option>
                            <option value="">August</option>
                            <option value="">September</option>
                            <option value="">October</option>
                            <option value="">November</option>
                            <option value="">December</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <select class="form-control outline" name="">
                            <option value="">2010</option>
                            <option value="">2011</option>
                            <option value="">2012</option>
                            <option value="">2013</option>
                            <option value="">2014</option>
                            <option value="">2015</option>
                            <option value="">2016</option>
                            <option value="">2017</option>
                            <option value="">2018</option>
                            <option value="">2019</option>
                            <option value="">2020</option>
                            <option value="">2021</option>
                        </select>
                    </div>
                    <div class="total_price col-md-12">
                        <div class="col-md-12 title-right">Total: $189 USD</div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <button class="confirm_button">Save</button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <button class="cancel_button">Cancel</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>


    <!-- body container end -->
</div>
