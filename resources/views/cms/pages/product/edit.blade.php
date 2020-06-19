@extends('cms.layouts.master')
@section('content')
    <div class="container-fluid m-t-40">
       <section>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <ol class="breadcrumb breadcrumb-col-cyan">
                            <li><a href="cms"><i class="material-icons">home</i> Home</a></li>
                            <li ><a href="{{route('product.index')}}"><i class="fas fa-female"></i> Dress</a></li>
                            <li ><a href="{{route('product.show',[123])}}"><i class="fas fa-female"></i>Show</a></li>
                            <li class="active"><i class="fas fa-female"></i> Edit</li>
                        </ol>
                    </div>
                </div>    
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 order-xl-4">
                        <div class="card">
                            <div class="card-header bg-cyan">
                                <div class="row align-items-center">
                                    <div class="col-xl-12 text-logo">
                                        <div class="mb-0 header-title">UPDATE INFO</div>
                                        <small>Form edit...</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="" method="">
                                                <div class="col-md-6">
                                                    <div class="title-header"> 
                                                        <b class="edit-title">Info of dress</b>    
                                                    </div>
                                                    <div class="w-100 mt-3">
                                                        <label class="text-detail-header pr-3">Title</label>
                                                        @if($product->title)
                                                            <input name="" class="form-control" value="{{ $product->title }}"  />
                                                        @else
                                                             <input name="" class="form-control" placeholder="Please Enter Title"  />
                                                        @endif
                                                            <p class="erro"></p>
                                                    </div>
                                                    <div class="w-100 mt-3">
                                                        <label class="text-detail-header pr-3" for="exampleFormControlSelect1">Category</label>
                                                       
                                                            <select class="form-control" id="exampleFormControlSelect1">
                                                                @foreach ($categories as $key  => $value)
                                                                    <option value="{{ $value->id }}">{{ $value->name  }}</option>
                                                                @endforeach
                                                           </select>
                                                        <p class="erro"></p>
                                                    </div>
                                                    <div class="w-100 mt-3">
                                                        <label class="text-detail-header pr-3">Size</label>
                                                        <select class="form-control" id="exampleFormControlSelect1">
                                                            @foreach ($sizes as $key  => $value)
                                                                  <option value="{{ $value->id }}">{{ $value->name  }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="erro"></p>
                                                    </div>
                                                    <div class="w-100 mt-3">
                                                        <label class="text-detail-header pr-3">Price</label>
                                                        <input name="" class="form-control" value="{{ (int)$price }}" placeholder="Please Enter Price " type="number"/>
                                                        <p class="erro"></p>
                                                    </div>
                                                    <div class="w-100 mt-3">
                                                        <label class="text-detail-header pr-3">Brand</label>
                                                        <select class="form-control" id="exampleFormControlSelect1">
                                                            @foreach ($brands as $key  => $value)
                                                                <option value="{{ $value->id }}">{{ $value->name  }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="erro"></p>
                                                    </div>
                                                    <div class=" w-100 mt-3">
                                                        <label for="exampleFormControlTextarea1" class="text-detail-header pr-3">Discreption</label>
                                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{ $product->descriptions}}</textarea>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="title-header"> 
                                                        <b class="edit-title">Contact</b>    
                                                    </div>
                                                    <div class="w-100 mt-3">
                                                        <label class="text-detail-header pr-3">Address</label>
                                                        <input name="" class="form-control" placeholder="Please Enter Address" value="{{ $product->address }}"/>
                                                        <p class="erro"></p>
                                                    </div>
                                                    <div class="title-header" style="margin-top:20px;"> 
                                                        <b class="edit-title">Image</b>    
                                                    </div>
                                                    <div class="form-group w-100 mt-3">
                                                        <label class="text-detail-header pr-3" for="exampleFormControlFile1">Upload File Image</label>
                                                        <input required type="file" class="form-control" name="images[]" placeholder="address" multiple >
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="btn btn-success border-success right" style="margin-top:10px; "> Submit</div>
                                                </div>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      </section>
    </div>
@endsection