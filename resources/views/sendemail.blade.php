<html>
    <head>
        <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <div class="user-form-wrapper mt-5">
                <div class="row">
                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    
                    <div class="card-body">
                        <form action="/send-mail" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                <button type="submit" class="btn btn-success float-left">Test send Email</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </body>
</html>