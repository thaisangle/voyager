<!DOCTYPE html>
<!-- saved from url=(0082)https://demos.creative-tim.com/argon-dashboard-pro/pages/dashboards/dashboard.html -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Send mail by Dressium.">
    <meta name="author" content="Creative Tim">
    <title>Dressium Send Mail</title>
    <base href="{{ asset('')}}">
    <!-- Favicon -->
    <link rel="icon" href="https://demos.creative-tim.com/argon-dashboard-pro/assets/img/brand/favicon.png"
        type="image/png">
    <!-- Fonts -->
    <link rel="stylesheet" href="cms-admin/css/css">
    <!-- Jquery -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
   
</head>

<body>

    <!-- Sidenav -->
    <!-- Main content -->
    <header style="text-align:center; padding:15px; background: #f7f7f7ee; font-weight: bold; color: #bdbcbc; font-size: 25px"> Dressium</header>
    <div class="container " >
        <div class="p-5" style="background:#ffffff;" >        
            <div style="margin: 25px; border-left: 5px solid #59c2b7; background: #fff "> 
                <div style="padding-top:25px; text-align:center">
                    <img style="width:200px; display:inline-block; margin-top: 10px" src="{{env('APP_URL')}}upload/logo_send.png" alt="">
                </div>
                <p style="text-align: center;">Your verification code is: {{$data['verify_code']}}</p>
                
            </div>
        </div>

    </div>
    <footer style="text-align:center; padding:15px; background: #f7f7f7ee; font-weight: bold; color: #bdbcbc;"> © 2020 Dressium. All rights reserved.</footer>
    <!-- Argon Scripts -->
    <!-- Core -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
</body>

</html>
