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
            <h1>Edit WareHouse Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/warehouse') }}">Buyers & Suppliers</a></li>
                    <li class="breadcrumb-item">Edit Details</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Update WareHouse</h5>
                            <form class="row g-3" method="post" action="{{ route('warehouse.update', $data->id) }}">
                                @csrf
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Company Name</label><span
                                    class="required-classes">*</span>
                                    <input type="text" value="{{ $data->warehouse_title }}" name="warehouse_title"
                                        class="form-control" id="inputName5" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Mobile No</label><span
                                    class="required-classes">*</span>
                                    <input type="text" value="{{ $data->mobile }}" name="mobile" class="form-control"  oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
                                        id="inputName5" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">PAN</label>
                                    <input type="text" value="{{ $data->pan }}" name="pan" class="form-control" maxlength="10"
                                        id="inputName5" >
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">TAN</label>
                                    <input type="text" value="{{ $data->tan }}" name="tan" class="form-control" maxlength="10"
                                        id="inputName5" >
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">GSTN</label>
                                    <input type="text" value="{{ $data->gstn }}" name="gstn" class="form-control" maxlength="15"
                                        id="inputName5" >
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Registration No</label>
                                    <input type="text" value="{{ $data->registration_no }}" name="registration_no" maxlength="15"
                                        class="form-control" id="inputName5">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">CIN No</label>
                                    <input type="text" value="{{ $data->cin_no }}" name="cin_no" class="form-control" maxlength="15"
                                        id="inputName5" >
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Store Manager</label><span
                                    class="required-classes">*</span>
                                    <select name="store_manager_id" class="form-select">
                                        <option value="{{ $storemanager->store_manager_id }}">{{$storemanager->name}}</option>
                                        @foreach ($stor_manager as $stor_manager_data)
                                            <option value="{{ $stor_manager_data->id }}">{{ $stor_manager_data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">State</label><span
                                    class="required-classes">*</span>
                                    {{-- @livewire('state') --}}
                                    <select name="state" onchange="get_city_list()" id="state_id" required
                                        class=" form-select">
                                        <option value="{{ $states_name->state }}">{{ $states_name->state }}</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->state }}">{{ $state->state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">City</label><span
                                    class="required-classes">*</span>
                                    <select name="city" id="city_id" class="form-select" required>
                                        <option value="{{ $city_name->city }}">{{ $city_name->city }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Pincode</label><span
                                    class="required-classes">*</span>
                                    <input type="text" value="{{ $data->pincode }}" name="pincode" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6);"
                                        class="form-control" required id="inputName5">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Country</label><span
                                    class="required-classes">*</span>
                                    <input type="text" value="{{ $data->country }}" name="country" required
                                        class="form-control" id="inputName5">
                                </div>

                                <div class="col-6">
                                    <label for="inputName5" class="form-label">Address</label><span
                                    class="required-classes">*</span>
                                    <textarea class="form-control" value="{{ $data->address }}" name="address" placeholder="Address" required
                                        id="floatingTextarea" style="height: 100px;">{{ $data->address }}</textarea>

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
            // console.log(state_name);
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
