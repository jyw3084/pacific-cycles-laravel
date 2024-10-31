<div>
	<div class="body_container">
      <!-- body nav start -->
      <div class="body_nav">
         <ul>
			<li><a href="/">{{ trans('frontend.Home') }}</a></li>
			<li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
			<li>{{ trans('frontend.dashboard.my_account_edit') }}</li>
         </ul>
      </div>
      <!-- body nav start -->
      <div class="heading_title">
         <h2>{{ trans('frontend.dashboard.my_account_edit') }}</h2>
      </div>
        <div class="product_card_area">
			<form  wire:submit.prevent="changePwd">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
						<div>
							<label for="password">{{ trans('frontend.dashboard.pwd') }}</label>
							<input  type="password" id="password" wire:model="password" class="form-control" placeholder="********">
							@error('password') <span class="text-danger error">{{ $message }}</span> @enderror
						</div>

						<div>
							<label for="password_confirm">{{ trans('frontend.dashboard.confirm_pwd') }}</label>
							<input type="password" id="password_confirm" wire:model="password_confirm" class="form-control" placeholder="********">
							@error('password_confirm') <span class="text-danger error">{{ $message }}</span> @enderror
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12"> </div>
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12"></div>
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
						<div class="row">
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 ">
								 <button type="submit" class="confirm_button">{{ trans('frontend.dashboard.save') }}</button>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 ">
								<button class="cancel_button" onclick="window.history.go(-1); return false;">{{ trans('frontend.dashboard.cancel') }}</button>
							</div>
						</div>
					</div>
				</div>
			</form>
        </div>
    </div>
</div>
