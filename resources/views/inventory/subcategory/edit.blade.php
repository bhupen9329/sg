@extends('layouts.main')
@section('title','Sub Category - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
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
                                        <select name="category_id" class="item-select form-select" id="name-input">
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
                                        {{-- <input type="hidden" id="category-id-input" name="category_id"
                                            value="{{ $selected_subcategory->category_id }}"> --}}
                                        <input type="text" id="sub_category-input" name="sub_category"
                                            onchange="check_category_name({{ $subcategory->id }})"
                                            value="{{ $subcategory->sub_category }}" class="form-control" required>

                                    </div>
                                    {{-- <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Weight(kg/ft)</label><span
                                            class="required-classes">*</span>
                                        <input type="number" id="edit-input" name="weight"
                                            value="{{ $subcategory->weight }}" min="0.001" class="form-control" required step="0.001">
                                    </div> --}}
                                    @can('price')
                                    {{-- <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Difference Amt (Gauge)</label><span
                                            class="required-classes"> *</span>
                                        <input type="number" value="{{ $subcategory->difference }}" id="edit-input" min="0" name="diff" class="form-control" required
                                        step="0.001" >
                                    </div> --}}
                                    @endcan

                                    {{-- <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Providers</label><span
                                            class="required-classes"> *</span>
                                        <select class="js-example-theme-multipl" multiple="multiple" name="provider[]"
                                            style="width: 100%" required>
                                            @if (isset($provider) && count($provider) > 0)
                                                @foreach ($provider as $s_provider)
                                                    <option value="{{ $s_provider->id }}" selected>
                                                        {{ $s_provider->company_name }}</option>
                                                @endforeach
                                                @foreach ($company_data as $item)
                                                    @if (!in_array($item->id, $provider->pluck('id')->toArray()))
                                                        <option value="{{ $item->id }}">{{ $item->company_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach ($company_data as $item)
                                                    <option value="{{ $item->id }}">{{ $item->company_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> --}}



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
    function check_category_name(subcategory_id) {
        // Get name from input
        let old_category_id = $('#name-input').val();
        let old_sub_category_name = $('#sub_category-input').val();

        // Log values for debugging
        // console.log(old_category_id);
        // console.log(old_sub_category_name);

        // AJAX request to check for duplicate subcategories
        $.ajax({
            url: "{{ url('get_sub_category_name_edit') }}",
            method: "post",
            data: {
                sub_category_id: subcategory_id,
                category_id: old_category_id,
                sub_category_name: old_sub_category_name,
                "_token": "{{ csrf_token() }}",
            },
            success: function(res) {
                // console.log(res);
                let category_id = res.category_id;
                let sub_category_name = res.sub_category;
                // console.log('category_id', category_id);
                // console.log('sub_category_name', sub_category_name);

                // Alert for duplicate name
                if (old_category_id == category_id && old_sub_category_name.toLowerCase() ==
                    sub_category_name.toLowerCase()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Duplicate entry found.'
                    }).then(() => {
                        resetRow_in_same_data();
                    });
                }
            }
        });
    }

    function resetRow_in_same_data() {
        // Reset input
        $('#name-input').val('').trigger('change');
        $('#sub_category-input').val('').trigger('change');
    }
</script> 
