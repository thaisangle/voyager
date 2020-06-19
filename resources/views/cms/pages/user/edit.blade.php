@extends('cms.layouts.master')
@section('content')

    <div class="container-fluid m-t--40">
    	<section>
    		<div class="row">
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
		    			<ol class="breadcrumb breadcrumb-col-cyan">
		    				<li><a href="cms"><i class="material-icons">home</i> Home</a></li>
		                    <li ><a href="{{route('user.index')}}"><i class="fas fa-users"></i>  User</a></li>
		                    <li class="active"><i class="fas fa-users"></i>  Show</li>
		                </ol>
		            </div>
		        </div>    
    		</div>
    	</section>
  	   	<section>
            <div class="row clearfix">
                <div class="row clearfix">
                <div class="col-xs-12 col-sm-3">
                    <div class="card profile-card">
                        <div class="profile-header">&nbsp;</div>
                        <div class="profile-body">
                            <div class="image-area">
                                <img src="http://s3.amazonaws.com/37assets/svn/765-default-avatar.png" width="150px"/>
                            </div>
                            <div class="content-area">
                                <h3>{{$user->name}}</h3>
                            </div>
                        </div>
                        <div class="profile-footer">
                            <ul>
                                <li>
                                    <span>Email</span>
                                    <span>
                                    	{{$user->email?$user->email:'<span class="col-pink">No data</span>'}}
                                    </span>
                                </li>
                                <li>
                                    <span>Birth day</span>
                                    <span>
                                    	{{$user->birth_day?date('Y-m-d',$user->birth_day):'<span class="col-pink">No data</span>'}}
                                    </span>
                                </li>
                                <li>
                                    <span>Social</span>
                                    <span>
                                    	{{$user->type_social?$user->type_social:'<span class="col-pink">No data</span>'}}
                                    </span>
                                </li>
                            </ul>
                            <form action="{{route('user.destroy',$user->id)}}" class="form-ban-user" method="POST" style="display: inline">
		                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
		                    	<input type="hidden" name="_method" value="delete" />
		                    	<div>
	                            </div>
		                    </form>
                            <button class="btn btn-danger btn-lg waves-effect btn-block btn-ban" data-type="confirm"><i class="fas fa-ban"></i></button>
		                    
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-9">
                    <div class="card">
                        <div class="body">
                            <div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active" role="presentation"><a href="#profile_settings" aria-controls="settings" role="tab" data-toggle="tab">Profile Settings</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="profile_settings">
                                        <form action="{{route("user.update",$user->id)}}" enctype="multipart/form-data" method="POST" class="form-horizontal">
                                        	<input type="hidden" name="_token" value="{{csrf_token()}}" />
								            <input type="hidden" name="_method" value="put" />

                                            <div class="form-group">
                                                <label for="NameSurname" class="col-sm-2 control-label">Name</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="name" placeholder="Name Surname" name="name" value="{{$user->name}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Email" class="col-sm-2 control-label">Email</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <input type="email" class="form-control" id="Email" name="email" placeholder="Email" value="{{$user->email}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="NameSurname" class="col-sm-2 control-label">Birth day</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                    	
                                                        <input type="date" class="form-control" name="birth_day" value="{{date('Y-m-d',$user->birth_day)}}" required>
                                                    </div>
                                                </div>
                                            </div>	
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-danger">SUBMIT</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- /.row -->
    	</section>
    <!-- /.content -->
	    
    </div>

@endsection
@section('javascript')
<script>
	$(document).ready(function() {
		$("#user").addClass('active');
		$('.btn-ban').click(function (){
			swal({
		        title: "Are you sure?",
		        text: "You will not be able to recover this user!",
		        type: "warning",
		        showCancelButton: true,
		        confirmButtonColor: "#DD6B55",
		        confirmButtonText: "Yes, ban it!",
		        closeOnConfirm: false
		    }, function (isConfirm) {
	    		swal({
	    			title: "Deleted?",
			        text: "User has been baned.",
			        type: "success",
			        closeOnConfirm: false
			    }, function(){
			    	$(".form-ban-user").submit();
			    })
		    	    
		    });
		});
		@if (session('success'))
			swal("Update!", "{{session('success')}}.", "success");
		@endif
        @if (session('danger'))
            swal("Error!", "{{session('danger')}}.", "danger");
        @endif
	});
</script>
@endsection