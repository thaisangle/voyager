<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- fonts -->
<title>Tpack</title>

<link href="//fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Monoton" rel="stylesheet">
<!-- /fonts -->
<!-- css -->
{{-- <link href="cms-admin/login/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all" /> --}}
<!-- Bootstrap Core Css -->
    <link href="cms-admin/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <link href="cms-admin/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="cms-admin/css/themes/all-themes.css" rel="stylesheet" />
</head>
<body>
    <style type="text/css">
        .agileits1 {
            font-size: 40px;
            font-weight: normal;
            color: #fff;
            text-align: Center;
            text-transform: uppercase;
            font-family: 'Monoton', cursive;
            text-align: center;

        }
        .agileits2 {
            font-size: 15px;
            font-weight: normal;
            color: #fff;
            text-align: center;
            text-transform: capitalize;
        }


    </style>
    <div class="row">

        <div class="col-md-6 col-lg-6 col-xs-6 col-sm-12" style="height : 100vh;background-color: black;  text-align: center; align-items: center; justify-content: center; display: flex  ">
            <div style=" display: inline-block">
                <img src="{{url('upload/logo.png')}}" style="text-align: center;" alt="">
                <h2 class="agileits1" >DRESSIUM</h2>
                    {{-- <p class="agileits2">Content Management System DRESSIUM.</p> --}}

            </div>

        </div>

        <div class="col-md-6 col-lg-6 col-xs-6 col-sm-12" style="background: #fff;">
            <div style="height : 100vh; display: flex;
          align-items: center;
          justify-content: center; ">
                <form method="POST" style="width: 300px;" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                    @csrf

                    <div class="form-group"> 
                        <div class="form-line">
                            <input type="email" class="form-control " name="email" placeholder="Mail@example.com" title="Please enter a valid email" required>
                        </div>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert" style="color: red;">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group"> 
                        <div class="form-line"> 
                            <input type="password" class="form-control" name="password" placeholder="Password" id="password1" required>
                        </div>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert" style="color: red;">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>  
                    
                    <input type="submit" class="btn btn-info btn-block" value="Login">

                </form>
                
            </div>
                
        </div>
    </div>
</div>
</body>
<!-- Jquery Core Js -->
    <script src="cms-admin/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="cms-admin/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="cms-admin/plugins/node-waves/waves.js"></script>
    <!-- Custom Js -->
    <script src="cms-admin/js/admin.js"></script>


</html>