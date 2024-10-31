<div>
<div class="news-header">
  <div class="overlay"></div>
  <div class="container">
    <div class="news-header-container">
      <h1>{{ trans('frontend.news.title') }}</h1>
    </div>
  </div>
</div>
<nav aria-label="breadcrumb" class="breadcrumb">
  <div class="container">
    <ol class="d-flex">
      <li class="breadcrumb-item"><a href="/">{{ trans('frontend.Home') }}</a></li>
      <li class="breadcrumb-item"><a href="/news-events">{{ trans('frontend.news.title') }}</a></li>
      <li class="breadcrumb-item breadcrumb-item-active">{{ trans('frontend.news.event_detail') }}</li>
    </ol>
  </div>
</nav>
<div class="container">
  <div class="row">
    <div class="col-md-12  mb-5">
      <div class="row">
        <div class="col-md-6 p-5">
          <span class="headings"> {{$event->title[$lang]}} </span>
          <br>
          <form class="mt-5" method="POST" action='{{URL::to("event/signup")}}'>
            @csrf
            <input name="event_id" id="event_id" type="hidden" value="{{$event->id}}">
            <div class="mb-3 row">
              @foreach((object)$event->fields as $k => $v)
              <?php
                $name = 'name_'.$lang;
                $required = !empty($v['required']) ? 'required': '';
                $key = $v['key'];
                $type = '';
                if(!empty($v['type']))
                {
                  switch($v['type'])
                  {
                    case 1:
                      $type = 'text';
                      break;
                    case 2:
                      $type = 'email';
                      break;
                    case 3:
                      $type = 'number';
                      break;
                    case 4:
                      $type = 'radio';
                      break;
                    case 5:
                      $type = 'checkbox';
                      break;
                    case 6:
                      $type = 'date';
                      break;
                  }
                }
                switch($v['category'])
                {
                  case 1:
                    $input = '<input wire:model="post.'.$v['key'].'" name="'.$v['key'].'" id="input_'.$k.'" type="'.$type.'" class="form-control custom-form-control" '.$required.'>';
                    break;
                  case 2:
                    $input = '<textarea wire:model="post.'.$v['key'].'" name="'.$v['key'].'" id="input_'.$k.'" class="form-control custom-form-control" rows="10" '.$required.'></textarea>';
                    break;
                  case 3:
                    $item = '<option></option>';
                    if(!empty($v['options']))
                    {
                      $options = is_array($v['options']) ? $v['options'] : explode(',', $v['options']);
                      foreach($options as $option)
                      {
                          $item .= '<option value="'.$option.'">'.$option.'</option>';
                      }
                    }
                    $input = '<select wire:model="post.'.$v['key'].'" name="'.$v['key'].'" id="input_'.$k.'" class="form-control" '.$required.'>'.$item.'</select>';
                    break;
                }
              ?>
              <div class="col-sm-{{ $v['col'] }} pt-3">
                <label for="input_{{ $k }}">{{ $v[$name] }}</label>
                {!! $input !!}
              </div>
              @endforeach
            </div>

            @if($event->price > 0)
            <div class="col-sm-12">
              <label for="" class="mb-5">{{ trans('frontend.news.payment') }}</label>

              <div class="row">
                <div class="col-sm-4">
                  <div class="card payment-choices">
                    <input  class="form-control custom-form-control custom-radio mx-auto"
                      type="radio" name="payment" id="cc" value="線上刷卡">
                    <label  for="cc">{{ trans('frontend.store.credit_card') }}</label>
                  </div>
                </div>
                <div class="col-sm-4">

                  <div class="card payment-choices">
                    <input class="form-control custom-form-control custom-radio mx-auto" type="radio" name="payment"
                      id="paypal" value="paypal">
                    <label for="paypal">Paypal</label>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="card payment-choices">
                    <input class="form-control custom-form-control custom-radio mx-auto" type="radio" name="payment"
                      id="bank" value="銀行匯款">
                    <label for="bank">{{ trans('frontend.store.bank') }}</label>
                  </div>
                </div>
              </div>

            </div>
            <div class="d-none" id='cc-detail'>
              <div class="mb-3 row mt-5">
                <div class="col-sm-9">
                  <label for="">{{ __('frontend.store.card_number') }}</label>
                  <input type="text" name="card_number" class="form-control custom-form-control" maxlength="16" required>
                </div>
                <div class="col-sm-3">
                  <label for="">CVC</label>
                  <input type="text" name="cvc" class="form-control custom-form-control" maxlength="3" required>
                </div>
              </div>
              <div class="mb-3 row">
                <div class="col-sm-12">
                  <label for="">{{ __('frontend.store.holder_name') }}</label>
                  <input type="text" name="holder-name" class="form-control custom-form-control" required>
                </div>
              </div>
              <label>{{ __('frontend.store.expiration_date') }}</label>
              <div class="mb-3 row">
                <div class="col-sm-6">
                  <label>{{ __('frontend.store.month') }}</label>
                  <input type="text" name="month" class="form-control custom-form-control" maxlength="2" placeholder="MM" required>
                </div>
                <div class="col-sm-6">
                  <label>{{ __('frontend.store.year') }}</label>
                  <input type="select" name="year" class="form-control custom-form-control" maxlength="4" placeholder="YYYY" required>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-sm-12 register d-none" id="complete-btn">
                <div>
                  <button type="submit" class="btn btn-primary mt-5">{{ __('frontend.news.complate') }}</button>
                </div>
              </div>

              <div class="col-sm-12 register d-none" id="complete-btn-bank">
                <div class="bank-payment-container">
                  {!! __('frontend.store.bank_content') !!}

                  <button type="submit" class="btn btn-primary mt-5">{{ __('frontend.news.complate') }}</button>

                </div>
              </div>
              <div class="col-sm-12 register d-none" id="complete-btn-paypal">
                <div id="paypal-btn">
                  <input type="hidden" id="paypalAmount" value="{{ $event->price ?? 0 }}" />
                  <input type="hidden" id="paypalCurrency" value="{{ $event->currency ?? 'TWD'}}" />
                  <!-- <button type="button" class="btn btn-primary mt-5" >Complete Paypal Payment</button> -->
                </div>
              </div>
            </div>
            @else
            <div class="col-sm-12 register d-none" id="complete-btn-paypal">
                <div id="paypal-btn">
                  <input type="hidden" id="paypalAmount" value="{{ $event->price ?? 0 }}" />
                  <input type="hidden" id="paypalCurrency" value="{{ $event->currency ?? 'TWD'}}" />
                  <!-- <button type="button" class="btn btn-primary mt-5" >Complete Paypal Payment</button> -->
                </div>
              </div>
              <button type="submit" id="register-event-btn" class="btn btn-primary mt-5 mb-5">{{ trans('frontend.news.register') }}</button>
            @endif
          </form>
        </div>
        <div class="col-md-6 p-5">
          <div class="card" style="width: 100%; border: none;">
            <img src="{{Storage::url($event->image)}}" class="card-img-top" alt="...">
            <div class="card-body">
              <!--<span class="md-text ">{{ $event->created_at }}</span>-->
              <!--<br>
              <h5 class="card-title pc-regular-text mt-5"></h5>
              <br>-->
              <p class="headings">{{ trans('frontend.news.price') }}: TWD{{ $event->price == 0 ? trans('frontend.news.free') :$event->price }}</p>
            </div>

          </div>
        </div>

      </div>
    </div>

    <br>
    <br>
    <br>
    <br>


  </div>
</div>
</div>
