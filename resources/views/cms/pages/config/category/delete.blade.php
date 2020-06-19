<form action="{{route('category.destroy',$id)}}" class="form-delete-category-{{$id}}" method="POST" style="display: inline-block">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <input type="hidden" name="_method" value="delete" />
    <div>
    </div>
</form>
