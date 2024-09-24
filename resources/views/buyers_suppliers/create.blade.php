@extends('layouts.main')
@section('title', 'Buyser Supliers - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        @if ($errors->any())
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-xmark-circle error"></i>
                    <div class="message">
                        <span class="text text-1">Error</span>
                        <span class="text text-2">Company Create UnSuccessfully</span>
                    </div>
                </div>
        @endif


        <div class="dashboard-header pagetitle">
            <h1>Add Buyers & Suppliers</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
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
                            <form class="row g-3" method="post" action="{{ route('buyers.store') }}">
                                @csrf
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Company Name</label><span
                                        class="required-classes">*</span>
                                    <input type="text" name="company_name" onchange="check_buyer_supplier_name()"
                                        class="form-control" id="name-input" required>
                                    @if ($errors->has('company_name'))
                                        <p class="error">{{ $errors->first('company_name') }}</p>
                                    @endif
                                </div>
                                @php
                                    $current_date = date('Y-m-d');
                                @endphp
                                
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputName5" class="form-label">Type</label><span
                                                class="required-classes">*</span>
                                            <select class="form-select sub_category_select subcategory-select" onchange="check_selected_type(this.value)"
                                                name="type" required>
                                                <option value="" selected disabled>Select Type </option>
                                                <option value="buyer">Buyer </option>
                                                <option value="supplier">Supplier</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6" style="display: none" id="virtual_div">
                                            <label for="inputName5" class="form-label">Virtual Store</label><span
                                                class="required-classes">*</span>
                                            <input type="text" name="virtual_store" class="form-control" id="name-input"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Mobile</label><span
                                        class="required-classes">*</span>
                                    <input type="tel" name="mobile" maxlength="10" class="form-control" id="inputName5"
                                        required pattern="\d{10}" title="Please enter a 10-digit mobile number">

                                    @if ($errors->has('mobile'))
                                        <p class="error">{{ $errors->first('mobile') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="inputName5" class="form-label">Email</label><span
                                        class="required-classes">*</span>
                                    <input type="email" name="email" class="form-control" id="inputName5" required>

                                    @if ($errors->has('email'))
                                        <p class="error">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>

                                <div class="col-6">
                                    <label for="inputName5" class="form-label">Address</label><span
                                        class="required-classes">*</span>
                                    <textarea class="form-control" name="address" placeholder="Address" id="floatingTextarea" style="height: 50px;"
                                        required></textarea>
                                    @if ($errors->has('address'))
                                        <p class="error">{{ $errors->first('address') }}</p>
                                    @endif
                                </div>
                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
        function check_selected_type(value){
            if(value == 'supplier'){
                $('#virtual_div').css('display', 'block');
            }else{
                $('#virtual_div').css('display', 'none');
            }
        }

        function check_buyer_supplier_name() {
            //get name from input
            let old_buyer_supplier_name = $('#name-input').val();
            // console.log(name);
            // console.log(sub_category);
            $.ajax({
                url: "{{ url('get_check_buyer_supplier_name') }}",
                method: "post",
                data: {
                    buyer_supplier_name: old_buyer_supplier_name,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res);
                    let buyer_supplier_name = res.company_name;
                    //alert for same name
                    if (old_buyer_supplier_name.toLowerCase() === buyer_supplier_name.toLowerCase()) {
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


@endsection

