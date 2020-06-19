<form action="{{route('color.store')}}" method="POST" class="form-inline" role="form">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="text" class="form-control" name="name" placeholder="name" required>
    <select name="status" id="">
        <option value="0">Text</option>
        <option value="1">Image</option>
    </select>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>