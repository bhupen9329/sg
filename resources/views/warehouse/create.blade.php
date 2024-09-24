@extends('layouts.main')
@section('title','Warehouse - Saraswati Globals')
@section('content')
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
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Add WareHouse</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/warehouse') }}">WareHouse Details</a></li>
                    <li class="breadcrumb-item">Add</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Create New WareHouse</h5>
                            <form class="row g-3" method="post" action="{{ route('warehouse.store') }}">
                                @csrf
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Company Name</label><span
                                        class="required-classes">*</span>
                                    <input type="text" name="warehouse_title" class="form-control" id="inputName5"
                                        required>
                                </div>

                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Mobile No</label><span
                                        class="required-classes">*</span>
                                    <input type="text" name="mobile" class="form-control" id="inputName5"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">PAN</label>
                                    <input type="text" name="pan" class="form-control" id="inputName5"
                                        maxlength="10">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">TAN</label>
                                    <input type="text" name="tan" class="form-control" id="inputName5"
                                        maxlength="10">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">GSTN</label>
                                    <input type="text" name="gstn" class="form-control" id="inputName5" minlength="15"
                                        maxlength="15">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Registration No</label>
                                    <input type="text" name="registration_no" class="form-control" id="inputName5"
                                        maxlength="15">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">CIN No</label>
                                    <input type="text" name="cin_no" class="form-control" id="inputName5"
                                        maxlength="15">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Store Manager</label><span
                                        class="required-classes">*</span>
                                    <select name="store_manager_id" class="form-select" required>
                                        <option value="">Store Manager </option>
                                        @foreach ($stock_manager as $stock_manager_data)
                                            <option value="{{ $stock_manager_data->id }}">{{ $stock_manager_data->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">State</label><span
                                        class="required-classes">*</span>
                                    <select name="state" onchange="get_city_list()" id="state_id" required
                                        class="form-select">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->state }}">{{ $state->state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">City</label><span
                                        class="required-classes">*</span>
                                    <select name="city" id="city_id" class="form-select" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Country</label><span
                                        class="required-classes">*</span>
                                    <input type="text" name="country" class="form-control" id="inputName5" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Pincode</label><span
                                        class="required-classes">*</span>
                                    <input type="number" name="pincode" class="form-control" id="inputName5"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6);" required>
                                </div>

                                <div class="col-6">
                                    <label for="inputName5" class="form-label">Address</label><span
                                        class="required-classes">*</span>
                                    <textarea class="form-control" name="address" placeholder="Address" id="floatingTextarea" style="height: 100px;"
                                        required></textarea>
                                </div>

                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a class="btn btn-secondary" href="{{ route('warehouse.index') }}">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
    <script>
        function get_city_list() {
            let state_name = $('#state_id').val();
            $.ajax({
                url: "{{ url('get_city_list') }}",
                method: "POST",
                data: {
                    state_name: state_name,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    let data = JSON.parse(res)
                    if (data) {
                        let htmldata = '<option value="">Select</option>';
                        for (let item of data) {
                            htmldata += `
                                <option value="${item.city}">${item.city}</option>
                            `;
                        }
                        $('#city_id').html(htmldata);
                    }
                }
            })
        }
    </script>
@endsection
