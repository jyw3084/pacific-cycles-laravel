<html>
    <head>
      <title>Order Complete</title>
        {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
        <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css')}}">
    </head>
    <body style="background-color: #FAFAFA;">
        <div class="row">
            <div class="col-md-2"></div>
            <div class=col-md-8>
                <div class="mt-3" style="background-color: #FFFFFF">
                    <center>
                        {{-- <div class="row">
                            <div class="col-md-3"> --}}
                        <a href="https://www.pacific-cycles.com/">
                          <img class="mt-3" src="https://www.linkpicture.com/q/pacific-cycles-1.png" width="200"/>
                        </a>
                          {{-- </div>
                        </div> --}}
                      </center>
                      <center>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col">
                                <h3 class="text-justify text-success">{{$data['body']}}</h3>
                                {{-- <h3 class="text-success">Your body goes here :)</h3> --}}
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                      </center>
                    <center>
                        <div class="row mt-3">
                            <div class="col-md-3"></div>
                            <div class="col">
                                <b class="pt-5">Check it out here <a href="https://www.pacific-cycles.com/" target="_blank">Pacific Cycles</a></b>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </center>

                    {{-- customized data using if condition --}}
                    @if($data['template_name'] === 'order_placed')
                      {{-- andromeda galaxy --}}
                    @endif
                    <hr>
                    <center>
                        <div>
                            <em class="text-muted">Copyright © 2021 Pacific Cycles, All rights reserved.</em>
                            <br>
                            <span>
                              <a href="https://www.youtube.com/user/pacificcycles">
                                <img width="31px"src="https://1000logos.net/wp-content/uploads/2017/05/New-YouTube-logo.jpg" alt="youtube">
                              </a>

                              <a href="https://www.facebook.com/pacificcycles.inc/">
                                <img width="45px"src="https://1000logos.net/wp-content/uploads/2021/04/Facebook-logo.png" alt="facebook">
                              </a>

                              <a href="https://www.instagram.com/pacificcycles/">
                                <img width="28px"src="https://1000logos.net/wp-content/uploads/2017/02/New-Instagram-logo.jpg" alt="instagram">
                              </a>
                            </span>
                          </div> 
                    </center>
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>
    </body>
</html>