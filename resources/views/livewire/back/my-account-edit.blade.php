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
			<form wire:submit.prevent="saveAccount">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
						<div>
							<label for="c_name">{{ trans('frontend.store.name') }}</label>
							<input  type="text" wire:model="name" id="c_name" class="form-control" placeholder="Alice-Wang">
							@error('name') <span class="text-danger error">{{ $message }}</span> @enderror
						</div>
						<div>
							<label for="c_email">Email</label>
							<input  type="email" wire:model="email" id="c_email" class="form-control" placeholder ="alice@email.com">
							@error('email') <span class="text-danger error">{{ $message }}</span> @enderror
						</div>
						<div>
							<label for="c_phone">{{ trans('frontend.store.phone') }}</label>
							<input  type="text" wire:model="phone" id="c_phone" class="form-control" placeholder="01928323723">
							@error('phone') <span class="text-danger error">{{ $message }}</span> @enderror
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
						<label for="c_addredd">{{ trans('frontend.store.address') }}</label>
						<input type="text" wire:model="address" id="c_addredd" class="form-control" placeholder="no.123, example st., city, country">
						@error('address') <span class="text-danger error">{{ $message }}</span> @enderror
					</div>
					<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
						&nbsp;
					</div>
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
