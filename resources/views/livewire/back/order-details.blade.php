<div class="body_container">
  <div class="body_nav">
    <ul>
      <li><a href="/">{{ trans('frontend.Home') }}</a></li>
      <li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
      <li>{{ trans('frontend.dashboard.order_detail') }}</li>
    </ul>
  </div>
  <div class="heading_title">
    <h2>{{ trans('frontend.dashboard.order_detail') }}</h2>
  </div>
  <style>
    .order {
      font-family: Montserrat;
      font-size: 17px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: normal;
      color: #000;
    }

    .order_placed {
      font-family: Montserrat;
      font-size: 14px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: normal;
      color: #6dd400;
    }

    .order_date {

      font-size: 14px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.43;
      letter-spacing: normal;
      color: #8b8b8f;
    }

    .order_add_title {

      font-size: 14px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.43;
      letter-spacing: normal;
      color: #8b8b8f;
    }

    .order_add {

      font-family: Montserrat;
      font-size: 14px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: normal;
      color: #000;
    }

    .order_count {
      font-family: Montserrat;
      font-size: 16px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: normal;
      color: #000;
    }

    .order_details_img_cart {
      width: 129px;
      height: 94px;
      background-color: #fff;
    }
  </style>
  <div class="row">
    <div class="col-xl-8 col-xl-offset-4 col-lg-8 col-lg-offset-4 col-md-12 col-sm-12">
      <div class="product_card_area">
        <div class="row">
          <div class="col-xl-6 col-sm-12 mb-3">
            <span class="order">{{ trans('frontend.dashboard.order_no') }} {{$order->number}}</span><br>
            <?php
              switch($order->status)
              {
                case 0:
                    $text = 'success';
                    $status = trans('frontend.dashboard.placed');
                  break;
                case 1:
                    $text = 'delivering';
                    $status = trans('frontend.dashboard.packed');
                  break;
                case 2:
                    $text = 'delivering';
                    $status = trans('frontend.dashboard.shipped');
                  break;
                case 3:
                    $text = 'delivering';
                    $status = trans('frontend.dashboard.delivering');
                  break;
                case 4:
                    $text = 'delivering';
                    $status = trans('frontend.dashboard.delivered');
                  break;
                case 5:
                    $text = 'canceled';
                    $status = trans('frontend.dashboard.canceled');
                  break;
              }
            ?>
            <span class="text-{{ $text }}">{{ trans('frontend.dashboard.order') }} {{ $status }}</span>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
            <span class="order_date">{{ date('d,M,Y H:i', strtotime($order->created_at)) }}</span><br>
            <span class="order_date">{{ trans('frontend.dashboard.item') }} <b class="order_count mx-2">{{$order->order_detail->count()}}</b></span>
            <span class="order_date ml-4">{{ trans('frontend.dashboard.total') }} <b class="order_count ml-2">${{number_format($order->total)}}</b></span>
          </div>
          @if($order->status == 0)
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 offset-md-3">
            <button class="cancel_button order_cancel" data-id="{{$order->id}}">{{ trans('frontend.dashboard.cancel') }}</button>
          </div>
          <div id="do_cancel_{{$order->id}}" wire:click="cancel({{$order->id}})"></div>
          @endif
        </div>
        <div class="row">
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <span class="order">{{ trans('frontend.dashboard.billing') }}</span>
            <div class="my-3">
              <div>
                <span class="order_add_title">{{ trans('frontend.store.name') }}: <span class="order_add">{{$order->name}}</span>
                </span>
              </div>
              <div class="my-3">
                <span class="order_add_title">{{ trans('frontend.store.address') }}: <span class="order_add">{{$order->address}}</span>
                </span><br>
                </span>
              </div>
              <div>
                <span class="order_add_title">{{ trans('frontend.store.phone') }}: <span class="order_add">{{$order->phone}}</span>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card_table table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>{{ trans('frontend.dashboard.number') }}</th>
                    <th></th>
                    <th>{{ trans('frontend.store.product') }}</th>
                    <th>{{ trans('frontend.dashboard.color') }}</th>
                    <th>{{ trans('frontend.dashboard.product_number') }}</th>
                    <th>{{ trans('frontend.dashboard.qty') }}</th>
                    <th>{{ trans('frontend.dashboard.price') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->order_detail as $k => $v)
                  <?php
                    switch($v->product_type)
                    {
                      case 1:
                        $image = $v->package->images[0] ?? '';
                        $title = $v->package->name;
                        $color = '';
                        break;
                      case 2:
                        $image = $v->product->images[0] ?? '';
                        $title = $v->product->product_name;
                        $color = $v->product->color;
                        break;
                    }
                  ?>
                  <tr>
                    <td>{{$k+1}}</td>
                    <td>
                      <div class="order_details_img_cart">
                        <img src="{{Storage::url($image)}}"
                          style="width: 93px; height: 58px; margin-top:18px; margin-left:18px;" alt="">
                      </div>
                    </td>
                    <td>{{$title}}</td>
                    <td>{{$color}}</td>
                    <td>{{$v->product->product_code}}</td>
                    <td>{{$v->quantity}}</td>
                    <td>${{number_format($v->price)}}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="6" class="text-right">{{ trans('frontend.dashboard.total') }}</th>
                    <th>${{number_format($order->total)}}</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <div class="messages w-100 mb-4 mt-4">
          @foreach($order->message as $k => $v)
            <div class="row justify-content-start no-gutters">
                <div class="user col-md-7 col-10 bg-white p-4">{!! nl2br($v->message) !!}</div>
            </div>
            <div class="row justify-content-end no-gutters">
                <div class="company col-md-7 col-10 p-4" style="text-align:right;">{!! nl2br($v->response) !!}</div>
            </div>
          @endforeach
        </div>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <form wire:submit.prevent="send">
              <textarea wire:model="content" class="form-control" id="" cols="30" rows="10" placeholder="{{ trans('frontend.dashboard.message') }}"></textarea>
							@error('content') <span class="text-danger error">{{ $message }}</span> @enderror
              <button type="submit" class="btn btn-black btn-lg btn-block">{{ trans('frontend.dashboard.send_us') }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
