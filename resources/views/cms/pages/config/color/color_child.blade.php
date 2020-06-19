<form action="{{route('color.update',$id)}}" method="POST" enctype="multipart/form-data" class="add-color-child-{{$id}}" style="display:none;" role="form">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="_method" value="put" />
    @if(!$status)
        <div class="input-group colorpicker" style="display: inline-table; width: 130px;">
            <div class="form-line">
                <input type="text" required name="name" class="form-control" value="#00AABB">
            </div>
            <span class="input-group-addon">
                <i></i>
            </span>
        </div>
    @else
        <div class="input-group">
            <input type="file" accept="image/*" required name="image" placeholder="Input field">
        </div>
    @endif
    <button type="submit" class="m-t--20 btn bg-cyan">submit</button>
</form>