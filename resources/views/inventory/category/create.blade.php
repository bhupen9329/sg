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
            <h1>Add Base Item</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Add Base Item</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <form class="row g-3" method="post" action="{{ route('category.store') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Add Category</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="inputName5" class="form-label">Base Item Name</label>
                                        </strong><span class="required-classes">*</span>
                                        <input type="text" id="name-input" name="name"
                                            onchange="check_category_name()" class="form-control" required>
                                    </div><br>
                                    {{-- <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Base Price (Rs/MT)</label><span
                                            class="required-classes">*</span>
                                        <input type="number" id="edit-input" name="price" min="1" class="form-control" required>
                                    </div>
                                    <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Margin Amt (Rs/MT)</label><span
                                            class="required-classes">*</span>
                                        <input type="number" id="edit-input" name="margin"  min="1" class="form-control" required>
                                    </div> --}}
                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
    function check_category_name() {
        //get name from input
        let name = $('#name-input').val();
        // console.log(name);
        $.ajax({
            url: "{{ url('get_category_name') }}",
            method: "post",
            data: {
                name: name,
                "_token": "{{ csrf_token() }}",
            },
            success: function(res) {
                // console.log(res);
                let category_name = res.name;
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
        // Reset specific input fields in the row
        $(`#name-input`).val('').trigger('change');
    }
</script>
