    <!-- body container start -->
    <div class="body_container">
      <!-- body nav start -->
      <div class="body_nav">
        <ul>
            <li><a href="/">{{ trans('frontend.Home') }}</a></li>
            <li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
            <li><a href="{{route('mybike')}}">{{ trans('frontend.dashboard.my_bikes') }}</a></li>
          <li>{{ trans('frontend.dashboard.Details') }}</li>
        </ul>
      </div>
      <!-- body nav start -->

      <div class="heading_title">
        <h2>{{ trans('frontend.dashboard.my_bikes') }} > {{$bike->ProductNo}}</h2>
      </div>
      <div class="product_card_area">
        <!--h1 class="card_title">My Bikes > 10003585</h1-->
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
          @if(json_decode($bike->images))
          <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12">
            <div class="card_box">
              <figure class="card_img">
                <img src="{{Storage::url(json_decode($bike->images)[0])}}" alt="">
              </figure>
            </div>
          </div>
          @endif

        </div>

      </div>

      <div class="heading_title">
        <h2>{{ trans('frontend.dashboard.my_bikes') }}</h2>
      </div>
      <div class="card_table table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>{{ trans('frontend.dashboard.date') }}</th>
              <th>{{ trans('frontend.dashboard.OperationType') }}</th>
              <th>{{ trans('frontend.dashboard.Remark') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{$bike->BuyDate}}</td>
              <td>{{$bike->TypeName}}</td>
              <td>{{$bike->Remark}}</td>
            </tr>

          </tbody>
        </table>
      </div>


    </div>
    <!-- body container end -->
