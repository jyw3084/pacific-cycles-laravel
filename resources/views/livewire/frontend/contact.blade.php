
<div class="contact-header" style="background-image: url({{ Storage::url($banner->image) }});">
  <div class="overlay"></div>
  <div class="container">
    <div class="contact-header-container">
      <h1>{{ trans('frontend.contact.title') }}</h1>
    </div>
  </div>
</div>
<div>
  <nav aria-label="breadcrumb">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">{{ trans('frontend.Home') }}</a></li>
        <li class="breadcrumb-item breadcrumb-item-active">{{ trans('frontend.contact.title') }}</li>
      </ol>
    </div>
  </nav>
  <div class="container min-vh-100 mb-5">
    <h2 class="headings text-nowrap mt-5">{{ trans('frontend.contact.title') }} <span class="v-line"></span>
    </h2>
    <div class="row">
      <div class="col-lg-6 offset-lg-3">
        <div class="text-center">
          <h3 class="my-4 headings">
            Pacific Cycles, Inc
          </h3>
          <p class="my-4 text-primary">
            Email: team@pacific-cycles.com
          </p>
        </div>
        <form method="POST" action='{{URL::to("contact")}}'>
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
                $input = '<input wire:model="post.'.$v->key.'" name="'.$v->key.'" id="input_'.$k.'" type="'.$type.'" placeholder="'.$v->placeholder.'" class="form-control custom-form-control" '.$required.'>';
                break;
              case 2:
                $input = '<textarea wire:model="post.'.$v->key.'" name="'.$v->key.'" id="input_'.$k.'" placeholder="'.$v->placeholder.'" class="form-control custom-form-control" rows="3" '.$required.'></textarea>';
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
                $input = '<select wire:model="post.'.$v->key.'" name="'.$v->key.'" id="input_'.$k.'" class="form-control" '.$required.'>'.$item.'</select>';
                break;
            }
          ?>
          <div class="form-group">
            <label for="input_{{ $k }}">{{ $v->filed }} @if($required)<span class="text-danger">*</span>@endif</label>
            {!! $input !!}
          </div>
          @endforeach
          <div class="row">
            <div class="col-md-6 offset-md-3">
              <button type="submit" class="btn btn-primary w-100">{{ trans('frontend.contact.send') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
