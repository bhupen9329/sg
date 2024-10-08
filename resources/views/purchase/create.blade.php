@extends('layouts.main')
@section('title', 'Purchase order - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class=" dashboard-header pagetitle">
            <h1>Add Purchase Order</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Purchase Order</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('save.purchase-order') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>

                                <!-- Horizontal Form -->

                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="row  ">
                                            <label for="inputEmail3" class="col-sm-3  col-form-label"><strong>Company
                                                    Name : </strong></label>
                                            <div class="col-sm-4 ms-5">
                                                <label for="inputEmail3"
                                                    class="  col-form-label">{{ $company->company_name }}</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6 text-end pe-5">
                                        <label for="inputEmail3" class="col-sm-6 col-form-label"><strong>PO Number :
                                            </strong>{{ $po_id }}</label>
                                    </div>


                                </div>
                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Address :
                                        </strong></label>
                                    <div class="col-sm-4">


                                        <label for="inputEmail3" class="  col-form-label"> {{ $company->address }}</label>
                                    </div>

                                </div>
                                <?php
                                $currentDate = date('Y-m-d');
                                $c_due_date = (int) $custom_due_date->custom_due_date;
                                $due_date = date('Y-m-d', strtotime($currentDate . ' +' . $c_due_date . 'days'));
                                // dd($c_due_date);
                                ?>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Date
                                            Raised</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" value="{{ $currentDate }}"
                                            name="date" id="inputPassword" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Number of Days</strong>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" name="no_of_due_date"
                                            id="number_of_due_date" oninput="set_due_date()">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Due
                                            Date</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" name="due_date" id="due_date_input"
                                            value="{{ $due_date }}" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Select Item</h5>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Category <span
                                                                    class="required-classes">*</span></th>
                                                            <th scope="col">Sub Category <span
                                                                    class="required-classes">*</span></th>
                                                            <th scope="col">Quantity (Q)</strong><span
                                                                    class="required-classes">*</span></th>
                                                            <th scope="col">Rate</strong> </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td scope="row">
                                                                <select class="form-select category_select"
                                                                    id="name-input"onchange="get_sub_category(this)"
                                                                    name="category_id" required>
                                                                    <option value="" selected disabled>Select Category
                                                                    </option>
                                                                    @foreach (\App\Models\Category::all() as $c_item)
                                                                        <option value="{{ $c_item->id }}">
                                                                            {{ $c_item->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td scope="row">
                                                                <select
                                                                    class="form-select sub_category_select subcategory-select"
                                                                    name="sub_category_id" required>
                                                                    <option value="" selected disabled>Select Sub
                                                                        Category
                                                                    </option>
                                                                    {{-- @foreach ($sub_category as $c_item)
                                                                        <option value="{{ $c_item->id }}">
                                                                            {{ $c_item->sub_category }}</option>
                                                                    @endforeach --}}
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="quantity"
                                                                    placeholder="Quantity (Q)" step="any" class="form-control"
                                                                    id="inputDesignation" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="price" class="form-control"
                                                                    placeholder="Rate" id="inputName5">
                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>

                                        </div><br><br>


                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Terms &
                                                        Conditions</strong></label>
                                                <textarea class="form-control" name="remark" placeholder="Terms & Conditions" id="floatingTextarea"
                                                    style="height: 100px;"></textarea>
                                            </div>


                                        </div>

                                        <input type="hidden" name="company_id"
                                            class="form-control"value="{{ $company->id }}" required>
                                        <input type="hidden" name="po_id"
                                            class="form-control"value="{{ $po_id }}" required>
                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a class="btn btn-secondary" href="{{ route('purchase.index') }}">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



            </div>

        </section>

    </main><!-- End #main -->
    <script>
        function set_due_date() {
            let number_of_due_date = $('#number_of_due_date').val(); // Get the number of days
            let currentDate = new Date(); // Get the current date

            // Add the number of days to the current date
            currentDate.setDate(currentDate.getDate() + parseInt(number_of_due_date));

            // Format the new date as 'Y-m-d' (e.g., '2024-09-14')
            let due_date = currentDate.toISOString().split('T')[0];

            // Set the calculated due date in the input field
            $('#due_date_input').val(due_date);
        }


        function get_sub_category(selectElement) {
            let item_id = selectElement.value;
            let row = selectElement.parentNode.parentNode; // Get the parent row of the select element
            let subcategorySelect = row.querySelector(
                '.subcategory-select'); // Find the subcategory select element in the same row

            $.ajax({
                url: "{{ url('get_subcategory_list') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    let data = JSON.parse(res)
                    if (data) {
                        let htmldata = '<option value="">Select Subcategory</option>';
                        for (let item of data) {
                            htmldata += `
                    <option value="${item.id}">${item.sub_category}</option>
                `;
                        }
                        subcategorySelect.innerHTML =
                            htmldata; // Populate the subcategory select element in the same row with dynamic options
                    }
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.sub_category_select').select2();
            $('.category_select').select2();

        });
    </script>
@endsection
