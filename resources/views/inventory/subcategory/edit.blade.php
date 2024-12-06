@extends('layouts.main')
@section('title','Sub Category - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        @if (session('msg'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "Oops!",
                    text: "{{ session('msg') }}",
                    icon: "error"
                });
            });
        </script>
    @endif
        <div class="dashboard-header pagetitle">
            <h1>Update Conv Item</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Update Conv Item</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <form class="row g-3" method="post" action="{{ route('subcategory.update', $subcategory->id) }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Update Conv Item</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="inputName5" class="form-label">Base Item Name</label>
                                        </strong><span class="required-classes">*</span>
                                        <select name="category_id" class="item-select form-select">
                                            <option value="{{ $selected_subcategory->category_id }}">
                                                {{ $selected_subcategory->name }}
                                            </option>
                                            @foreach ($subcategory_data as $s_category)
                                                @if ($s_category->id != $selected_subcategory->category_id)
                                                    <option value="{{ $s_category->id }}">{{ $s_category->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <script>
                                            $(document).ready(function() {
                                                $('.item-select').select2();
                                            });
                                        </script>
                                    </div><br>
                                    <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Conv Item Name</label><span
                                            class="required-classes">*</span>
                                        <input type="text" id="sub_category-input" name="sub_category"
                                            onchange="check_category_name({{ $subcategory->id }})"
                                            value="{{ $subcategory->sub_category }}" class="form-control" required>

                                    </div>
                                    <div class="text-end mt-3">
                                        @can('Sub-Category-edit')
                                        @can('price')
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        @endcan
                                        @endcan
                                        <a class="btn btn-secondary" href="{{ route('subcategory.index') }}">Back</a>
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
<script>
    // function check_category_name(subcategory_id) {
    //     // Get name from input
    //     let old_category_id = $('#name-input').val();
    //     let old_sub_category_name = $('#sub_category-input').val();

    //     // Log values for debugging
    //     // console.log(old_category_id);
    //     // console.log(old_sub_category_name);

    //     // AJAX request to check for duplicate subcategories
    //     $.ajax({
    //         url: "{{ url('get_sub_category_name_edit') }}",
    //         method: "post",
    //         data: {
    //             sub_category_id: subcategory_id,
    //             category_id: old_category_id,
    //             sub_category_name: old_sub_category_name,
    //             "_token": "{{ csrf_token() }}",
    //         },
    //         success: function(res) {
    //             // console.log(res);
    //             let category_id = res.category_id;
    //             let sub_category_name = res.sub_category;
    //             // console.log('category_id', category_id);
    //             // console.log('sub_category_name', sub_category_name);

    //             // Alert for duplicate name
    //             if (old_category_id == category_id && old_sub_category_name.toLowerCase() ==
    //                 sub_category_name.toLowerCase()) {
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Oops!',
    //                     text: 'Duplicate entry found.'
    //                 }).then(() => {
    //                     resetRow_in_same_data();
    //                 });
    //             }
    //         }
    //     });
    // }

    function resetRow_in_same_data() {
        // Reset input
        $('#name-input').val('').trigger('change');
        $('#sub_category-input').val('').trigger('change');
    }
</script> 




