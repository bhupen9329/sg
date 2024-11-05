@extends('layouts.main')
@section('title', 'Index - Manual Matching')
@section('content')
    <style>

    </style>
    <main id="main" class="main">
        @if ($message = Session::get('Credit_note_status'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-check check"></i>
                    <div class="message">
                        <span class="text text-1">Success</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-times-circle error-icon"></i>
                    <div class="message">
                        <span class="text text-1">Error</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif

        @if ($message = Session::get('update'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-check check"></i>
                    <div class="message">
                        <span class="text text-1">Update</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif
        @if ($message = Session::get('approve'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-check check"></i>
                    <div class="message">
                        <span class="text text-1">Approve</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif

        @if ($message = Session::get('delete'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-exclamation exclamation update"></i>
                    <div class="message">
                        <span class="text text-1">Delete</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
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
            <h1>Dispatch Summary</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Open Dispatch Positions</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Dispatch Details</h5>
                            <form class="row g-3" method="post" action="{{ route('dispatch.update', $disaptch_data->dispatch_id) }}">
                                @csrf
                                <div class="row mb-3">
                                    <!-- Company Dropdown (From) -->
                                    <div class="col-md-6"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="get_miller_id" class="form-label">From</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company" id="get_miller_id" name="po_company_id"
                                            onchange="fetchPoNumbers(this)" required disabled>
                                            <option value="{{ $disaptch_data->po_company_id }}">
                                                {{ $disaptch_data->po_company }}</option>
                                        </select>
                                    </div>

                                    <!-- PO Items Dropdown (To) -->
                                    <div class="col-md-6"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="to_company_id" class="form-label">To</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company" id="to_company_id" name="so_company_id"
                                            onchange="fetchSalesOrders(this)" required disabled>
                                            <option value="{{ $disaptch_data->so_company_id }}">
                                                {{ $disaptch_data->so_company }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-4"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="to_company_id" class="form-label">Vehicle Number</label>
                                          <input type="text" class="form-control" value="{{ $disaptch_data->vehicle_number }}" name="vehicle_number">
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">Dispatch Details</h4>
                                    {{-- <button type="button" id="addRowBtn" class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1" onclick="addRow()">Add Row</button> --}}
                                </div>

                                <table id="myTable" class="col-md-4 col-sm-4 col-xl-12 table">
                                    <thead>
                                        <tr>
                                            <th class="table_heading_long">Base Item Name<span
                                                    class="required-classes">*</span></th>
                                            <th class="table_heading_long">Conv Item Name</th>
                                            <th class="table_heading_normal">Conv Rate</th>
                                            <th class="table_heading_normal">Quantity<span class="required-classes">*</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be dynamically added here -->
                                    </tbody>
                                </table>
                                <input type="hidden" id="po_item_id">
                                <input type="hidden" id="so_item_no" name="so_item_no">
                                <input type="hidden" id="po_item_no" name="po_item_no">

                                <div class="col-md-4">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" value="{{ $disaptch_data->remarks }}" placeholder="Enter remarks here...">{{ $disaptch_data->remarks }}</textarea>
                                </div>

                                <div class="text-end mt-5">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a class="btn btn-secondary" href="{{ route('dispatch.index') }}">Back</a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br><br><br>



    </main><!-- End #main -->

    <script>
        var lastItemId = 1;

        function addRow(itemName = '', itemId = '', quantity = '', unitPrice = '', subItems = []) {
            var table = document.getElementById("myTable").getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);
            let subItemOptions = '<option readonly>Select Item</option>';
            subItems.forEach(subItem => {
                subItemOptions += `<option value="${subItem.id}">${subItem.sub_category}</option>`;
            });

            newRow.innerHTML = `
            <td>${itemName}</td>
            <input type="hidden" name="cat_id[]" class="form-control" value="${itemId}" required>
            <td>
            <select name="sub_cat_id[]" class="form-select select_brand_name">${subItemOptions}</select>
        </td>
            <td><input type="text" name="conv_rate[]" class="form-control"  required /></td>
            <td><input type="number" name="quantity[]" class="form-control" value="" required /></td>
            <td>
                <button type="button" class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>
            </td>

        `;
            lastItemId++;
        }

        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }

        function fetchrow() {
            var table = document.getElementById("myTable");
            var newRow = table.insertRow(table.rows.length);
            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            var cell3 = newRow.insertCell(2);
            var cell4 = newRow.insertCell(3);

            cell1.innerHTML = `
             <td>{{ $disaptch_data->category_name }}</td>
            <input type="hidden" name="cat_id[]" class="form-control" value="{{ $disaptch_data->category_id }}" required>
           
`;
            cell2.innerHTML = `
           <select name="sub_cat_id[]" class="form-select select_brand_name">
            <option value="{{ $disaptch_data->subcategory_id }}" selected>{{ $disaptch_data->sub_category_name }}</option>
            @foreach($sub_items as $sub_item)
            @if($sub_item->id != $disaptch_data->subcategory_id)
            <option value={{ $sub_item->id }}>{{ $sub_item->sub_category }}</option>
            @endif
            @endforeach
            </select>
           
`;
            cell3.innerHTML = `
             <td><input type="number" name="conv_rate[]" class="form-control"  value="{{ $disaptch_data->conv_rate }}" min="1" required /></td>
           
`;
            cell4.innerHTML = `
             <td><input type="number" name="quantity[]" class="form-control" value="{{ $disaptch_data->dispatched_quantity }}" min="1" required /></td>
           
`;

           
         

            // Focus the search box when the dropdown is opened
            $('.item-select-' + lastItemId).on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });

            // Focus the search box when the subcategory dropdown is opened
            $('#item_sub_category' + lastItemId).on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });


        }

        document.addEventListener("DOMContentLoaded", function() {
            fetchrow();
        });
    </script>

@endsection
