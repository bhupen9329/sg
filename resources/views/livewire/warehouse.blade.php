<div>
    <select  class="form-select" id="warehouse_id" name="warehouse_id" required>
        <option value="">Warehouse Name</option>
        @foreach (\App\Models\WareHouseModel::all() as $c_item)
        <option value="{{$c_item->id}}">{{$c_item->warehouse_title}}</option>
        @endforeach
    </select>
</div>
