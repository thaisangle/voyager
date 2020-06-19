@extends('cms.layouts.master')
@section('content')

    <div class="container-fluid m-t--40">
    	<section>
    		<div class="row">
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
		    			<ol class="breadcrumb breadcrumb-col-cyan">
		    				<li class="active"><a href="cms"><i class="material-icons">home</i> Home</a></li>
		                </ol>
		            </div>
		        </div>    
    		</div>
    	</section>
    	<section>
    		<div class="row">
    			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-red hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">email</i>
                        </div>
                        <div class="content">
                            <div class="text">MESSAGES</div>
                            <div class="number">15</div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-purple hover-zoom-effect">
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="content">
                            <div class="text">USER</div>
                            <div class="number">{{$user->count()}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-light-blue hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">access_alarm</i>
                        </div>
                        <div class="content">
                            <div class="text">ALARM</div>
                            <div class="number">07:00 AM</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">gps_fixed</i>
                        </div>
                        <div class="content">
                            <div class="text">LOCATION</div>
                            <div class="number">Turkey</div>
                        </div>
                    </div>
                </div>
    			
    		</div>
    	</section>

    	<section>
    		<div class="row clearfix">
                <!-- Area Chart -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>AREA CHART</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="area_chart" class="graph"></div>
                        </div>
                    </div>
                </div>
                <!-- #END# Area Chart -->
                <!-- Donut Chart -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>USER SOCIAL CHART</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="donut_chart" class="graph"></div>
                        </div>
                    </div>
                </div>
                <!-- #END# Donut Chart -->
            </div>
    	</section>
    </div>

@endsection
@section('javascript')
<!-- Morris Plugin Js -->
<script src="cms-admin/plugins/raphael/raphael.min.js"></script>
<script src="cms-admin/plugins/morrisjs/morris.js"></script>

<!-- Custom Js -->
<script src="cms-admin/js/custorm/chart.js"></script>

<script>

	$(document).ready(function() {
		var donut_data = [];
		donut_data['data'] = 
			@php
				echo json_encode($data_user);
			@endphp;
		donut_data['colors'] = ['rgb(233, 30, 99)', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)'];
		getMorris('donut', 'donut_chart',donut_data);
		$("#home").addClass('active')
	});
</script>
@endsection