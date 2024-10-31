<div class="support-header" style="
    height: 500px;
    width: 100%;
    background: linear-gradient(0deg, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url({{ Storage::url($banner->image) }});
    display: table;
    position: relative;
    z-index: 1;
    background-position: center center;">
  <div class="overlay"></div>
  <div class="container">
    <div class="support-header-container">
        <h1>{{ trans('frontend.support.title') }}</h1>
    </div>
    </div>
</div>
<div>
  <nav aria-label="breadcrumb" class="breadcrumb-1 p-3 b-about">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="support-desktop-view">
            <a href="/support" class="text-light ml-5 pc-link pc-link-active">{{ strtoupper(trans('frontend.support.download')) }}</a>
            <a href="/support/faq" class="text-light ml-5 pc-link">{{ strtoupper(trans('frontend.support.faq')) }}</a>
            <a href="/support/warranty" class="text-light ml-5 pc-link">{{ strtoupper(trans('frontend.support.warranty_policy')) }}</a>
          </div>
          <div class="dropdown-mobile-view">
            <a href="#" id="aboutmobile" class="text-light mr-5 pc-link pc-link-active">{{ strtoupper(trans('frontend.support.download')) }} <i class="fa fa-angle-down"></i></a>
            <div id="aboutdropdown">
              <a href="/support/faq" class="text-light ml-5 pc-link">{{ strtoupper(trans('frontend.support.faq')) }}</a>
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
        <li class="breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active" style="color:#000000 !important;">{{ trans('frontend.support.download') }}</li>
      </ol>
    </div>
  </nav>
 <div class="container">
    <h2 class="headings text-nowrap">{{ trans('frontend.support.download') }} <span class="v-line"></span></h2>
      <div class="first-download">
        <h4 class="support-title">{{ trans('frontend.support.user_manuals') }}</h4>
        <table class="download-table">
          @foreach($manuals as $k => $v)
          <tr><td>
            <span class="table-title">{{ $v->name }}</span>
            <a href="{{ Storage::url($v->file) }}" target="_blank"><span class="download-button">Download <i class="fa fa-angle-down"></i></span></a>
          </td></tr>
          @endforeach
        </table>
      </div>
      <div class="second-download">
        <h4 class="support-title">{{ trans('frontend.support.catalogs') }}</h4>
        <table class="download-table">
          @foreach($catalogs as $k => $v)
          <tr><td>
            <span class="table-title">{{ $v->name }}</span>
            <a href="{{ Storage::url($v->file) }}" target="_blank"><span class="download-button">Download <i class="fa fa-angle-down"></i></span></a>
          </td></tr>
          @endforeach
        </table>
      </div>
      <div class="second-download">
        <h4 class="support-title">{{ trans('frontend.support.others') }}</h4>
        <table class="download-table">
          @foreach($others as $k => $v)
          <tr><td>
            <span class="table-title">{{ $v->name }}</span>
            <a href="{{ Storage::url($v->file) }}" target="_blank"><span class="download-button">Download <i class="fa fa-angle-down"></i></span></a>
          </td></tr>
          @endforeach
        </table>
      </div>
  </div>
