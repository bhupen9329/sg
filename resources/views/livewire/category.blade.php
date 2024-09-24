<div>
    <select class="form-select custom-select" id="name-input" name="category_id" required>
        <option value="" selected disabled>Select Category</option>
        @foreach (\App\Models\Category::all() as $c_item)
            <option value="{{$c_item->id}}">{{$c_item->name}}</option>
        @endforeach
    </select>
</div>

<script>
    $(document).ready(function() {
        $('.custom-select').select2();
              // Focus the search box when the subcategory dropdown is opened
    $('.custom-select').on('select2:open', function () {
    document.querySelector('.select2-search__field').focus();
});

    });
</script>

