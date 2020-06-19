<thead>
    <tr>
    	@foreach ($col_show as $key => $value)
    		<th class="iconSort" id="{{$key}}">
    			@if($value['sort'])
    				<a class="col-cyan">
    					<i class="col-blue-grey fas fa-sort-up"></i>
    					<i style="margin-left: -12px;" class="col-blue-grey fas fa-sort-down"></i>
    				</a>

    			@endif
        		{{$value['name']}}
        	</th>
    	@endforeach
            <th>Action</th>   
	</tr>
	{{-- @dd($action) --}}
    <form action="{{$action}}" method="GET" role="form" >
        <tr>
        	@foreach ($col_show as $key => $value)
        		<th id="{{$key}}">
            		@if(isset($value['search']))
            			@if($value['search'] == 'text')
            			<input  class="form-control" value="{{$value['data']}}" type="{{$value['search']}}" name="{{$key}}">
            			@elseif($value['search'] == 'select')
        					<select class="form-control" name="{{$key}}">
							@foreach ($value['value'] as $key1 => $ele)
							    <option <?php if($value['data'] == $key1) echo 'selected'; ?>  value="{{$key1}}">{{$ele}}</option>
							@endforeach
							</select>
						@endif
            		@endif
            	</th>
        	@endforeach
                <th>
                    <button class="btn btn-primary m-b-5 " style="float:right;"  type="submit"><i class="fas fa-search"></i></button>
    </form>
                    {{-- clear get --}}
                    <a href="{{$action}}">
                        <button class="btn btn-warning m-b-5 m-r-5 " style="float:right;"  type="submit"><i class="fas fa-chalkboard"></i></button>
                    </a>
                </th>
        </tr>

</thead>