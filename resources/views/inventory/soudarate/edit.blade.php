@extends('layouts.main')
@section('title', 'Sub Category - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        <div class="dashboard-header pagetitle">
            <h1>Add Conv Item</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Add Conv Item</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <form class="row g-3" method="post" action="{{ route('rate.update', $item->id) }}">
                @csrf
              
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Edit Conv Item</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="categorySelect" class="form-label">Base Item Name</label>
                                        <span class="required-classes">*</span>
                                        <select id="categorySelect" class="form-control" name="category_id" required>
                                            <option value="">Select a Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $item->category_id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
            
                                    <div class="col-md-8">
                                        <label for="subCategorySelect" class="form-label">Subcategory</label>
                                        <select id="subCategorySelect" class="form-control" name="subcategory_id" required>
                                            <option value="">Select a Subcategory</option>
                                            @foreach ($subcategories as $subcategory) <!-- Assuming you have subcategories passed to the view -->
                                                <option value="{{ $subcategory->id }}" {{ $subcategory->id == $item->subcategory_id ? 'selected' : '' }}>
                                                    {{ $subcategory->sub_category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
            
                                    <div class="col-md-8 mt-4">
                                        <label for="date-input" class="form-label">Select Date</label>
                                        <span class="required-classes">*</span>
                                        <input type="date" id="date-input" name="selected_date" class="form-control"
                                               value="{{ $item->selected_date }}" required>
                                    </div>
            
                                    <div class="col-md-8 mt-4">
                                        <label for="price-input" class="form-label">Conv Price</label>
                                        <span class="required-classes">*</span>
                                        <input type="text" id="price-input" name="item_price" class="form-control"
                                               value="{{ $item->item_price }}" required>
                                    </div>

                                    <div class="col-md-8 mt-4">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks here...">{{ old('remarks', $item->remarks) }}</textarea>
                                    </div>
                                    
            
                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a class="btn btn-secondary" href="{{ route('rate.index') }}">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            </div>
        </section>
    </main><!-- End #main -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#categorySelect').on('change', function() {
            var categoryId = $(this).val();
            $('#subCategorySelect').empty().append(
            '<option value="">Select a Subcategory</option>'); // Clear subcategory dropdown

            if (categoryId) {
                $.ajax({
                    url: '/get-subcategories/' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $('#subCategorySelect').append('<option value="' + value
                                .id + '">' + value.sub_category + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>

<script>
    function check_category_name() {
        //get name from input
        let old_category_id = $('#name-input').val();
        let old_sub_category = $('#sub_category-input').val();
        // console.log(name);
        // console.log(sub_category);
        $.ajax({
            url: "{{ url('get_sub_category_name') }}",
            method: "post",
            data: {
                category_id: old_category_id,
                sub_category_name: old_sub_category,
                "_token": "{{ csrf_token() }}",
            },
            success: function(res) {
                // console.log(res);
                let category_id = res.category_id;
                let sub_category_name = res.sub_category;
                //alert for same name
                if (old_category_id === category_id && old_sub_category.toLowerCase() === sub_category_name
                    .toLowerCase()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps!',
                        text: 'Duplicate entry found.'
                    }).then(() => {
                        resetRow_in_same_data();
                    });
                }
            }
        })
    }

    function resetRow_in_same_data() {
        // Reset specific input fields in the row
        $(`#name-input`).val('').trigger('change');
        $(`#sub_category-input`).val('').trigger('change');
    }
</script>

<script>
    $(document).ready(function() {
        $('#categorySelect').focus(); // Example code
    });
</script>
