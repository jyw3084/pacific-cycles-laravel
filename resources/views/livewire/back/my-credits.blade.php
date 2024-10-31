<main role="main" id="credits">

    <div class="contain mb-5">

        <!-- 內容開頭 -->

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">{{ trans('frontend.Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('frontend.dashboard.my_credits') }}</li>
            </ol>
        </nav>

        <h2 class="title PingFang">{{ trans('frontend.dashboard.my_credits') }}</h2>

        <div class="alert alert-gray" role="alert"><span class="PingFang">{!! trans('frontend.dashboard.have_point', ['point' => $point]) !!}</span></div>

        <h2 class="title PingFang">{{ trans('frontend.dashboard.history') }}</h2>

        <div class="table-responsive-xl w-100 border border-secondary scrollbar-outer">
            <div class="grid-striped w900">
                <div class="row no-gutters pl-3 pr-3 flex-row font-weight-bold thead-dark text-white">
                    <div class="col">{{ trans('frontend.dashboard.date') }}</div>
                    <div class="col">{{ trans('frontend.dashboard.purpose') }}</div>
                    <div class="col">{{ trans('frontend.dashboard.credits') }}</div>
                    <div class="col">{{ trans('frontend.dashboard.status') }}</div>
                </div>
                @foreach($points as $k => $v)
                <div class="row no-gutters p-3 flex-row">
                    <div class="col">{{ date('Y/m/d', strtotime($v->created_at)) }}</div>
                    <div class="col">{{ $v->order_id ? trans('frontend.dashboard.shopping_used') : trans('frontend.dashboard.birthday') }}</div>
                    <div class="col">{{ $v->amount }}</div>
                    <div class="col">{{ $v->order_id ? trans('frontend.dashboard.used') : trans('frontend.dashboard.received') }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 內容結尾 -->

    </div>
    <!-- /.container -->
</main>
