<div class="support-header" style="
    background: linear-gradient(0deg, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url({{ Storage::url($banner->image) }});
    background-size: cover;
    background-position: center;">
  <div class="overlay"></div>
  <div class="container">
    <div class="support-header-container">
        <h1>{{ trans('frontend.support.title') }}</h1>
    </div>
    </div>
</div>
  <nav aria-label="breadcrumb" class="breadcrumb-1 p-3 b-about">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="support-desktop-view">
            <a href="/support" class="text-light ml-5 pc-link">{{ strtoupper(trans('frontend.support.download')) }}</a>
            <a href="/support/faq" class="text-light ml-5 pc-link pc-link-active">{{ strtoupper(trans('frontend.support.faq')) }}</a>
            <a href="/support/warranty" class="text-light ml-5 pc-link">{{ strtoupper(trans('frontend.support.warranty_policy')) }}</a>
          </div>
          <div class="dropdown-mobile-view">
            <a href="#" id="aboutmobile" class="text-light mr-5 pc-link pc-link-active">{{ strtoupper(trans('frontend.support.faq')) }} <i class="fa fa-angle-down"></i></a>
            <div id="aboutdropdown">
              <a href="/support" class="text-light ml-5 pc-link">{{ strtoupper(trans('frontend.support.download')) }}</a>
              <a href="/support/warranty" class="text-light ml-5 pc-link">{{ strtoupper(trans('frontend.support.warranty_policy')) }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <nav aria-label="breadcrumb" class="breadcrumb-2">
    <div class="container">
      <ol class="breadcrumb breadcrumb-2">
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/">{{ trans('frontend.Home') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/support">{{ trans('frontend.support.title') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active" style="color:#000000 !important;">{{ trans('frontend.support.faq') }}</li>
      </ol>
    </div>
  </nav>
 <div class="container">
    <h2 class="headings text-nowrap">{{ strtoupper(trans('frontend.support.faq')) }} <span class="v-line"></span></h2>
      <div class="col-lg-12 text-center faq-category-container">
        <a href="javascript:void(0)" class="pc-link pc-link-active ml-5" data-type="birdy">Birdy</a>
        <a href="javascript:void(0)" class="pc-link ml-5 " data-type="carry">Carry Me</a>
        <a href="javascript:void(0)" class="pc-link ml-5 " data-type="if">If</a>
        <a href="javascript:void(0)" class="pc-link ml-5 " data-type="reach">Reach</a>
        <a href="javascript:void(0)" class="pc-link ml-5 " data-type="supportive">Supportive</a>
      </div>
      <div class="birdy-faq-container">
      	<!--Accordion wrapper-->
		@if($birdy)
		<div class="accordion md-accordion" id="birdy" role="tablist" aria-multiselectable="true">
		@foreach($birdy as $k => $v)
		  <div class="card">
		    <div class="card-header active" role="tab" id="headingOne{{$v->id}}">
		      <a data-toggle="collapse" class="category-accordion" data-parent="#birdy" href="#collapseOne{{$v->id}}" aria-expanded="true"
		        aria-controls="collapseOne{{$v->id}}">
		        <h5 class="mb-0">
				{{ $v->question }} <i class="fa fa-angle-down"></i>
		        </h5>
		      </a>
		    </div>
		    <div id="collapseOne{{$v->id}}" class="collapse" role="tabpanel" aria-labelledby="headingOne{{$v->id}}"
		      data-parent="#birdy">
		      <div class="card-body">{!! nl2br($v->answer) !!}</p>
		      </div>
		    </div>

		  </div>
		@endforeach
		  <!-- Accordion card -->
		</div>
		<!-- Accordion wrapper -->
		@endif
		@if($carry)
		<div class="accordion md-accordion" id="carry" role="tablist" aria-multiselectable="true">
		@foreach($carry as $k => $v)
		  <div class="card">
		    <div class="card-header active" role="tab" id="headingOne{{$v->id}}">
		      <a data-toggle="collapse" class="category-accordion" data-parent="#carry" href="#collapseOne{{$v->id}}" aria-expanded="true"
		        aria-controls="collapseOne{{$v->id}}">
		        <h5 class="mb-0">
				{{ $v->question }} <i class="fa fa-angle-down"></i>
		        </h5>
		      </a>
		    </div>
		    <div id="collapseOne{{$v->id}}" class="collapse" role="tabpanel" aria-labelledby="headingOne{{$v->id}}"
		      data-parent="#carry">
		      <div class="card-body">{!! nl2br($v->answer) !!}</p>
		      </div>
		    </div>

		  </div>
		@endforeach
		  <!-- Accordion card -->
		</div>
		<!-- Accordion wrapper -->
		@endif
		@if($if)
		<div class="accordion md-accordion" id="if" role="tablist" aria-multiselectable="true">
		@foreach($if as $k => $v)
		  <div class="card">
		    <div class="card-header active" role="tab" id="headingOne{{$v->id}}">
		      <a data-toggle="collapse" class="category-accordion" data-parent="#if" href="#collapseOne{{$v->id}}" aria-expanded="true"
		        aria-controls="collapseOne{{$v->id}}">
		        <h5 class="mb-0">
				{{ $v->question }} <i class="fa fa-angle-down"></i>
		        </h5>
		      </a>
		    </div>
		    <div id="collapseOne{{$v->id}}" class="collapse" role="tabpanel" aria-labelledby="headingOne{{$v->id}}"
		      data-parent="#if">
		      <div class="card-body">{!! nl2br($v->answer) !!}</p>
		      </div>
		    </div>

		  </div>
		@endforeach
		  <!-- Accordion card -->
		</div>
		<!-- Accordion wrapper -->
		@endif
		@if($reach)
		<div class="accordion md-accordion" id="reach" role="tablist" aria-multiselectable="true">
		@foreach($reach as $k => $v)
		  <div class="card">
		    <div class="card-header active" role="tab" id="headingOne{{$v->id}}">
		      <a data-toggle="collapse" class="category-accordion" data-parent="#reach" href="#collapseOne{{$v->id}}" aria-expanded="true"
		        aria-controls="collapseOne{{$v->id}}">
		        <h5 class="mb-0">
				{{ $v->question }} <i class="fa fa-angle-down"></i>
		        </h5>
		      </a>
		    </div>
		    <div id="collapseOne{{$v->id}}" class="collapse" role="tabpanel" aria-labelledby="headingOne{{$v->id}}"
		      data-parent="#reach">
		      <div class="card-body">{!! nl2br($v->answer) !!}</p>
		      </div>
		    </div>

		  </div>
		@endforeach
		  <!-- Accordion card -->
		</div>
		<!-- Accordion wrapper -->
		@endif
		@if($supportive)
		<div class="accordion md-accordion" id="supportive" role="tablist" aria-multiselectable="true">
		@foreach($supportive as $k => $v)
		  <div class="card">
		    <div class="card-header active" role="tab" id="headingOne{{$v->id}}">
		      <a data-toggle="collapse" class="category-accordion" data-parent="#supportive" href="#collapseOne{{$v->id}}" aria-expanded="true"
		        aria-controls="collapseOne{{$v->id}}">
		        <h5 class="mb-0">
				{{ $v->question }} <i class="fa fa-angle-down"></i>
		        </h5>
		      </a>
		    </div>
		    <div id="collapseOne{{$v->id}}" class="collapse" role="tabpanel" aria-labelledby="headingOne{{$v->id}}"
		      data-parent="#supportive">
		      <div class="card-body">{!! nl2br($v->answer) !!}</p>
		      </div>
		    </div>

		  </div>
		@endforeach
		  <!-- Accordion card -->
		</div>
		<!-- Accordion wrapper -->
		@endif
  	</div>
</div>
