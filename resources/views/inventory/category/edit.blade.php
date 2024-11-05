@extends('layouts.main')
@section('title','Category - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        <div class="dashboard-header pagetitle">
            <h1>Update Base Item</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Update Base Item</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <form class="row g-3" method="post" action="{{ route('category.update', $category->id) }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Add Base Item</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="inputName5" class="form-label">Base Item Name</label>
                                        </strong><span class="required-classes">*</span>
                                        <input type="text" onchange="check_category_name({{$category->id}})" id="name-input" name="name" value="{{ $category->name }}"
                                            class="form-control" required>
                                    </div><br>
                                    {{-- @can('price')
                                    <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Base Price (Rs/MT)</label><span class="required-classes">*</span>
                                        <input type="number" id="edit-input" name="price"  min="1" value="{{ $category->price }}" required
                                            class="form-control">
                                    </div>
                                   
                                    <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Margin Amt (Rs/MT)</label><span class="required-classes">*</span>
                                        <input type="number" id="edit-input" name="margin"  min="1"  value="{{ $category->margin }}" required
                                            class="form-control">
                                    </div>
                                    @endcan --}}
                                    <div class="text-end mt-3">
                                        @can('Category-edit')
                                        @can('price')
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        @endcan
                                        @endcan
                                        <a class="btn btn-secondary" href="{{ route('category.index') }}">Back</a>
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
    function check_category_name(category_id) {
        //get name from input
        let name = $('#name-input').val();
        let cat_id = category_id;
        // console.log(cat_id);
        $.ajax({
            url: "{{ url('get_category_name_edit') }}",
            method: "post",
            data: {
                name: name,
                category_id: category_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(res) {
                let category_name = res.name;
                // console.log(res);
                // console.log(category_name);
                //alert for same name
                if (name.toLowerCase() === category_name.toLowerCase()) {
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
        // Resetinput
        $(`#name-input`).val('').trigger('change');
    }
</script>