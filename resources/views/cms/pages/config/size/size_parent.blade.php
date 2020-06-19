<form action="{{route('size.store')}}" method="POST" class="form-inline" role="form">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="text" class="form-control" name="name" placeholder="name" required>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>