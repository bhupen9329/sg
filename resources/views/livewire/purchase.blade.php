

<div>
    <select style="height: 25px" class="js-example-basic-single form-select custom-select" name="company_id" onchange="navigateToPurchaseCreate(this)" required>
        <option value="" selected disabled>Company Name</option>
        @foreach (\App\Models\Company::whereIn('type', ['supplier', 'both'])->get() as $c_item)
            <option value="{{$c_item->id}}">{{$c_item->company_name}}</option>
        @endforeach
    </select>
</div>






  