<div class="dealer-header" style="
    background: url({{ Storage::url($banner->image) }});
    background-position: center;">
  <div class="overlay"></div>
  <div class="container">
    <div class="dealer-header-container">
        <h1>Dealers</h1>
    </div>
  </div>
</div>
<div>
  <nav aria-label="breadcrumb" class="breadcrumb-1 p-3 b-about">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
          <div class="dealer-desktop-view">
            <a href="/dealer" class="text-light mr-5 pc-link">{{ trans('frontend.dealer.where') }}</a>
            <a href="/dealer/apply" class="text-light ml-5 pc-link-active">{{ trans('frontend.dealer.apply') }}</a>
          </div>
          <div class="dropdown-mobile-view">
            <a id="dealermobile" href="#" class="text-light pc-link-active">{{ trans('frontend.dealer.apply') }} <i class="fa fa-angle-down"></i></a>
            
            <div id="dealerdropdown">
              <a href="/dealer" class="text-light pc-link">{{ trans('frontend.dealer.where') }}</a>
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
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/dealer">{{ trans('frontend.dealer.title') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active">{{ trans('frontend.dealer.apply') }}</li>
      </ol>
    </div>
  </nav>
  <div class="container min-vh-100 mb-5">
    <h2 class="headings text-nowrap mt-5">{{ trans('frontend.dealer.apply_for') }} <span class="v-line"></span></h2>
    <div class="row mt-5 where-to-buy-content">
      <div class="col-lg-6 offset-lg-3">
        
        <form method="POST" action='{{URL::to("apply")}}'>
          @csrf
          @foreach($form as $k => $v)
          <?php
            $required = !empty($v->required) ? 'required': '';
            $key = $v->key;
            $type = '';
            if(!empty($v->type))
            {
              switch($v->type)
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
            switch($v->category)
            {
              case 1:
                $input = '<input name="'.$v->key.'" id="input_'.$k.'" type="'.$type.'" placeholder="'.$v->placeholder.'" class="form-control" '.$required.'>';
                break;
              case 2:
                $input = '<textarea name="'.$v->key.'" id="input_'.$k.'" placeholder="'.$v->placeholder.'" class="form-control" rows="3" '.$required.'></textarea>';
                break;
              case 3:
                $item = '<option></option>';
                if(!empty($v->options))
                {
                  $options = explode(',', $v->options);
                  foreach($options as $option)
                  {
                      $item .= '<option value="'.$option.'">'.$option.'</option>';
                  }
                }
                $input = '<select name="'.$v->key.'" id="input_'.$k.'" class="form-control" '.$required.'>'.$item.'</select>';
                break;
            }
          ?>
          <div class="form-group">
            <label for="input_{{ $k }}">{{ $v->filed }} @if($required)<span class="text-danger">*</span>@endif</label>
            {!! $input !!}
          </div>
          @endforeach
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <button type="submit" class="btn btn-primary w-100">{{ trans('frontend.dealer.send') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
