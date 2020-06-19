<div class="modal fade m-t-100" id="smallModal"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-col-default">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">ADD CATEGORY</h4>
            </div>
            <form action="{{route('category.store')}}" enctype="multipart/form-data"  method="POST" role="form">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    
                    <div class="m-b-20">
                        Name
                        <input type="text" class="form-control" required name="name" id="" placeholder="Input field">
                    </div>
                    
                    <div class="form-group">
                        Select
                        <input type="file" accept="image/*" required name="img_select" placeholder="Input field">
                    </div>
                    <div class="form-group">
                        None
                        <input type="file" accept="image/*" required name="img_none" placeholder="Input field">
                    </div>
                    <div class="form-group">
                        Default
                        <input type="file" accept="image/*" required name="img_default" placeholder="Input field">
                    </div>
                    <div class="form-group">
                        Background white
                        <input type="file" accept="image/*" required name="img_white" placeholder="Input field">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bg-cyan waves-effect">SUBMIT</button>
                    <button type="button" class="btn bg-red waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>

        </div>
    </div>
</div>