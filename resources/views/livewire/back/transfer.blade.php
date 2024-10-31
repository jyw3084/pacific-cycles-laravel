<div class="body_container">
    <!-- body nav start -->
    <div class="body_nav">
        <ul>
            <li><a href="/">{{ trans('frontend.Home') }}</a></li>
            <li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
            <li><a href="{{route('mybike')}}">{{ trans('frontend.dashboard.my_bikes') }}</a></li>
            <li>{{ trans('frontend.dashboard.transfer') }}</li>
        </ul>
    </div>
    <!-- body nav start -->
    <div class="heading_title">
        <h2>{{ trans('frontend.dashboard.my_bikes') }} > {{ trans('frontend.dashboard.transfer') }}</h2>
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
                {{ trans('frontend.dashboard.type') }}
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
                    {{$bike->BuyCompanyName}}
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
                    {{$bike->WarrantyCompanyName}}
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.WarrantyDate') }}
                </div>
                <div class="details_text">
                    {{$bike->WarrantyStartDT.' ~ '.$bike->WarrantyEndDT}}
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="details_title">
                {{ trans('frontend.dashboard.extent') }}
                </div>
                <div class="details_text">
                    &nbsp;
                </div>
                <div class="details_title">
                {{ trans('frontend.dashboard.Customization') }}
                </div>
                <div class="details_text">
                    &nbsp;
                </div>

            </div>
            @if(json_decode($bike->BikeImages))
            <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12">
                <div class="card_box">
                    <figure class="card_img">
                        <img src="{{ asset(json_decode($bike->BikeImages)[1]) }}" alt="">
                    </figure>
                </div>
            </div>
            @endif

        </div>

    </div>

    <div class="product_card_area">
        <form wire:submit.prevent="ProductTransfer">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-7 col-sm-12">
                    <label for="email">請輸入欲移轉之使用者(E-mail/手機)</label>
                    <input type="text" wire:model="account" class="form-control">
                    @error('account') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 pt-4">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-10 mx-4 mx-sm-0">
                        <button type="button" class="confirm_button" wire:click="bring">{{ trans('frontend.dashboard.bring') }}</button>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-1 col-sm-12"></div>

                <div class="col-xl-4 col-lg-4 col-md-7 col-sm-12">
                    <label for="name">姓名</label>
                    <input type="text" wire:model="name" class="form-control" disabled>
                    @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12"></div>
                <div class="col-xl-4 col-lg-4 col-md-1 col-sm-12"></div>

                <div class="col-xl-4 col-lg-4 col-md-7 col-sm-12">
                    <label for="comment">備註</label>
                    <textarea type="text" wire:model="memo" class="form-control outline"></textarea>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12"></div>
                <div class="col-xl-4 col-lg-4 col-md-1 col-sm-12"></div>

                <div class="col-xl-4 col-lg-4 col-md-7 col-sm-12">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-10 mx-4 mx-sm-0">
                            <button type="submit" class="confirm_button">{{ trans('frontend.dashboard.confirm') }}</button>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-10 mx-4 mx-sm-0">
                            <button type="button" class="cancel_button">{{ trans('frontend.dashboard.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    


    <!-- body container end -->
</div>
