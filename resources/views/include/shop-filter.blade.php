<div id="category-filter">
    <button class="closeFilter" id="btn-exit">x</button>
    <form method="get">
        <div class="input-group col-md-12">
            <input class="form-control py-2" type="search" id="search-filter" placeholder="{{ trans('frontend.store.search') }}" name="search" value="{{ request()->search }}"/>
            <span class="input-group-append">
          <button id="search-filter-btn" class="btn btn-outline-secondary" type="button">
            <i class="fa fa-search"></i>
          </button>
        </span>
        </div>

        @if(isset($filters))
            @foreach($filters as $key => $filter)
        <div id="category" class="mt-5 p-3">
            <h6 class="section-title-white">{{ trans('frontend.store.'.$key) }}</h6>


                @foreach($filter as $k => $option)
                    @if($k == 0)
                        <div class="form-group mt-4">
                            @else
                                <div class="form-group mt-3">
                                    @endif
                                    <label class="form-check-label" @if($key=='color') style="background: {{$option}}; color: transparent; width: 40px; border: 1px solid #fff" @endif>{{ $option }}</label>
                                    <label class="main float-right">
                                        <input type="checkbox" class="form-check-input ml-0 text-right" id="folding-bikes-chk" value="{{ $option }}" name="{{$key}}[]">
                                        <span class="geekmark"></span>
                                    </label>
                                </div>
                                @endforeach


                        </div>
                    @endforeach
                    @endif

                        <div id="category" class="mt-5 p-3">
            <h6 class="section-title-white">{{ trans('frontend.store.category') }}</h6>
            @if(isset($categories))
            @foreach($categories as $k => $category)
                @if($k == 0)
                    <div class="form-group mt-4">
                        @else
                            <div class="form-group mt-3">
                                @endif
                                <label class="form-check-label">{{ $category }}</label>
                                <label class="main float-right">
                                    <input type="checkbox" class="form-check-input ml-0 text-right" id="folding-bikes-chk" value="{{ $k }}" name="category[]" @if(!empty(request()->all()['category']) && in_array($k, request()->all()['category'])) checked @endif>
                                    <span class="geekmark"></span>
                                </label>
                            </div>
                            @endforeach
                            @endif
                    </div>

                    <div id="type" class="mt-5 p-3">
                        <h6 class="section-title-white">{{ trans('frontend.store.model') }}</h6>
                        @if(isset($types))
                        @foreach($types as $k => $type)
                            @if($k == 0)
                                <div class="form-group mt-4">
                                    @else
                                        <div class="form-group mt-2">
                                            @endif
                                            <label class="form-check-label">{{ $type }}</label>
                                            <label class="main float-right">
                                                <input type="checkbox" class="form-check-input check-input ml-0 text-right" id="birdy-chk" value="{{ $k }}" name="type[]" @if(!empty(request()->all()['type']) && in_array($k, request()->all()['type'])) checked @endif>
                                                <span class="geekmark"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                        @endif
                                </div>
                                <button id="btn-show-result" class="closeFilter" type="submit"><span class="text-primary">{{ trans('frontend.store.show_results') }} <i class="fa fa-chevron-right" aria-hidden="true"></i></span></button>
                    </div>
        </div>
    </form>
</div>

