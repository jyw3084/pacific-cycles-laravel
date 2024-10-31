<main role="main" id="mybikes">
  <div class="contain mb-5 max-1400">

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">{{ trans('frontend.Home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ trans('frontend.dashboard.my_bikes') }}</li>
      </ol>
    </nav>

    @if($my_wish_products->count() > 0)
    <h2 class="title PingFang">{{ trans('frontend.dashboard.favorite') }}</h2>

    <div class="row no-gutters justify-content bg-light p-4 flex-row mb-4">
      <h2 class="title2">{{ trans('frontend.dashboard.bikes') }}</h2>
      @foreach($my_wish_products as $k => $v)
      <div class="col-xl-2 col-lg-3 col-md-4 p-1">
        <div class="product bg-white">
          <div class="delete" wire:click="deleteProduct({{$v->id}})"></div>
          <?php $image = $v->images[0] ?? ''; ?>
          <img class="img-fluid" src="{{Storage::url($image)}}" alt="">
          <h5>{{ $v->category->name ?? ''}} - {{ $v->bike_model->bike_model ?? ''}}</h5>
          <h3>{{ $v->product_name }}</h3>
          <a class="view" href="/store/products/{{ $v->product_code }}" target="_blank">{{ trans('frontend.dashboard.view') }}</a>
        </div>
      </div>
      @endforeach
      @if(count($products) > $productPerPage)
      <div class="vmore w-100 text-center" wire:click="viewMoreBike">{{ trans('frontend.dashboard.view_more') }}</div>
      @endif
    </div>
    @endif

    @if(count($accessories) > 0)
    <div class="row no-gutters justify-content bg-light p-4 flex-row mb-4">
      <h2 class="title2">{{ trans('frontend.dashboard.accessories') }}</h2>
      @foreach($my_wish_accessories as $k => $v)
      <div class="col-xl-2 col-lg-3 col-md-4 p-1">
        <div class="product bg-white">
          <div class="delete" wire:click="deleteAccessory({{$v->id}})"></div>
          <img class="img-fluid" src="{{Storage::url($v->images[0])}}" alt="">
          <h5>{{ $v->category->name ?? ''}} - {{ $v->bike_model->bike_model ?? ''}}</h5>
          <h3>{{ $v->product_name }}</h3>
          <a class="view" href="/store/products/{{ $v->product_code }}" target="_blank">{{ trans('frontend.dashboard.view') }}</a>
        </div>
      </div>
      @endforeach

      @if(count($accessories) > $accessoryPerPage)
      <div class="vmore w-100 text-center" wire:click="viewMoreAccessories">{{ trans('frontend.dashboard.view_more') }}</div>
      @endif

    </div>
    @endif

    @if(count($transfer) > 0)
    <h2 class="title PingFang">{{ trans('frontend.dashboard.transferring') }}</h2>

    <div class="table-responsive-xl table-vw border border-secondary scrollbar-outer">
      <div class="grid-striped w900">
        <div class="row no-gutters flex-row font-weight-bold thead-dark text-white">
          <div class="col mincol">{{ trans('frontend.dashboard.number') }}</div>
          <div class="col">{{ trans('frontend.dashboard.type') }}</div>
          <div class="col">{{ trans('frontend.dashboard.model') }}</div>
          <div class="col">{{ trans('frontend.dashboard.color') }}</div>
          <div class="col">{{ trans('frontend.dashboard.product_number') }}</div>
          <div class="col">{{ trans('frontend.dashboard.transfer').' '.trans('frontend.dashboard.date') }}</div>
          <div class="col">{{ trans('frontend.dashboard.warranty') }}</div>
          <div class="text-nowrap col">{{ trans('frontend.dashboard.transfers_account') }}</div>
          <div class="text-nowrap col">{{ trans('frontend.dashboard.transfers_name') }}</div>
          <div class="col"></div>
        </div>
        @foreach($transfer as $k => $v)
        <div class="row no-gutters flex-row">
          <div class="col mincol">{{$k+1}}</div>
          <div class="col">{{$v->BicycleTypeName}}</div>
          <div class="col">{{$v->Model}}</div>
          <div class="col">{{$v->Color}}</div>
          <div class="col">{{$v->ProductNo}}</div>
          <div class="col">{{date('Y/m/d', strtotime($v->TransferDate))}}</div>
          <div class="col">{{date('Y/m/d', strtotime($v->WarrantyEndDT))}}</div>
          <?php
            $user = \App\Models\User::where('MemberId', $v->ToMemberID)->first();
          ?>
          <div class="col">{{ $user->email ?? $user->phone }}</div>
          <div class="col">{{ $user->name }}</div>
          <div class="col"><button class="btn btn-primary" wire:click="agree('{{$v->ProductNo}}', '{{ auth()->user()->MemberID }}', '{{$user->MemberID}}')">{{ trans('frontend.dashboard.Agree') }}</button></div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <div class="d-flex align-items-center">
      <h2 class="title PingFang">{{ trans('frontend.dashboard.my_bikes') }}</h2>
      <button class="btn btn-primary" style="min-width: 120px" onclick="location.href = 'register-bike-with-qr';"><i class="fa fa-plus" aria-hidden="true"></i>
      {{ trans('frontend.dashboard.add_bike') }}</button>
    </div>
    <div class="table-responsive-xl table-vw border border-secondary scrollbar-outer">
      <div class="grid-striped w900">
        <div class="row no-gutters flex-row font-weight-bold thead-dark text-white">
          <div class="col mincol">{{ trans('frontend.dashboard.number') }}</div>
          <div class="col">{{ trans('frontend.dashboard.type') }}</div>
          <div class="col">{{ trans('frontend.dashboard.model') }}</div>
          <div class="col">{{ trans('frontend.dashboard.color') }}</div>
          <div class="col">{{ trans('frontend.dashboard.product_number') }}</div>
          <div class="col">{{ trans('frontend.dashboard.buy_date') }}</div>
          <div class="col">{{ trans('frontend.dashboard.warranty') }}</div>
          <div class="text-nowrap col">{{ trans('frontend.dashboard.transfer') }}</div>
          <div class="text-nowrap col">{{ trans('frontend.dashboard.product_history') }}</div>
        </div>
        @foreach($bikes as $k => $v)
        <div class="row no-gutters flex-row">
          <div class="col mincol">{{$k+1}}</div>
          <div class="col">{{$v->BicycleTypeName}}</div>
          <div class="col">{{$v->Model}}</div>
          <div class="col">{{$v->Color}}</div>
          <div class="col">{{$v->ProductNo}}</div>
          <div class="col">{{date('Y/m/d', strtotime($v->BuyDate))}}</div>
          <div class="col">{{date('Y/m/d', strtotime($v->WarrantyEndDT))}} @if($v->IsCanBuyWarranty) <button class="btn btn-primary" onclick="location.href = '/extend/{{$v->ProductNo}}';" >{{ trans('frontend.dashboard.buy_extension') }}</button> @endif</div>
          <div class="col">@if($v->IsTransfer) {{ trans('frontend.dashboard.Transferring') }} <button class="btn btn-cancel" wire:click="cancel_transfer('{{$v->ProductNo}}', '{{$v->TransferID}}')">{{ trans('frontend.dashboard.cancel') }}</button> @else <button class="btn btn-primary" onclick="location.href = 'transfer/{{$v->ProductNo}}';">{{ trans('frontend.dashboard.application') }}</button> @endif</div>
          <div class="col"><button class="btn-search" onclick="location.href = '/bike-details/{{$v->ProductNo}}';"></button></div>
        </div>
        @endforeach
      </div>
    </div>
    @if($unckeck->count() > 0)
    <h2 class="title PingFang">{{ trans('frontend.dashboard.to_be_reviewed') }}</h2>
    <div class="table-responsive-xl table-vw border border-secondary scrollbar-outer">
      <div class="grid-striped w900">
        <div class="row no-gutters flex-row font-weight-bold thead-dark text-white">
          <div class="col">{{ trans('frontend.dashboard.operation_date') }}</div>
          <div class="col">{{ trans('frontend.dashboard.frame_number') }}</div>
          <div class="col">{{ trans('frontend.dashboard.product_number') }}</div>
          <div class="col">{{ trans('frontend.dashboard.type') }}</div>
          <div class="col">{{ trans('frontend.dashboard.model') }}</div>
          <div class="col">{{ trans('frontend.dashboard.color') }}</div>
          <div class="col">{{ trans('frontend.dashboard.status') }}</div>
          <div class="col">{{ trans('frontend.dashboard.cancel') }}</div>
        </div>
        @foreach($unckeck as $k => $v)
        <div class="row no-gutters flex-row">
          <div class="col">{{date('Y/m/d', strtotime($v->created_at))}}</div>
          <div class="col">{{$v->BBNo}}</div>
          <div class="col">{{$v->ProductNo}}</div>
          <div class="col">{{$v->BicycleTypeName}}</div>
          <div class="col">{{$v->Model}}</div>
          <div class="col">{{$v->Color}}</div>
          <div class="col">{!! $v->status == 1 ? trans('frontend.dashboard.Handling') : '<span class="text-warning font-weight-bold">'.trans('frontend.dashboard.Fail').'</span>' !!} {!! nl2br($v->memo) !!}</div>
          <div class="col"><button class="btn btn-delete bike_cancel" data-id="{{$v->id}}"></button></div>
          <div id="do_cancel_{{$v->id}}" wire:click="cancel({{$v->id}}, {{$v->No}})"></div>
        </div>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</main>
