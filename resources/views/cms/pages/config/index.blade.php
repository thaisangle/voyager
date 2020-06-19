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
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                    <div class="card">
                        <div class="header bg-cyan">
                            <h2>
                                Size Table
                            </h2>
                            <small>Size Manager...</small>
                        </div>

                        <div class="body table-responsive" style="height: 400px; overflow: auto;">
                            <button class="btn bg-blue waves-effect m-b-15" type="button" data-toggle="collapse" data-target="#collapseSize" aria-expanded="false"
                                    aria-controls="collapseExample">
                                Add
                            </button>

                            <div class="collapse m-b-5" id="collapseSize">
                                @include('cms.pages.config.size.size_parent')
                            </div>
                            <table class="table table-bordered table-striped " >{{-- 
                            	@include('cms.components.thead',['col_show'=>$col_show,'action'=>route('user.index')]) --}}
                                <thead>
                                	<tr>
                                		<th>Parent</th>
                                		<th>Child</th>
                                		<th width="30px">Action</th>
                                	</tr>
                                </thead>
                                <tbody>
                                	@foreach ($sizes as $key => $value)
                                		<tr>
                                			<td>{{$value['name']}}</td>
                                            <th >
	                                            @foreach ($value['child'] as $key_child =>  $value_child)
	                                             	<span>
                                                        <span  class="badge waves-effect btn-delete" data-id="{{$value_child['id']}}" data-type="size"
                                                        style="border: 0.5px solid #efefef; }}; display: inline-block; cursor: pointer" >
                                                        {{$value_child['name']}} {{$value_child['ted_size']}}
                                                        </span>
                                                        @include('cms.pages.config.size.delete',['id'=>$value_child['id']])
                                                    </span>
	                                             @endforeach
                                                 @include('cms.pages.config.size.size_child',['id'=>$value['id']]) 
                                            </th>
	                                        <td>
                                                <button data-id="{{$value['id']}}" data-type="size" class="btn-add-child waves-effect  btn bg-blue "><i class="fas fa-plus"></i></button>
                                                @include('cms.pages.config.size.delete',['id'=>$value['id']])
                                                <button class="btn btn-danger waves-effect btn-delete" data-type="size" data-id="{{$value['id']}}" ><i class="fas fa-trash"></i></button>
                                            </td>
	                                    </tr>
                                	@endforeach
                                		
                                </tbody>
                            </table>
                            {{-- <div class="">
                        		{{ $users->appends($_GET)->links() }}
                        	</div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header bg-cyan">
                            <h2>
                                Color Table
                            </h2>
                            <small>Color Manager...</small>
                        </div>
							
                        <div class="body table-responsive" style="height: 400px; overflow: auto;">
							<button class="btn bg-blue waves-effect m-b-15" type="button" data-toggle="collapse" data-target="#collapseColor" aria-expanded="false"
                                    aria-controls="collapseExample">
                                Add
                            </button>

                            <div class="collapse m-b-5" id="collapseColor">
                                @include('cms.pages.config.color.color_parent')
                            </div>
                            <table class="table table-bordered table-striped ">{{-- 
                            	@include('cms.components.thead',['col_show'=>$col_show,'action'=>route('user.index')]) --}}
                                <thead>
                                	<tr>
                                		<th>Parent</th>
                                		<th>Child</th>
                                		<th width="30px">Action</th>
                                	</tr>
                                </thead>
                                <tbody>
                                	@foreach ($colors as $key => $value)
                                		<tr>
                                			<td>{{$value['name']}}</td>
                                            <td >
	                                            @foreach ($value['child'] as $key_child =>  $value_child)
				                                    <span>
                                                        @if(!$value_child['status'])
                                                        <span  class="badge btn waves-effect btn-delete" data-toggle="tooltip" data-placement="top" data-id="{{$value_child['id']}}" data-type="color" title="{{$value_child['name']}}"
    				                                    style="border: 0.5px solid #efefef; background-color: {{$value_child['name']}}; display: inline-block; width: 45px; height: 45px; cursor: pointer" >
                                                        </span>
                                                        @else
                                                            <span  class="badge btn waves-effect btn-delete" data-toggle="tooltip" data-placement="top" data-id="{{$value_child['id']}}" data-type="color" title="{{$value_child['name']}}"
                                                        style="border: 0.5px solid #efefef; background: #383838;width: 45px; height: 45px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; " >
                                                            <img src="{{$value_child['path_image']}}" alt="">
                                                        </span>
                                                        @endif
                                                    </span>
                                                    @include('cms.pages.config.color.delete',['id'=>$value_child['id']])

	                                            @endforeach
                                                @include('cms.pages.config.color.color_child',['id'=>$value['id'],'status' => $value['status']])
                                            </td>
	                                        <td>
	                                        	<button data-id="{{$value['id']}}" data-type="color" class="btn-add-child waves-effect  btn bg-blue "><i class="fas fa-plus"></i></button>

                                                @include('cms.pages.config.color.delete',['id'=>$value['id']])
                                                
                                                <button class="btn btn-danger waves-effect btn-delete" data-type="color" data-id="{{$value['id']}}" ><i class="fas fa-trash"></i></button>
							                    
							                </td>
	                                    </tr>
                                	@endforeach
                                		
                                </tbody>
                            </table>

                            {{-- <div class="">
                        		{{ $users->appends($_GET)->links() }}
                        	</div> --}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                    <div class="card">
                        <div class="header bg-cyan">
                            <h2>
                                Category Table
                            </h2>
                            <small>Category Manager...</small>
                        </div>

                        <div class="body table-responsive" style="height: 400px; overflow: auto;">
                            <button class="btn-add-category btn bg-blue waves-effect m-b-15" type="button">
                                Add
                            </button>

                            <table class="table table-bordered table-striped " >{{-- 
                                @include('cms.components.thead',['col_show'=>$col_show,'action'=>route('user.index')]) --}}
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>image (select-none-default)</th>
                                        <th width="30px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $key => $value)
                                        <tr>
                                            <td>{{$value['name']}}</td>
                                            <th style="min-width: 280px;">
                                                <img width="50px" style="background-color: black" src="{{$value->path_icon_select}}" alt="" class="btn m-r-30"> 
                                                <img class="btn m-r-30" style="background-color: black" width="50px" src="{{$value->path_icon_none}}" alt=""> 
                                                <img class="btn btn m-r-30" style="background-color: black"  width="50px" src="{{$value->path_icon_default}}" alt="">
                                                <img class="btn" style="background-color: white"  width="50px" src="{{$value->path_icon_bg_white}}" alt=""> 
                                            </th>
                                            <td>
                                                @include('cms.pages.config.category.delete',['id'=>$value['id']])
                                                <button class="btn btn-danger waves-effect btn-delete" data-type="category" data-id="{{$value['id']}}" ><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                        
                                </tbody>
                            </table>
                            {{-- <div class="">
                                {{ $users->appends($_GET)->links() }}
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="header bg-cyan">
                            <h2>
                                Brand list
                            </h2>
                            <small>Brand Manager...</small>
                        </div>

                        <div class="body table-responsive">
                            <div>
                                <button class="btn-add-brand badge bg-blue" type="button" data-toggle="collapse" data-target="#collapseBrand" aria-expanded="false"
                                    aria-controls="collapseExample">
                                    <i class="fas fa-plus"></i>
                                </button>

                                <div class="collapse m-t-5" id="collapseBrand">
                                    @include('cms.pages.config.brand.add')
                                </div>
                            </div>
                            @foreach ($brands as $value)
                                <span style="text-align: center;">
                                    <span data-toggle="tooltip" class="btn badge btn-delete bg-cyan item-category" data-placement="top" title="{{$value->id}}" data-type="brand" data-id="{{$value->id}}" data-sub-html="{{$value->name}}">
                                        {{$value->name}}
                                    </span>
                                    @include('cms.pages.config.brand.delete',['id'=>$value['id']])
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            @include('cms.pages.config.category.add')

    	</section>
    <!-- /.content -->

        
    </div>


@endsection
@section('javascript')
<script>
	$(document).ready(function() {
		$("#config").addClass('active')
        $(function () {
            $('#aniimated-thumbnials').lightGallery({
                thumbnail: true,
                selector: 'a'
            });
        });
        $('.colorpicker').colorpicker();
        $('.btn-add-child').click(function(event) {
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            $('.add-'+type+'-child-'+id).toggle();
        });
        @if (session('success'))
            swal("Success!", "{{session('success')}}.", "success");
        @endif
        @if (session('danger'))
            swal("Error!", "{{session('danger')}}.", "warning");
        @endif
        
        $(".btn-add-category").click(function(event) {
            $("#smallModal").modal('show');

        });
        $('.btn-delete').click(function (){
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function (isConfirm) {
                if(isConfirm){
                    $(".form-delete-"+type+"-"+id).submit();
                }
            });
        });
        
	});
</script>
@endsection