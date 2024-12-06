@extends('layouts.main')
@section('title', 'Category - Saraswati Globals')
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
                                        <input type="text" id="name-input" name="name" class="form-control"
                                            required>
                                    </div><br>
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
    function resetRow_in_same_data() {
        // Reset specific input fields in the row
        $(`#name-input`).val('').trigger('change');
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#name-input').focus(); // Example code
    });
</script>
