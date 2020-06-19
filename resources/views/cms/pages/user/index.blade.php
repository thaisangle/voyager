@extends('cms.layouts.master')
@section('content')

    <div class="container-fluid m-t--40">
    	<section>
    		<div class="row">
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
		    			<ol class="breadcrumb breadcrumb-col-cyan">
		    				<li><a href="cms"><i class="material-icons">home</i> Home</a></li>
		                    <li class="active"><i class="fas fa-users"></i>  User</li>
		                </ol>
		            </div>
		        </div>    
    		</div>
    	</section>
  	   	<section>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-cyan">
                            <h2>
                                User Table
                            </h2>
                            <small>User Manager...</small>
                        </div>

                        <div class="body table-responsive">
                            <table class="table table-bordered table-striped ">
                            	@include('cms.components.thead',['col_show'=>$col_show,'action'=>route('user.index')])
                                
                                <tbody>
                                	@foreach ($users as $key => $value)
                                		<tr>
	                                        <th scope="row" width="100px">{{$value->id}}</th>
	                                        <td>
	                                            <img class="img-circle" width="80px" src="{{$value->avatar}}" /> 
	                                            {{$value->name}}
                                            </td>
	                                        <td>
	                                        	@if($value->birth_day)
	                                        		{{date('Y-m-d',$value->birth_day)}}
	                                        	@else
	                                        		<span class="font-italic col-pink">No data</span>
	                                        	@endif
	                                        </td>
	                                        <td>
	                                        	@if($value->email)
		                                        	{{$value->email}}
	                                        	@else
	                                        		<span class="font-italic col-pink">No data</span>
	                                        	@endif
	                                        </td>
	                                        
	                                        <td>
	                                        	@if($value->type_social == 'facebook')
		                                        	<span class="badge bg-cyan">{{$value->type_social}}</span>
		                                        @elseif($value->type_social == 'gmail')
		                                        	<span class="badge bg-orange">{{$value->type_social}}</span>
		                                        @else
			                                        <span class="badge bg-default">normal</span>
		                                        @endif
	                                        </td>
	                                        <td>{{date('Y-m-d H:i:s',$value->created_at)}}</td>
	                                        <td>
							                    <a href="{{route('user.edit',[$value->id])}}">
							                    	<button class="btn btn-default btn-form-edit">
								                    	<span class="fas fa-pen"></span>
								                	</button>
								                </a>
							                </td>
	                                    </tr>
                                	@endforeach
                                		
                                </tbody>
                            </table>
                            <div class="">
                        		{{ $users->appends($_GET)->links() }}
                        	</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
    	</section>
    <!-- /.content -->
	    <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog"
	         aria-labelledby="userModalCenterTitle" aria-hidden="true">
	        <div class="modal-dialog modal-dialog-centered" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title text-primary" id="exampleModalLongTitle">Edit user</h4>
	                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
	                        <span aria-hidden="true">&times;</span>
	                    </button>
	                </div>
	                <div class="modal-body">
	                    ...
	                </div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
	                    <button type="submit" class="btn btn-primary btn-submit-form-edit" form="edit_user">Confirm</button>
	                </div>
	            </div>
	        </div>
	    </div>

    </div>

@endsection
@section('javascript')
<script>
	$(document).ready(function() {
		$("#user").addClass('active');
		@if (session('success'))
			swal("Update!", "{{session('success')}}.", "success");
		@endif
	});
</script>
@endsection