@extends('layouts.main')
@section('title', 'Buyers Suppliers - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        @if ($errors->any())
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-xmark-circle error"></i>
                    <div class="message">
                        <span class="text text-1">Error</span>
                        <span class="text text-2">Company Update UnSuccessfully</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif
        <div class="pagetitle">
            <h1>Update Buyers & Suppliers</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Buyers & Suppliers</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Company Form</h5>
                            <form class="row g-3" method="post" action="{{ route('buyers.update', $companies->id) }}">
                                @csrf
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Company Name</label><span
                                        class="required-classes">*</span>
                                    <input type="text" name="company_name"
                                        onchange="check_buyer_supplier_name({{ $companies->id }})"
                                        value="{{ $companies->company_name }}" class="form-control" id="name-input"
                                        required>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputName5" class="form-label">Type</label><span
                                                class="required-classes">*</span>
                                            <select class="form-select " id="get_selected_type"
                                                onchange="check_selected_type(this.value)" name="type" required>
                                                <option value="" selected disabled>Select Type</option>
                                                <option value="buyer" {{ $companies->type == 'buyer' ? 'selected' : '' }}>
                                                    Buyer
                                                </option>
                                                <option value="supplier"
                                                    {{ $companies->type == 'supplier' ? 'selected' : '' }}>
                                                    Supplier</option>
                                            </select>

                                        </div>
                                        {{-- <div class="col-md-6" style="display: none" id="virtual_div">
                                            <label for="inputName5" class="form-label">Virtual Store</label><span
                                                class="required-classes">*</span>
                                            <input type="text" name="virtual_store" class="form-control" id="virtual_input"
                                                value="{{ $companies->virtual_store }}" >
                                        </div> --}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Mobile</label>
                                    <input type="text" name="mobile" maxlength="10" minlength="10" class="form-control"
                                        value="{{ $companies->mobile }}" id="inputName5">
                                </div>

                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ $companies->email }}" id="inputName5">
                                </div>

                                <div class="col-6">
                                    <label for="inputName5" class="form-label">Address</label><span
                                        class="required-classes">*</span>
                                    <textarea class="form-control" name="address" value="" placeholder="Address" id="floatingTextarea"
                                        style="height: 50px;" required>{{ $companies->address }}</textarea>
                                </div>
                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a class="btn btn-secondary" href="{{ route('buyers.index') }}">Back</a>
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
                        let htmldata = '<option value="">Select City</option>';
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let value = $('#get_selected_type').val();
            check_selected_type(value);
        });

        function check_selected_type(value) {

            if (value == 'supplier') {
                $('#virtual_div').css('display', 'block');
            } else {
                $('#virtual_div').css('display', 'none');
                resetRow_in_virtual_input();
            }
        }

        function resetRow_in_virtual_input() {
            // Reset specific input fields in the row
            $(`#virtual_input`).val('') ;
        }

        function check_buyer_supplier_name(company_id) {
            //get name from input
            let name = $('#name-input').val();
            let c_id = company_id;
            // console.log(cat_id);
            $.ajax({
                url: "{{ url('get_buyer_supplier_name_edit') }}",
                method: "post",
                data: {
                    name: name,
                    company_id: c_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    let company_name = res.company_name;
                    // console.log(res);
                    // console.log(category_name);
                    //alert for same name
                    if (name.toLowerCase() === company_name.toLowerCase()) {
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

<script>
    $(document).ready(function() {
        // Focus the date input when the page is loaded
        $('#name-input').focus();
    });
</script>
@endsection
