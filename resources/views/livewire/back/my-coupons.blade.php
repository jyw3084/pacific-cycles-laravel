<main role="main" id="vouchers">

    <div class="contain mb-5">

        <!-- 內容開頭 -->

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">{{ trans('frontend.Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('frontend.dashboard.my_coupons') }}</li>
            </ol>
        </nav>

        <h2 class="title PingFang">{{ trans('frontend.dashboard.my_coupons') }}</h2>

        <div class="alert alert-gray" role="alert"><span class="PingFang">{!! trans('frontend.dashboard.have_coupons', ['coupons' => count($coupon)]) !!}</div>

        <h2 class="title PingFang">{{ trans('frontend.dashboard.list') }}</h2>

        <div class="p-3 mb-4">
            <a class="page_link filter mr-3 active" filter="" href="#">{{ trans('frontend.dashboard.all') }}</a>
            <a class="page_link filter mr-3" filter="nouse" href="#">{{ trans('frontend.dashboard.unused') }}</a>
            <a class="page_link filter" filter="use" href="#">{{ trans('frontend.dashboard.used') }}</a>
        </div>

        <div class="table-responsive-xl w-100 border border-secondary scrollbar-outer">
            <div class="grid-striped w900 filterlist">
                <div class="row no-gutters pl-3 pr-3 flex-row font-weight-bold thead-dark text-white">
                    <div class="col">{{ trans('frontend.dashboard.usage_period') }}</div>
                    <div class="col">{{ trans('frontend.dashboard.coupon_name') }}</div>
                    <div class="col">{{ trans('frontend.dashboard.status') }}</div>
                </div>
                @foreach($coupons as $k => $v)
                <div class="row no-gutters p-3 flex-row filters {{ $v->status ? 'use' : 'nouse' }}">
                    <div class="col">{{ date('Y/m/d', strtotime($v->start)) }} ~ {{ date('Y/m/d', strtotime($v->end)) }}</div>
                    <div class="col">{{ $v->name }}</div>
                    <div class="col">{{ $v->status ? trans('frontend.dashboard.used') : trans('frontend.dashboard.unused') }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 內容結尾 -->

    </div>
    <!-- /.container -->
</main>
