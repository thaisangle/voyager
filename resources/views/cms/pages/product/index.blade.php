@extends('cms.layouts.master')
@section('content')
    <div class="container-fluid m-t-40">
        <section>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <ol class="breadcrumb breadcrumb-col-cyan header_breadcrumb ">
                            <li><a href="cms"><i class="material-icons">home</i> Home</a></li>
                            <li class="active"><i class="fas fa-female"></i>  Dress</li>
                        </ol>
                    </div>
                </div>    
            </div>
        </section>
        <section>
            <div class="row clearfix ">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-cyan">
                           <div class="row">
                                <h2>
                                    Dress Table
                                </h2>
                                <small>Dress Manager...</small>
                           </div>
                        </div>
                        <div class="body table-responsive">
                           
                            <table class="table table-bordered table-striped ">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Brand</th>
                                        <th>Address</th>
                                        <th>Create_at</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    
                                    </tr>
                                    <tr>
                                        <form action="">
                                            <th><input  class="form-input" type="text"></th>
                                            <th><input  class="form-input" type="text"></th>
                                            <th><input  class="form-input" type="text"></th>
                                            <th><input  class="form-input" type="text"></th>
                                            <th><input  class="form-input" type="text"></th>
                                            <th><button type="submit" class="btn btn-success"> <i class="fa fa-search-minus"></i> </button></th>
                                        </form>
                                    </tr>
                                 </thead>
                                    <tbody>
                                        @foreach ($product as $key => $value):
                                                <tr>
                                                    <td>{{ $value['id'] }}</td>
                                                    <td>
                                                        <div>
                                                            <span class="font-italic ">Brand:</span> 
                                                            @if($value['brand'])
                                                                <span style="color:yellowgreen;">{{$value['brand']}}</span>  
                                                             @else
                                                                    <span class="font-italic col-pink">No data</span>
                                                             @endif
                                                        </div>
                                                        <div>
                                                            <span class="font-italic ">Category:</span> 
                                                            @if($value['category'])
                                                                <span class="font-italic col-blue-grey">{{$value['category']}}</span>
                                                            @else
                                                                <span class="font-italic col-pink">No data</span>
                                                            @endif
                                                        </div> 
                                                        <div>
                                                           <span class="font-italic ">Title:</span> 
                                                            @if($value['title'])
                                                                <span>{{$value['title']}}</span>
                                                            @else
                                                                <span class="font-italic col-pink">No data</span>
                                                            @endif
                                                        </div> 
                                                    </td>
                                                    <td>
                                                        @if($value['address'])
                                                            {{$value['address']}}
                                                        @else
                                                            <span class="font-italic col-pink">No data</span>
                                                        @endif
                                                    </td>
                                                    <td style="height: 150px;width: 150px;">
                                                        <img src="{{ $value['image'] }}" style="width: 150px;height: 150px;"></img>
                                                    </td>
                                                    <td>
                                                        @if($value['create'])
                                                            {{$value['create']}}
                                                        @else
                                                            <span class="font-italic col-pink">No data</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-facebook" href="{{route('product.show',[$value['id']])}}">
                                                           Detail
                                                        </a>
                                                    </td>
                                                    
                                                </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                            <div class="">
                        		{{ $paginate->appends($_GET)->links() }}
                        	</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection