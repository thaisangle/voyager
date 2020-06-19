<form action="{{route('size.update',$id)}}" method="POST" enctype="multipart/form-data" class="m-t-10 add-size-child-{{$id}}" style="display:none;" role="form">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="_method" value="put" />
    <div  style="display: inline-table; width: 130px;">
        <div class="form-line">
            <input type="text" name="name" class="form-control" placeholder="name">
        </div>
    </div>
    <button type="submit" class="m-t--5 btn bg-cyan">submit</button>
</form>