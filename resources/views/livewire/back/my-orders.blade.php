<main role="main">

  <div class="contain mb-5">

    <!-- 內容開頭 -->

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">{{ trans('frontend.Home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ trans('frontend.dashboard.my_order') }}</li>
      </ol>
    </nav>

    <h2 class="title PingFang">{{ trans('frontend.dashboard.my_order') }}</h2>

    <div class="row no-gutters mb-4">
    @foreach($orders as $k => $v)
      <div class="col-lg-6 bg-light orders">
        <div class="row p-4">
          <div class="col-12">
            <div class="text w-100">{{ date('d,M,Y H:i', strtotime($v->created_at)) }}</div>
            <h3>{{ trans('frontend.dashboard.order_no') }} {{$v->number}}</h3>
          </div>
          <div class="col-xl-6">
            <?php
              switch($v->status)
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
                    $text = 'canceled';
                    $status = trans('frontend.dashboard.canceled');
                  break;
                case 4:
                    $text = 'canceled';
                    $status = trans('frontend.dashboard.canceled');
                  break;
              }
            ?>
            <div class="type text-{{ $text }}">{{ trans('frontend.dashboard.order') }} {{ $status }}</div>
            <div class="img_list">
              @foreach($v->order_detail as $detail)
              <?php
                switch($detail->product_type)
                {
                  case 1:
                    $image = $detail->package->images[0] ?? '';
                    $title = $detail->package->name;
                    break;
                  case 2:
                    $image = $detail->product->images[0] ?? '';
                    $title = $detail->product->product_name;
                    break;
                }
              ?>
              <div class="img_item">
                <img class="img-fluid" src="{{Storage::url($image)}}" alt="">
                <h5>{{$title}}</h5>
              </div>
              @endforeach
              @if(count($v->order_detail) > 2)
              <div class="img_item more">+2 {{ trans('frontend.dashboard.more') }}</div>
              @endif
            </div>
          </div>
          <div class="col-xl-6">
            <div class="text">{{ trans('frontend.dashboard.item') }}</div>
            <div class="value">{{$v->order_detail->count()}}</div>
            <div class="text">{{ trans('frontend.dashboard.total') }}</div>
            <div class="value">${{number_format($v->total)}}</div>
            <div class="form-row">
              @if($v->status == 0)
              <div class="form-group col-md-12 mt-4">
                <button class="btn btn-warning-outline btn-block order_cancel" data-id="{{$v->id}}">{{ trans('frontend.dashboard.cancel') }}</button>
              </div>
              <div id="do_cancel_{{$v->id}}" wire:click="cancel({{$v->id}})"></div>
              @endif
              <div class="form-group col-md-12">
                <button class="btn btn-link btn-block" onclick="window.location.href = '/order-details/{{$v->number}}';">{{ trans('frontend.dashboard.view_detail') }}</button>
              </div>
              <div class="form-group col-md-12">
                <button class="btn btn-link btn-block" onclick="window.location.href = '/order-details/{{$v->number}}?d=message';">{{ trans('frontend.dashboard.send_msg') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach


    </div>

    <!-- 內容結尾 -->

  </div>
  <!-- /.container -->
</main>
