<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <base href="{{asset('')}}">

  <title>Blank Page | Bootstrap Based Admin Template - Material Design</title>
  <!-- Favicon-->
  <link rel="icon" href="{{url('')}}/cms-admin/favicon.ico" type="image/x-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
    type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

  <!-- Bootstrap Core Css -->
  {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
   integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" 
  crossorigin="anonymous"> --}}
  <link href="{{url('')}}/cms-admin/plugins/bootstrap/css/bootstrap.css?t={{time()}}" rel="stylesheet">

  <!-- Waves Effect Css -->
  <link href="{{url('')}}/cms-admin/plugins/node-waves/waves.css?t={{time()}}" rel="stylesheet" />

  <!-- Animation Css -->
  <link href="{{url('')}}/cms-admin/plugins/animate-css/animate.css?t={{time()}}" rel="stylesheet" />

  <!-- Custom Css -->
  <link href="{{url('')}}/cms-admin/css/style.css?t={{time()}}" rel="stylesheet">

  <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
  <link href="{{url('')}}/cms-admin/css/themes/all-themes.css?t={{time()}}" rel="stylesheet" />

  <!-- Morris Css -->
  <link href="{{url('')}}/cms-admin/plugins/morrisjs/morris.css?t={{time()}}" rel="stylesheet" />

  <!-- Sweetalert Css -->
  <link href="{{url('')}}/cms-admin/plugins/sweetalert/sweetalert.css?t={{time()}}" rel="stylesheet" />

  <!-- Colorpicker Css -->
  <link href="{{url('')}}/cms-admin/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css?t={{time()}}" rel="stylesheet" />

  <!-- Light Gallery Plugin Css -->
  <link href="{{url('')}}/cms-admin/plugins/light-gallery/css/lightgallery.css?t={{time()}}" rel="stylesheet">

  {{-- font awesome --}}
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    {{-- fancybox --}}
    <link href="{{url('')}}/cms-admin/css/jquery.fancybox.min.css?t={{time()}}" rel="stylesheet" type="text/css">
  
</head>

<body class="theme-cyan">
  <!-- Page Loader -->
  <div class="page-loader-wrapper">
    <div class="loader">
      <div class="preloader">
        <div class="spinner-layer pl-red">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div>
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
      <p>Please wait...</p>
    </div>
  </div>
  <!-- #END# Page Loader -->
  <!-- Overlay For Sidebars -->
  <!-- Search Bar -->
  <div class="search-bar">
    <div class="search-icon">
      <i class="material-icons">search</i>
    </div>
    <input type="text" placeholder="START TYPING...">
    <div class="close-search">
      <i class="material-icons">close</i>
    </div>
  </div>
  <!-- #END# Search Bar -->
  <!-- Top Bar -->
  @include('cms.layouts.navbartop');
  <!-- #Top Bar -->

  @include('cms.layouts.menu');

  <section class="content">
    @yield('content')
  </section>

  <!-- Jquery Core Js -->
  <script src="{{url('')}}/cms-admin/plugins/jquery/jquery.min.js?t={{time()}}"></script>

  <!-- Bootstrap Core Js -->
  <script src="{{url('')}}/cms-admin/plugins/bootstrap/js/bootstrap.js?t={{time()}}"></script>
  {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> --}}
  {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script> --}}
  <!-- Select Plugin Js -->
  {{-- <script src="{{url('')}}/cms-admin/plugins/bootstrap-select/js/bootstrap-select.js"></script> --}}

  <!-- Slimscroll Plugin Js -->
  <script src="{{url('')}}/cms-admin/plugins/jquery-slimscroll/jquery.slimscroll.js?t={{time()}}"></script>

  <!-- Waves Effect Plugin Js -->
  <script src="{{url('')}}/cms-admin/plugins/node-waves/waves.js?t={{time()}}"></script>

  <!-- Custom Js -->
  <script src="{{url('')}}/cms-admin/js/admin.js?t={{time()}}"></script>

  <!-- Demo Js -->
  <script src="{{url('')}}/cms-admin/js/demo.js?t={{time()}}"></script>

  <!-- SweetAlert Plugin Js -->
  <script src="{{url('')}}/cms-admin/plugins/sweetalert/sweetalert.min.js?t={{time()}}"></script>
  {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}
  {{-- tooltip --}}
  <script src="{{url('')}}/cms-admin/js/pages/ui/tooltips-popovers.js?t={{time()}}"></script>

  <!-- Bootstrap Colorpicker Js -->
  <script src="{{url('')}}/cms-admin/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js?t={{time()}}"></script>

  <!-- Light Gallery Plugin Js -->
  <script src="{{url('')}}/cms-admin/plugins/light-gallery/js/lightgallery-all.js?t={{time()}}"></script>
  <!--Fancybox js -->
  <script src="{{url('')}}/cms-admin/js/jquery.fancybox.min.js?t={{time()}}"></script>

  <!-- Custom Js -->
  {{-- <script src="../../js/pages/ui/dialogs.js"></script> --}}


  @yield('javascript')
  <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
  <script>
    var OneSignal = window.OneSignal || [];
      OneSignal.push(function() {
        OneSignal.init({
          appId: "a491925f-ef80-477d-a41e-2b7d86cb0787",
        });
      });
      OneSignal.push(function() {
            OneSignal.sendTags({
                userId: '{{ Auth::user()->id }}',
            }).then(function(tagsSent) {
               console.log('User id has been updated');
            });
        });
  </script>
  <script>
    $(document).ready(function($) {
      var link = window.location.href;
      var nameSitebar = link.split("cms/");
      nameSitebar = nameSitebar[1].split("/");

      
      var sort  =  <?php echo (isset($_GET['sort']))?"\"".$_GET['sort']."\"":"\"no\""; ?>;
      var sortName = sort.split("-");
        if(sort !="no"){
          if(sortName[1] == "asc"){
            $("#"+sortName).find(".fa-sort-up").removeClass('col-blue-grey');
          }else{
            $("#"+sortName).find(".fa-sort-down").removeClass('col-blue-grey');
          }
        }
      // sortting table;
      $(".iconSort").click(function(event) {
        var id = $(this).attr('id');
        if(id !== undefined){
          var dau = "?";
          for (var i = link.length - 1; i >= 0; i--) 
          if(link[i] == '?') {
            dau = "&&"; break;
          }
          if(sort == "no"){
              $(location).attr('href', link+dau+"sort="+id+"-asc");
          }else{
            var typeSort = sortName[1];
            if(id == sortName[0] && typeSort == "asc"){
              typeSort = "desc";
            }else{
              if(id == sortName[0] && typeSort == "desc"){
                typeSort = "asc";
              }
            }
            link = link.replace("sort="+sort,"sort="+id+"-"+typeSort);
            $(location).attr('href', link);
          }
        }
      });

    });
  </script>
</body>

</html>