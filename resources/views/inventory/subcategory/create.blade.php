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
            <h1>Add Conv Item</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Add Conv Item</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <form class="row g-3" method="post" action="{{ route('subcategory.store') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">  
                            <div class="card-body">
                                <h5 class="card-title">Add Conv Item</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="inputName5"  class="form-label">Base Item Name</label>
                                        </strong><span class="required-classes">*</span>

                                        @livewire('category')

                                    </div><br>
                                    <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Conv Item Name</label><span
                                            class="required-classes">*</span>
                                        <input type="text" id="sub_category-input"  name="sub_category" class="form-control"
                                            required>
                                    </div>
                                    {{-- <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Weight (kg/ft)</label><span
                                            class="required-classes"> *</span>
                                        <input type="number" id="edit-input" min="0" name="weight" class="form-control" required
                                        step="0.001" >
                                    </div> --}}
{{-- 
                                    <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Difference Amt (Gauge)</label><span
                                            class="required-classes"> *</span>
                                        <input type="number" id="edit-input" min="0" name="diff" class="form-control" required
                                            >
                                    </div> --}}

                                    {{-- <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Providers</label><span
                                            class="required-classes"> *</span>
                                        <select class="js-example-theme-multipl" multiple="multiple" name="provider[]"
                                            style="width: 100%" required>
                                            @foreach ($company_data as $item)
                                                <option value="{{ $item->id }}">{{ $item->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="text-end mt-3">
                                        <button type="submit"  class="btn btn-primary">Submit</button>
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
    // function check_category_name() {
    //     //get name from input
    //     let old_category_id = $('#name-input').val();
    //     let old_sub_category = $('#sub_category-input').val();
    //     // console.log(name);
    //     // console.log(sub_category);
    //     $.ajax({
    //         url: "{{ url('get_sub_category_name') }}",
    //         method: "post",
    //         data: {
    //             category_id: old_category_id,
    //             sub_category_name: old_sub_category,
    //             "_token": "{{ csrf_token() }}",
    //         },
    //         success: function(res) {
    //             // console.log(res);
    //             let category_id = res.category_id;
    //             let sub_category_name = res.sub_category;
    //             //alert for same name
    //             if (old_category_id === category_id && old_sub_category.toLowerCase() === sub_category_name.toLowerCase()) {
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Opps!',
    //                     text: 'Duplicate entry found.'
    //                 }).then(() => {
    //                     resetRow_in_same_data();
    //                 });
    //             }
    //         }
    //     })
    // }

    function resetRow_in_same_data() {
        // Reset specific input fields in the row
        $(`#name-input`).val('').trigger('change');
        $(`#sub_category-input`).val('').trigger('change');
    }
</script>

