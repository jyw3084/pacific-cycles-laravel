    <div class="body_container">
        <!-- body nav start -->
        <div class="body_nav">
            <ul>
                <li><a href="/">{{ trans('frontend.Home') }}</a></li>
                <li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
                <li><a href="{{ route('mybike') }}">{{ trans('frontend.dashboard.my_bikes') }}</a></li>
                <li>{{ trans('frontend.dashboard.RegisterBike') }}</li>
            </ul>
        </div>
        <!-- body nav start -->
        <div class="heading_title">
            <h2>{{ trans('frontend.dashboard.my_bikes') }} > {{ trans('frontend.dashboard.RegisterBike') }}</h2>
        </div>
        <div class="qr_code_title">
            <a class="active" href="{{ URL::to('register-bike-with-qr') }}">{{ trans('frontend.dashboard.WithQR') }}</a>  <a class="" href="{{ URL::to('register-bike-without-qr') }}">{{ trans('frontend.dashboard.WithoutQR') }}</a>
        </div>
        <div class="product_card_area">
            <form wire:submit.prevent="addBike">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-7 col-sm-12 form-group">
                        <label for="c_name"> {{ trans('frontend.dashboard.product_number') }}</label>
                        <input type="text" wire:model="ProductNo" id="c_name" class="form-control" placeholder="10003585">
                        @error('ProductNo') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 form-group">

                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-1 col-sm-12 form-group">

                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-7 col-sm-12 form-group">
                        <label for="c_name"> {{ trans('frontend.dashboard.frame_number') }}</label>
                        <input type="text" wire:model="BBNo" id="c_name" class="form-control" placeholder="10616">
                        @error('BBNo') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    @if(!$add)
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-6 ">
                                <button type="submit" class="verify_button">{{ trans('frontend.dashboard.comfirm') }}</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
        @if($add)
		<div class="product_card_area">
            <form wire:submit.prevent="saveBike">
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
						<div class="form-group">
                            <label for="c_addredd"> {{ trans('frontend.dashboard.buy_date') }}</label>
                            <input type="date" wire:model="BuyDate" class="form-control" placeholder="">
                            @error('BuyDate') <span class="text-danger error">{{ $message }}</span> @enderror
						</div>
						<div class="form-group">
                            <label for="c_phone">{{ trans('frontend.dashboard.BuyAreaName') }}</label>
                            <select class="form-control" wire:model="area" wire:change="changeArea()">
                                <option value=""></option>
                                @foreach($areas as $k => $v)
                                <option value="{{$v->AreaNo}}-{{ $lang == 'en' ? $v->AreaEngName : $v->AreaCnName}}"> {{ $lang == 'en' ? $v->AreaEngName : $v->AreaCnName}}</option>
                                @endforeach
                            </select>
                            @error('area') <span class="text-danger error">{{ $message }}</span> @enderror
						</div>
						<div class="form-group">
                            <label for="c_phone"> {{ trans('frontend.dashboard.BuyCompany') }}</label>
                            <select class="form-control" wire:model="BuyCompanyName">
                                <option value=""></option>
                                @foreach($store as $k => $v)
                                <option value="{{ $v->CompanyNo }}">{{ $v->CompanyName }}</option>
                                @endforeach
                            </select>
                            @error('BuyCompanyName') <span class="text-danger error">{{ $message }}</span> @enderror
					    </div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 form-group">
						&nbsp;
					</div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 form-group">
                        <label for="c_phone"> {{ trans('frontend.dashboard.UploadPurchaseCertificates') }}</label>
                        <input  type="file" wire:model="certificate" id="certificate" class="form-control btn btn-primary">
                        @error('certificate') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 form-group">
                        <label for="c_phone"> {{ trans('frontend.dashboard.UploadBike') }}</label>
                        <input  type="file" wire:model="bike" id="bike" class="form-control btn btn-primary">
                        @error('bike') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 form-group">
                        <label for="c_phone"> {{ trans('frontend.dashboard.UploadFrame') }}</label>
                        <input  type="file" wire:model="frame" id="frame" class="form-control btn btn-primary">
                        @error('frame') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12">
                        <div class="card_box">
                            <figure class="card_img" id="show_certificate">
                            @if($certificate)
                                <img src="{{ $certificate->temporaryUrl() }}">
                            @else
                                <img src="{{asset('/img/dashboard/Certificate.jpg')}}">
                            @endif
                            </figure>
                            <p>{{ trans('frontend.dashboard.UploadPurchaseCertificatesNote') }}</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12">
                        <div class="card_box">
                            <figure class="card_img" id="show_bike">
                            @if($bike)
                                <img src="{{ $bike->temporaryUrl() }}">
                            @else
                                <img src="{{asset('/img/dashboard/RightSideFullView.jpg')}}">
                            @endif
                            </figure>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12">
                        <div class="card_box">
                            <figure class="card_img" id="show_frame">
                            @if($frame)
                                <img src="{{ $frame->temporaryUrl() }}">
                            @else
                                <img src="{{asset('/img/dashboard/BB-location.jpg')}}">
                            @endif
                            </figure>
                        </div>
                    </div>
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
						<div class="row">
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 ">
								 <button type="submit" class="confirm_button">{{ trans('frontend.dashboard.save') }}</button>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 ">
							<button type="button" class="cancel_button" onclick="location.href = '/my-bike';">{{ trans('frontend.dashboard.cancel') }}</button>
							</div>
						</div>
					</div>
				 </div>
			 </form>
		</div>
        @endif
    </div>
