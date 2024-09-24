<div>


    <select  class="form-select" id="company_id" name="company_id">
        <option value="">Company Name</option>
        @foreach (\App\Models\company::all() as $c_item)
        <option value="{{$c_item->id}}">{{$c_item->company_name}}</option>
        @endforeach
    </select>

</div>


