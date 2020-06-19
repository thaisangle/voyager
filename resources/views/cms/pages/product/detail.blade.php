@extends('cms.layouts.master')
@section('content')
<div class="container-fluid m-t--40">
	<section>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<ol class="breadcrumb breadcrumb-col-cyan">
						<li><a href="cms"><i class="material-icons">home</i> Home</a></li>
						<li ><a href="{{route('product.index')}}"><i class="fas fa-female"></i> Dress</a></li>
						<li class="active"><i class="fas fa-female"></i>  Show</li>
					</ol>
				</div>
			</div>    
		</div>
	</section>
	<section>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-5 col-sm-10 col-xs-12">
				<div class="card">
					<div class="card-header bg-cyan">
						<div class="row align-items-center text-logo">
							<div class="col-4"><span class="mb-0 header-title ">INFOMATION DETAIL</span>
							</div>
							<small>Dress Detail...</small>
							<div class="col-8 text-right" style="margin-bottom: -12px;" ><a class="btn btn-neutral pito-color btn_edit" href="{{route('product.edit',[$product['id']])}}">Edit Info</a>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="pl-lg-4">
							<div class="row">
								<div class="col-lg-5 wrapper">
									<a data-fancybox="gallery"  href="{{ $product['image'] }}" data-caption="{{ $product['title'] }}"><img src="{{ $product['image'] }}" alt="" height="100%" width="100%"> </a>
								</div>
								<div class="col-lg-7">
									<div class="dress-info col-md-12">
										<div class="heading-small text-muted mb-4">
											INFORMATION DRESS
										</div>
										<div class="col-md-12" style="padding: 10px;padding-top:35px;">
											<div class="col-md-6 row">
												<label class="text-header-view text-capitalize">Title</label><br>
												@if($product['title'])
													<label class="text-detail-view font-weight-bold">{{ $product['title'] }}</label>
												@else
													<label class="label label-danger">NONE</label>
												@endif
											</div>
											<div class="col-md-6 row">
												<label class="text-header-view text-capitalize">Category</label><br>
												@if($product['category'])
													<label class="text-header-view font-weight-bold">{{ $product['category'] }}</label>
												@else
												<label class="label label-danger">NONE</label>
												@endif
											</div>
										</div>
										<div class="col-md-12" style="padding: 10px;">
											<div class="col-md-6 row">
												<label class="text-header-view text-capitalize">Size</label><br>
												@if($product['size'])
													<label class="text-detail-view font-weight-bold">{{ $product['size'] }}</label>
												@else
													<label class="label label-danger">NONE</label>
												@endif
											</div>
											<div class="col-md-6 row">
												<label class="text-header-view text-capitalize">Price</label><br>
												@if($product['price'])
													<label class="text-header-view font-weight-bold">{{ $product['price'] }}</label>
												@else
													<label class="label label-danger">NONE</label>
												@endif
											</div>
										</div>
										<div class="col-md-12" style="padding: 10px;">
											<div class="col-md-6 row">
												<label class="text-header-view text-capitalize">Brand</label><br>
												@if($product['brand'])
													<label class="text-detail-view font-weight-bold">{{ $product['brand'] }}</label>
												@else
													<label class="label label-danger">NONE</label>
												@endif
											</div>
											<div class="col-md-6 col-xs-6  row">
												<label class="text-header-view text-capitalize">Discreptions</label><br>
												@if($product['descriptions'])
													<labe class="text-detail-view font-weight-bold">{{ $product['descriptions'] }}</>
												@else
													<label class="label label-danger">NONE</label>
												@endif
											</div>
										</div>
										<div class="col-md-12" style="padding: 10px;padding-bottom: 30px;">
											<div class="col-md-6 row">
												<label class="text-header-view text-capitalize">Create_at</label><br>
												@if($product['create'])
													<label class="text-detail-view font-weight-bold">{{date('Y-m-d H:i:s',$product['create'])}}</label>
												@else
													<label class="label label-danger">NONE</label>
												@endif
											</div>
											<div class="col-md-6 row">
												
											</div>
										</div>
										<hr/>
									</div>
									
									<div  class="dress-info col-md-12">
										<div class="heading-small text-muted mb-4">
											ADDRESS CONTACT
										</div>
										<div class="col-md-12" style="padding:10px;padding-top:35px;">
											<div class="col-md-6 row">
												<label class="text-header-view text-capitalize">Poster</label><br>
												@if($product['user'])
													<label class="text-detail-view font-weight-bold">{{ $product['user'] }}</label>
												@else
													<label class="label label-danger">NONE</label>
												@endif
											</div>
											<div class="col-md-6 row">
												<label class="text-header-view text-capitalize">Address</label><br>
												@if($product['address'])
													<label class="text-detail-view font-weight-bold">{{$product['address']}}</label>
												@else
													<label class="label label-danger">NONE</label>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-12 col-md-5 col-sm-10 col-xs-12">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center" style="padding-bottom: 20px;">
							<div class="col-4"><h4 class="mb-0">IMAGE</h4></div>
							<div class="col-8 text-right" >
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="pl-lg-4">
							<div class=" wrapper row">
								<div class="col-lg-3">
									<a data-fancybox="gallery"  href="{{ $product['image'] }}" data-caption="{{ $product['title'] }}"><img src="{{ $product['image'] }}" alt="" height="100%" width="100%"> </a>
								</div>
								<div class="col-lg-3">
									<a data-fancybox="gallery"  href="{{ $product['image'] }}" data-caption="{{ $product['title'] }}"><img src="{{ $product['image'] }}" alt="" height="100%" width="100%"> </a>
								</div>
								<div class="col-lg-3">
									<a data-fancybox="gallery"  href="{{ $product['image'] }}" data-caption="{{ $product['title'] }}"><img src="{{ $product['image'] }}" alt="" height="100%" width="100%"> </a>
								</div>
								<div class="col-lg-3">
									<a data-fancybox="gallery"  href="{{ $product['image'] }}" data-caption="{{ $product['title'] }}"><img src="{{ $product['image'] }}" alt="" height="100%" width="100%"> </a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
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