@extends('layouts.main')
@section('title','Sales Order - Saraswati Globals')
@section('content')
    <style>
        .smaller-font {
            font-size: 13px;
            /* Adjust the font size as needed */
        }

        .select2-container--default .select2-selection--single {
            font-size: 13px;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaaaaa63;
            border-radius: 4px;
            height: 34px;
        }
    </style>

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
        <div class=" dashboard-header pagetitle">
            <h1>Sales Order Edit </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"> Sales Order Edit</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('sales.update', $sales_orders->so_id) }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>
                                {{-- <input type="hidden" id="overall_total_weight" readonly
                                    value="{{ $sales_orders->total_weight }}" name="total_weight"> --}}
                                <input type="hidden" id="" value="{{ $sales_orders->so_type }}" name="so_type">


                                <!-- Horizontal Form -->

                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Bill
                                            To : - </strong></label>
                                    <div class="col-sm-4 mt-1">
                                        <p>{{ $company->company_name }}</p>
                                    </div>
                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-end"><strong>SO Number
                                                :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">{{ $sales_orders->so_number }}
                                        </label>
                                        <input type="hidden" name="document_number" value="{{ $sales_orders->so_number }}">
                                        <input type="hidden" name="so_type" value="{{ $sales_orders->so_type }}">
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Date</strong><span
                                            class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" name="date"
                                            value="{{ $sales_orders->date }}" id="inputPassword" required>
                                    </div>
                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-end"><strong>SO Type
                                                :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">{{ $sales_orders->so_type }}
                                        </label>
                                    </div>
                                </div><br>


                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Address : -
                                        </strong></label>
                                    <div class="col-sm-4">
                                        <p>{{ $company->address }}</p>
                                        <textarea class="form-control" name="address" placeholder="Address" id="floatingTextarea" style="height: 100px;"
                                            required readonly hidden>{{ $sales_orders->address }}</textarea>

                                    </div>

                                    <label for="inputEmail3" class="col-sm-4 col-form-label text-end"><strong>GST
                                            Type</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-2">
                                        <select class="form-select" id="selected_type" name="gst_type"
                                            value="{{ $sales_orders->gst_type }}" onchange="get_state()" disabled>
                                            @if ($sales_orders->gst_type == 'state_gst')
                                                <option value="{{ $sales_orders->gst_type }}" selected>State Gst</option>
                                            @else
                                                <option value="{{ $sales_orders->gst_type }}" selected>Central Gst</option>
                                            @endif

                                            <option value="state_gst">State GST</option>

                                            <option value="central_gst">Central GST</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-8">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Payment
                                            Mandatory</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        @if ($sales_orders->payment_mandatory == 'yes')
                                            <input class="form-check-input" type="radio" name="payment_mandatory"
                                                id="gridRadios1" value="yes" checked>
                                        @else
                                            <input class="form-check-input" type="radio" name="payment_mandatory"
                                                id="gridRadios1" value="yes">
                                        @endif
                                        <label class="form-check-label" for="gridRadios1">
                                            Yes
                                        </label><br>
                                        @if ($sales_orders->payment_mandatory == 'no')
                                            <input class="form-check-input" type="radio" name="payment_mandatory"
                                                id="gridRadios2" value="no" checked>
                                        @else
                                            <input class="form-check-input" type="radio" name="payment_mandatory"
                                                id="gridRadios2" value="no">
                                        @endif
                                        <label class="form-check-label" for="gridRadios2">
                                            No
                                        </label>
                                    </div>
                                </div><br>

                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Warehouse
                                        </strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="warehouse_id" name="warehouse_id" required>
                                            <option value="{{ $sales_orders->warehouse_id }}">
                                                {{ $sales_orders->warehouse_title }}</option>
                                            @foreach ($warehouse as $warehouses)
                                                @if ($warehouses->id == $sales_orders->warehouse_id)
                                                    @continue
                                                @endif
                                                <option value="{{ $warehouses->id }}">{{ $warehouses->warehouse_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div><br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Select Item</h5>

                                        <div class="col-md-12 col-sm-12 mb-30">
                                            <div class="pd-20 card-box height-100-p">
                                                <div class="row">
                                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">
                                                    </h4>
                                                    @if ($sales_orders->status == 'pending')
                                                        @can('price')
                                                            <button type="button" id="addRowBtn"
                                                                class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1"
                                                                onclick="addRow()">Add
                                                                Row</button>
                                                        @endcan
                                                    @endif
                                                </div>

                                                <div class="btn-list">
                                                    {{-- <input type="text" id="searchInput" placeholder="Search by item name"> --}}


                                                    <div style="overflow-x: scroll;">
                                                        <table class="col-md-4 col-sm-4 col-xl-12 table">
                                                            <thead>
                                                                <tr>
                                                                    <th class="smaller-font">Item Category <span
                                                                            class="required-classes">*</span>
                                                                    </th>
                                                                    <th class="smaller-font">Item SubCategory<span
                                                                            class="required-classes">*</span>
                                                                    </th>

                                                                    <th class="smaller-font">Qty <span
                                                                            class="required-classes">*</span></th>

                                                                    <th class="smaller-font">length<span
                                                                            class="required-classes">*</span></th>
                                                                    <th class="smaller-font">UOM Type <span
                                                                            class="required-classes">*</span></th>
                                                                    <th class="smaller-font">PCs <span
                                                                            class="required-classes">*</span></th>
                                                                    <th class="smaller-font">Weight(kg) <span
                                                                            class="required-classes">*</span></th>
                                                                    @can('price')
                                                                        <th class="smaller-font">Price(/kg)<span
                                                                                class="required-classes">*</span>
                                                                        </th>

                                                                        <th class="smaller-font">Tax(%)<span
                                                                                class="required-classes">*</span></th>
                                                                        <th class="smaller-font">Amount<span
                                                                                class="required-classes">*</span>
                                                                        </th>
                                                                        @if ($sales_orders->status == 'pending')
                                                                            <th>Action </th>
                                                                        @else
                                                                            <th class="smaller-font">Rest Pcs </th>
                                                                        @endif
                                                                    @endcan
                                                                </tr>
                                                            </thead>
                                                            <tbody id="myTable">
                                                                <tr></tr>
                                                            </tbody>
                                                            @can('price')
                                                                <tfoot>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <td style="height: 34px; width: 58px">Total</td>
                                                                        <td style="height: 34px; width: 105px">
                                                                            <input type="text"
                                                                                class="form-control smaller-font"
                                                                                name="total_pcs" id="overall_total_pcs"
                                                                                style="height: 34px; width: 105px; " required
                                                                                readonly>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                class="form-control smaller-font"
                                                                                name="total_weight" id="overall_total_weight"
                                                                                style="height: 34px; width: 105px; " required
                                                                                readonly>
                                                                        </td>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                </tfoot>
                                                            @endcan

                                                        </table>
                                                    </div>

                                                    <script>
                                                        var lastItemId = 0;

                                                        function fetchrow() {
                                                            var table = document.getElementById("myTable");
                                                            @foreach ($so_items as $so_item)
                                                                var newRow = table.insertRow(table.rows.length);

                                                                var cell1 = newRow.insertCell(0);
                                                                var cell2 = newRow.insertCell(1);
                                                                var cell3 = newRow.insertCell(2);
                                                                var cell4 = newRow.insertCell(3);
                                                                var cell5 = newRow.insertCell(4);
                                                                var cell6 = newRow.insertCell(5);
                                                                var cell7 = newRow.insertCell(6);
                                                                var cell8 = newRow.insertCell(7);
                                                                var cell9 = newRow.insertCell(8);
                                                                var cell10 = newRow.insertCell(9);
                                                                var cell11 = newRow.insertCell(10);

                                                                cell1.innerHTML = `
                                                                    <select name="item_category[]" id="item_id${lastItemId}" onchange="get_subcategory(this);  category_reset(${lastItemId});" style="height: 34px; width: 150px;" class="form-control smaller-font item-select-${lastItemId}" required>
                                                                        <option value="{{ $so_item->category_id }}" selected>{{ $so_item->name }}</option>
                                                                        @foreach ($category as $categories)
                                                                            <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                `;
                                                                $('.item-select-' + lastItemId).select2();

                                                                cell2.innerHTML = `
                                                                    <select name="item_subcategory[]" class="form-control smaller-font subcategory-select" onchange="get_subcategory_details(this);  category_reset(${lastItemId});" id="subcategory_${lastItemId}" style="height: 34px; width: 150px;" required>
                                                                        <option value="{{ $so_item->item_subcategory }}" selected>{{ $so_item->sub_category }}</option>
                                                                            @foreach ($sub_category as $data)
                                                                            @if (!empty($data->id))
                <option value="{{ $data->id }}">{{ $data->sub_category }}</option>
                @endif
            @endforeach
                                                                    </select>
                                                                `;
                                                                $('.subcategory-select').select2();

                                                                cell3.innerHTML = `
                                                                    <input type="number" name="qty[]" id="qty_${lastItemId}" class="form-control smaller-font" min="1" style="height: 34px; width: 105px" value="{{ $so_item->qty }}" placeholder="Qty" oninput="calculateTotal(${lastItemId}); change_status('${lastItemId}');" required>
                                                                `;

                                                                cell4.innerHTML = `
                                                                    <input type="number" name="length[]" id="length_${lastItemId}" class="form-control smaller-font" min="1" style="height: 34px; width: 105px" value="{{ $so_item->length }}" placeholder="Length" oninput="calculateTotal(${lastItemId}); change_status('${lastItemId}'); check_same_data('${lastItemId}');" required>
                                                                `;

                                                                cell5.innerHTML =
                                                                    `
                                                                    <div class="toggle-switch-container" style="display: flex; align-items: center; height: 34px;">
                                                                        <span style="margin-right: 8px;">PCs</span>
                                                                        <label class="toggle-switch">
                                                                            @if ($so_item->uom_type == 'weight')
                                                                            <input type="checkbox" id="uom_${lastItemId}" class="uom-checkbox" value="{{ $so_item->uom_type }}" oninput="change_status('${lastItemId}')"  checked>
                                                                            @else
                                                                            <input type="checkbox" id="uom_${lastItemId}" class="uom-checkbox" value="{{ $so_item->uom_type }}" oninput="change_status('${lastItemId}')" >
                                                                            @endif

                                                                            <span class="slider"></span>

                                                                        </label>
                                                                        <span style="margin-left: 8px;">Kg</span>
                                                                    </div>
                                                                    <input type="hidden" id="uom_main_${lastItemId}"  value="{{ $so_item->uom_type }}"  name="uom[]">`;

                                                                @if ($so_item->uom_type == 'pcs')
                                                                    cell6.innerHTML = `
                                                                    <input type="number" name="pcs[]" id="pcs_${lastItemId}" class="form-control smaller-font" style="height: 34px; width: 105px; background-color: rgb(0, 255, 255) !important;" value="{{ $so_item->pcs }}" placeholder="PCs" oninput="calculateTotal(${lastItemId})" required readonly>
                                                                `;

                                                                    cell7.innerHTML = `
                                                                    <input type="text" id="weight_${lastItemId}" name="weight[]" value="{{ $so_item->so_weight }}" class="form-control weight-input smaller-font" style="height: 34px; width: 101px" placeholder="weight" oninput="calculateTotal(${lastItemId})" required readonly>
                                                                    <input type="hidden" id="weight_hidden_${lastItemId}" class="form-control weight-input2" value="{{ $so_item->weight }}" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal(${lastItemId})" required readonly>
                                                                `;
                                                                @else

                                                                    cell6.innerHTML = `
                                                                    <input type="number" name="pcs[]" id="pcs_${lastItemId}" class="form-control smaller-font" style="height: 34px; width: 105px;" value="{{ $so_item->pcs }}" placeholder="PCs" oninput="calculateTotal(${lastItemId})" required readonly>
                                                                `;

                                                                    cell7.innerHTML = `
                                                                    <input type="text" id="weight_${lastItemId}" name="weight[]" value="{{ $so_item->so_weight }}" class="form-control smaller-font weight-input" style="height: 34px; width: 101px; background-color: rgb(0, 255, 255) !important;" placeholder="weight" oninput="calculateTotal(${lastItemId})" required readonly>
                                                                    <input type="hidden" id="weight_hidden_${lastItemId}" class="form-control weight-input2" value="{{ $so_item->weight }}" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal(${lastItemId})" required readonly>
                                                                `;
                                                                @endif

                                                                // cell8.innerHTML = `
            //     <input type="text" id="price_${lastItemId}" name="price[]" value="{{ $so_item->so_price }}" class="form-control price-input" style="height: 34px; width: 101px" placeholder="price" oninput="calculateTotal(${lastItemId})" required>
            // `;


                                                                @can('price')
                                                                    cell8.innerHTML =
                                                                        `
        <input type="text" id="price_${lastItemId}" name="price[]" class="form-control smaller-font price-input" style="height: 34px; width: 101px" placeholder="price" value="{{ $so_item->so_price }}" oninput="calculateTotal(${lastItemId})" required>`;


                                                                    cell9.innerHTML = `
                                                                        <select class="form-control gst_percent-select smaller-font" name="gst_percent[]" id="gst_percent_${lastItemId}" onchange="calculateTotal(${lastItemId})" required style="flex: 1;">
                                                                            <option value="{{ $so_item->gst_percent }}" selected>{{ $so_item->gst_percent }}</option>

                @foreach ($gstsetting as $gst_setting)
                <option value="{{ $gst_setting->percent }}">{{ $gst_setting->gst_prefix }}</option>
               @endforeach
                                                                        </select>
                                                                `;

                                                                    if (document.getElementById('selected_type').value === 'state_gst') {
                                                                        cell9.innerHTML += `
                                                                       <div id="cgst" class="mt-2" style="display: flex; gap: 2px;">
                                                                            <input type="text" name="sgst[]" id="sgst_${lastItemId}" class="form-control sgst-value smaller-font" value="{{ $so_item->sgst }}" placeholder="SGST" onchange="calculateTotal(${lastItemId})" required readonly style="flex: 1; min-width: 100px;">
                                                                            <input type="text" name="cgst[]" id="cgst_${lastItemId}" class="form-control cgst-value smaller-font" value="{{ $so_item->cgst }}" placeholder="CGST" onchange="calculateTotal(${lastItemId})" required readonly style="flex: 1; min-width: 100px;">
                                                                        </div>
                                                                    `;
                                                                    } else {
                                                                        cell9.innerHTML += `
                                                                        <div id="igst" class="mt-2">
                                                                            <input type="text" name="igst[]" id="igst_${lastItemId}" class="form-control igst-value smaller-font" value="{{ $so_item->igst }}" placeholder="IGST" onchange="calculateTotal(${lastItemId})" required readonly style="flex: 1; min-width: 100px;">
                                                                        </div>
                                                                    `;
                                                                    }

                                                                    cell9.innerHTML += `</div>`;

                                                                    cell10.innerHTML = `
                                                                    <input type="text" name="amount[]" id="amount${lastItemId}" class="form-control igst_value smaller-font" style="height: 34px; width: 101px" value="{{ $so_item->amount }}" placeholder="Amount" onchange="calculateTotal(${lastItemId})" required readonly>
                                                                `;


                                                                    @if ($sales_orders->status == 'pending')
                                                                        cell11.innerHTML = `
        <button class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>`;
                                                                    @else
                                                                        cell11.innerHTML = `
            <div id="" class="">
                <input type="text" class="form-control smaller-font" placeholder=""  value="{{ $so_item->rest_pcs }}" style="height: 34px; width: 101px" readonly>
            </div>`;
                                                                    @endif
                                                                @endcan

                                                                // Focus the search box when the dropdown is opened
                                                                $('.item-select-' + lastItemId).on('select2:open', function() {
                                                                    document.querySelector('.select2-search__field').focus();
                                                                });

                                                                // Focus the search box when the subcategory dropdown is opened
                                                                $('#subcategory_' + lastItemId).on('select2:open', function() {
                                                                    document.querySelector('.select2-search__field').focus();
                                                                });
                                                                lastItemId++;
                                                            @endforeach
                                                        }

                                                        function deleteRow(button) {
                                                            var row = button.parentNode.parentNode;
                                                            var table = document.getElementById("myTable");
                                                            row.parentNode.removeChild(row);
                                                            lastItemId--;
                                                            calculateTotal(lastItemId);
                                                        }
                                                    </script>


                                                    <script>
                                                        var lastItemId = {{ $count - 1 }}; // Initial Item ID

                                                        function addRow() {
                                                            var table = document.getElementById("myTable");
                                                            var newRow = table.insertRow(table.rows.length);

                                                            var cell1 = newRow.insertCell(0);
                                                            var cell2 = newRow.insertCell(1);
                                                            var cell3 = newRow.insertCell(2);
                                                            var cell4 = newRow.insertCell(3);
                                                            var cell5 = newRow.insertCell(4);
                                                            var cell6 = newRow.insertCell(5);
                                                            var cell7 = newRow.insertCell(6);
                                                            var cell8 = newRow.insertCell(7);
                                                            var cell9 = newRow.insertCell(8);
                                                            var cell10 = newRow.insertCell(9);
                                                            var cell11 = newRow.insertCell(10);

                                                            cell1.innerHTML = `
        <select name="item_category[]" id="item_id${lastItemId}" onchange="get_subcategory(this); category_reset(${lastItemId});" style="height: 34px; width: 150px;" class="form-control item-select-${lastItemId} smaller-font" required>
            <option value="" disabled selected>Select Item</option>
            @foreach ($category as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>`;
                                                            $('.item-select-' + lastItemId).select2();

                                                            cell2.innerHTML = `
        <select name="item_subcategory[]" class="form-control subcategory-select smaller-font" onchange="get_subcategory_details(this);  category_reset(${lastItemId});" id="subcategory_${lastItemId}" style="height: 34px; width: 150px;" required>
            <option value="" selected>Select Subcategory</option>
           
        </select>`;
                                                            $('.subcategory-select').select2();

                                                            cell3.innerHTML =
                                                                `
        <input type="number" name="qty[]" id="qty_${lastItemId}" class="form-control smaller-font" min="1" style="height: 34px; width: 105px" placeholder="Qty" value="0" oninput="change_status('${lastItemId}')" required>`;

                                                            cell4.innerHTML =
                                                                `
        <input type="number" name="length[]" id="length_${lastItemId}" class="form-control smaller-font" min="1" style="height: 34px; width: 105px" placeholder="Length" value="0" oninput="change_status('${lastItemId}'); check_same_data('${lastItemId}');" required>`;

                                                            cell5.innerHTML = `
        <div class="toggle-switch-container" style="display: flex; align-items: center; height: 34px;">
            <span style="margin-right: 8px;">PCs</span>
            <label class="toggle-switch">
                <input type="checkbox" id="uom_${lastItemId}" class="uom-checkbox" oninput="change_status('${lastItemId}')" name="uom_${lastItemId}">
                <span class="slider"></span>
            </label>
            <span style="margin-left: 8px;">Kg</span>
        </div>
        <input type="hidden" id="uom_main_${lastItemId}" name="uom[]">`;

                                                            cell6.innerHTML =
                                                                `
        <input type="text" name="pcs[]" id="pcs_${lastItemId}" class="form-control smaller-font" style="height: 34px; width: 105px" placeholder="PCs" oninput="calculateTotal(${lastItemId})" required readonly>`;

                                                            cell7.innerHTML =
                                                                `
        <input type="text" id="weight_${lastItemId}" name="weight[]" class="form-control weight-input smaller-font" style="height: 34px; width: 101px" placeholder="weight" oninput="calculateTotal(${lastItemId})" required readonly>
        <input type="hidden" id="weight_hidden_${lastItemId}" class="form-control weight-input2" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal(${lastItemId})" required readonly>`;

                                                            @if (auth()->user()->roles->contains('name', 'Admin'))
                                                                cell8.innerHTML =
                                                                    `
        <input type="text" id="price_${lastItemId}" name="price[]" class="form-control price-input smaller-font" style="height: 34px; width: 101px" placeholder="price" oninput="calculateTotal(${lastItemId})"  required>`;
                                                            @else
                                                                cell8.style.display = "none";
                                                                cell8.innerHTML =
                                                                    `
        <input type="hidden" name="price[]" class="form-control price-input" id="price_${lastItemId}" oninput="calculateTotal(${lastItemId})">`;
                                                            @endif

                                                            cell9.innerHTML = `
            <select class="form-control gst_percent-select smaller-font" name="gst_percent[]" id="gst_percent_${lastItemId}" onchange="calculateTotal(${lastItemId})" required style="flex: 0.5;">
                <option value="">Select %</option>
                @foreach ($gstsetting as $gst_setting)
                <option value="{{ $gst_setting->percent }}">{{ $gst_setting->gst_prefix }}</option>
               @endforeach
            </select>`;

                                                            if (document.getElementById('selected_type').value === 'state_gst') {
                                                                cell9.innerHTML += `
           <div id="cgst" class="mt-2" style="display: flex; gap: 2px;">
                <input type="text" name="sgst[]" id="sgst_${lastItemId}" class="form-control sgst-value smaller-font" value="0" placeholder="SGST" onchange="calculateTotal(${lastItemId})" required readonly style="flex: 1; min-width: 100px;">
                <input type="text" name="cgst[]" id="cgst_${lastItemId}" class="form-control cgst-value smaller-font" value="0" placeholder="CGST" onchange="calculateTotal(${lastItemId})" required readonly style="flex: 1; min-width: 100px;">
            </div>`;
                                                            } else {
                                                                cell9.innerHTML += `
            <div id="igst" class="mt-2">
                <input type="text" name="igst[]" id="igst_${lastItemId}" class="form-control igst-value smaller-font" value="0" placeholder="IGST" onchange="calculateTotal(${lastItemId})" required readonly style="flex: 1; min-width: 100px;">
            </div>`;
                                                            }

                                                            cell9.innerHTML += `</div>`;

                                                            cell10.innerHTML =
                                                                `
        <input type="text" name="amount[]" value="0" id="amount${lastItemId}" class="form-control smaller-font" style="height: 34px; width: 101px" placeholder="Amount" onchange="calculateTotal(${lastItemId})" required readonly>`;

                                                            cell11.innerHTML = `
        <button class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>`;


                                                            // Focus the search box when the dropdown is opened
                                                            $('.item-select-' + lastItemId).on('select2:open', function() {
                                                                document.querySelector('.select2-search__field').focus();
                                                            });

                                                            // Focus the search box when the subcategory dropdown is opened
                                                            $('#subcategory_' + lastItemId).on('select2:open', function() {
                                                                document.querySelector('.select2-search__field').focus();
                                                            });

                                                            lastItemId++;
                                                        }

                                                        function deleteRow(button) {
                                                            var row = button.parentNode.parentNode;
                                                            var table = document.getElementById("myTable");
                                                            row.parentNode.removeChild(row);
                                                            lastItemId--;
                                                            calculateTotal(lastItemId);
                                                            updateOverallTotalWeight();
                                                        }
                                                    </script>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-5">
                                            <div class="col-lg-6">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label"><strong>Remarks

                                                    </strong></label>
                                                <textarea class="form-control" name="remarks" placeholder="term_and_condition" id="floatingTextarea"
                                                    style="height: 100px;">{{ $sales_orders->remarks }}</textarea>
                                            </div>
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-4 ">
                                                <div class="row">

                                                    {{-- <div class="row ps-3">
                                                        <div class="col-lg-6 mb-2">
                                                            <label for="inputPassword3" class="  col-form-label"><strong>
                                                                    Total PCs</strong> </label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" name="total_pcs"
                                                                id="overall_total_pcs"
                                                                value="{{ $sales_orders->total_pcs }}" required readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row ps-3">
                                                        <div class="col-lg-6 mb-2">
                                                            <label for="inputPassword3" class="  col-form-label"><strong>
                                                                    Total Weight</strong> </label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control"
                                                                name="total_weight" id="overall_total_weight"
                                                                value="{{ $sales_orders->total_weight }}" required
                                                                readonly>
                                                        </div>
                                                    </div> --}}
                                                    @can('price')
                                                        <div class="row ps-3" id="">
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="inputPassword3" class="  col-form-label"><strong>
                                                                        Total Amount</strong> </label>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input type="text" class="form-control" name="sub_total"
                                                                    id="material_value" required readonly
                                                                    value="{{ $sales_orders->sub_total }}">
                                                            </div>
                                                        </div>
                                                        <div class="row ps-3">
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="inputPassword3"
                                                                    class="  col-form-label"><strong>Loading/Cutting</strong><span
                                                                        class="required-classes">*</span></label>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input type="number" class="form-control" min="0"
                                                                    name="loading_cutting" id="loading"
                                                                    value="{{ $sales_orders->loading_charges }}" required>
                                                            </div>
                                                        </div>


                                                        <div class="row ps-3">
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="inputPassword3"
                                                                    class="  col-form-label"><strong>Additional
                                                                        Charges</strong><span
                                                                        class="required-classes">*</span></label>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input type="number" class="form-control" min="0"
                                                                    name="additional_charges"
                                                                    value="{{ $sales_orders->additional_charges }}"
                                                                    id="additional_charges" required>
                                                            </div>
                                                        </div>
                                                        <div class="row ps-3">
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="inputPassword3"
                                                                    class="  col-form-label"><strong>Freight </strong><span
                                                                        class="required-classes">*</span></label>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input type="number" class="form-control"
                                                                    value="{{ $sales_orders->freight }}" name="freight"
                                                                    min="0" id="freight" required>
                                                            </div>
                                                        </div>


                                                        @if ($sales_orders->total_cgst == 0)
                                                            <div class="row ps-3" id="divIGST">

                                                                <div class="col-lg-6 mb-2">
                                                                    <label for="inputPassword3"
                                                                        class="  col-form-label"><strong>IGST</strong> </label>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="hidden" class="form-control" name=""
                                                                        id="totalIGST" required readonly
                                                                        value="{{ $sales_orders->total_igst }}">

                                                                    <input type="number" class="form-control"
                                                                        name="total_igst" id="grandigst" required readonly
                                                                        value="{{ $sales_orders->total_igst }}">
                                                                </div>
                                                                <input type="hidden" class="form-control" id="totalCGST"
                                                                    required readonly value="{{ $sales_orders->total_cgst }}">
                                                                <input type="hidden" class="form-control" id="totalSGST"
                                                                    required readonly value="{{ $sales_orders->total_sgst }}">

                                                            </div>
                                                        @endif
                                                        @if ($sales_orders->total_igst == 0)
                                                            <div class="row ps-3" id="divSGST">

                                                                <div class="col-lg-6 mb-2">
                                                                    <label for="inputPassword3"
                                                                        class="  col-form-label"><strong>SGST</strong> </label>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="hidden" class="form-control" name=""
                                                                        id="totalSGST" required readonly
                                                                        value="{{ $sales_orders->total_sgst }}">

                                                                    <input type="text" class="form-control"
                                                                        name="total_sgst" id="grandsgst" required readonly
                                                                        value="{{ $sales_orders->total_sgst }}">
                                                                </div>
                                                                <input type="hidden" class="form-control" id="totalIGST"
                                                                    required readonly value="{{ $sales_orders->total_igst }}">
                                                            </div>
                                                        @endif

                                                        @if ($sales_orders->total_igst == 0)
                                                            <div class="row ps-3" id="divCGST">
                                                                <div class="col-lg-6 mb-2">
                                                                    <label for="inputPassword3"
                                                                        class="  col-form-label"><strong>CGST</strong> </label>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="hidden" class="form-control" name=""
                                                                        id="totalCGST" required readonly
                                                                        value="{{ $sales_orders->total_cgst }}">

                                                                    <input type="text" class="form-control"
                                                                        name="total_cgst" id="grandcgst" required readonly
                                                                        value="{{ $sales_orders->total_cgst }}">
                                                                </div>
                                                                <input type="hidden" class="form-control" id="totalIGST"
                                                                    required readonly value="{{ $sales_orders->total_igst }}">
                                                            </div>
                                                        @endif
                                                        <div class="row ps-3">
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="inputPassword3"
                                                                    class="  col-form-label"><strong>Grand Total
                                                                    </strong> </label>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input type="text" class="form-control" name="grand_total"
                                                                    id="grandTotal" value="{{ $sales_orders->grand_total }}"
                                                                    required readonly>
                                                            </div>
                                                            <div class="row ps-3" id="divCGST">
                                                            </div>
                                                        </div>
                                                    @endcan

                                                </div>
                                                {{-- ..........................................................  --}}
                                            </div>

                                        </div>





                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-3">
                                            @if ($sales_orders->status == 'pending')
                                                @can('Sales-edit')
                                                    @can('price')
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    @endcan
                                                @endcan
                                            @endif

                                            {{-- <a class="btn btn-secondary" href="{{ route('sales.index') }}">Back</a> --}}
                                            <a class="btn btn-secondary" id="backButton">Back</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const backButton = document.getElementById('backButton');

            backButton.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior
                window.history.back();  // Go one step back in the browser history
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetchrow();
            calculateTotal();
            updateOverallTotalWeight();
        });

        function change_status(lastItemId) {
            const checkbox = document.getElementById(`uom_${lastItemId}`);
            const checkbox_main = document.getElementById(`uom_main_${lastItemId}`);
            const quantityInput = document.getElementById(`qty_${lastItemId}`);
            const weightInput = document.getElementById(`weight_${lastItemId}`);
            const weightInput2 = document.getElementById(`weight_hidden_${lastItemId}`);
            const pcsInput = document.getElementById(`pcs_${lastItemId}`);
            const lengthInput = document.getElementById(`length_${lastItemId}`);
            const quantity = parseFloat(quantityInput.value) || 0;
            const length = parseFloat(lengthInput.value) || 0;
            const weight2 = parseFloat(weightInput2.value) || 0;

            let total_weight;
            let total_pcs_qty;

            if (checkbox.checked) {
                var main_uom = 'weight';
                checkbox_main.value = main_uom;
                total_weight = length * weight2;
                weightInput.value = quantityInput.value;
                total_pcs_qty = quantityInput.value / total_weight;
                total_pcs_qty = Math.round(total_pcs_qty);
                if (total_pcs_qty - Math.floor(total_pcs_qty) > 0.5) {
                    total_pcs_qty = Math.ceil(total_pcs_qty);
                } else {
                    total_pcs_qty = Math.floor(total_pcs_qty);
                }
                pcsInput.value = total_pcs_qty;



            } else {
                var main_uom = 'pcs';
                checkbox_main.value = main_uom;
                total_weight = length * weight2;
                total_weight_qty = total_weight * quantity;
                pcsInput.value = quantity;
                weightInput.value = total_weight_qty.toFixed(3);
            }
            calculateTotal(lastItemId);

            // Recalculate and update the overall total weight
            updateOverallTotalWeight();
        }

        function updateOverallTotalWeight() {
            // // Select all weight inputs
            // const weightInputs = document.querySelectorAll('[id^="weight_"]:not([id^="weight_hidden_"])');
            // let overallTotalWeight = 0;

            // // Sum up all weights
            // weightInputs.forEach(input => {
            //     const weight = parseFloat(input.value) || 0;
            //     overallTotalWeight += weight;
            // });

            // // Update the overall total weight input box
            // const overallTotalWeightInput = document.getElementById('overall_total_weight');
            // overallTotalWeightInput.value = overallTotalWeight.toFixed(2); // Assuming you want to show two decimal places
            const weightInputs = document.querySelectorAll('[id^="weight_"]:not([id^="weight_hidden_"])');
            const pcsInputs = document.querySelectorAll('[id^="pcs_"]');

            let overallTotalWeight = 0;
            let overallTotalPcs = 0;



            weightInputs.forEach(input => {
                const weight = parseFloat(input.value) || 0;
                overallTotalWeight += weight;
            });

            pcsInputs.forEach(input => {
                const pcs = parseFloat(input.value) || 0;
                overallTotalPcs += pcs;
            });

            // Update the overall total weight input box
            const overallTotalWeightInput = document.getElementById('overall_total_weight');
            const overallTotalPcsInput = document.getElementById('overall_total_pcs');

            overallTotalWeightInput.value = overallTotalWeight.toFixed(3); // Assuming you want to show two decimal places
            overallTotalPcsInput.value = overallTotalPcs.toFixed(0); // Assuming you want to show two decimal places

        }

        document.addEventListener("DOMContentLoaded", function() {
            calculateGrandTotalOnInput();

        });

        document.getElementById("freight").addEventListener("input", calculateGrandTotalOnInput);
        document.getElementById("additional_charges").addEventListener("input", calculateGrandTotalOnInput);
        document.getElementById("loading").addEventListener("input", calculateGrandTotalOnInput);


        function calculateTotal(lastItemId) {
            var table = document.getElementById("myTable");
            var rows = table.getElementsByTagName("tr");

            var subtotal = 0;
            var totalSGST = 0;
            var totalCGST = 0;
            var totalIGST = 0;
            var type = document.getElementById('selected_type').value;

            for (var i = 1; i < rows.length; i++) {
                var row = rows[i];
                var quantity = parseFloat(row.cells[2].getElementsByTagName("input")[0].value) || 0;
                var length = parseFloat(row.cells[3].getElementsByTagName("input")[0].value) || 0;
                var pcs = parseFloat(row.cells[5].getElementsByTagName("input")[0].value) || 0;
                var weight = parseFloat(row.cells[6].getElementsByTagName("input")[0].value) || 0;
                var price = parseFloat(row.cells[7].getElementsByTagName("input")[0].value) || 0;
                var gstInput = row.cells[8].getElementsByTagName("select")[0];
                var totalInput = row.cells[9].getElementsByTagName("input")[0];
                let igstInput = row.querySelector('.igst-value');
                let cgstInput = row.querySelector('.cgst-value');
                let sgstInput = row.querySelector('.sgst-value');

                var gst_percent = parseFloat(gstInput.value) || 0;

                // Calculate total before tax
                var totalBeforeTax = weight * price;
                subtotal += totalBeforeTax;
                totalInput.value = totalBeforeTax.toFixed(2);

                // Calculate SGST, CGST, or IGST based on state
                var sgst = 0,
                    cgst = 0,
                    igst = 0;
                if (type === 'state_gst') {
                    var gst_half = gst_percent / 2;
                    sgst = totalBeforeTax * gst_half / 100;
                    cgst = totalBeforeTax * gst_half / 100;
                    // if (cgst - Math.floor(cgst) > 0.5) {
                    //     cgst = Math.ceil(cgst);
                    // } else {
                    //     cgst = Math.floor(cgst);
                    //     // sgst = Math.floor(cgst);
                    // }
                    // if (sgst - Math.floor(sgst) > 0.5) {
                    //     sgst = Math.ceil(sgst);
                    // } else {
                    //     sgst = Math.floor(sgst);
                    // }

                    sgstInput.value = sgst.toFixed(2);
                    cgstInput.value = cgst.toFixed(2);
                    totalSGST += sgst;
                    totalCGST += cgst;
                } else {
                    igst = totalBeforeTax * gst_percent / 100;

                    // if (igst - Math.floor(igst) > 0.5) {
                    //     igst = Math.ceil(igst);
                    // } else {
                    //     igst = Math.floor(igst);

                    // }
                    igstInput.value = igst.toFixed(2);
                    totalIGST += igst;
                }

            }

            // Set total SGST, CGST, IGST to respective input fields
            if (type === 'state_gst') {
                document.getElementById("totalSGST").value = totalSGST.toFixed(2);
                document.getElementById("totalCGST").value = totalCGST.toFixed(2);
            } else {
                document.getElementById("totalIGST").value = totalIGST.toFixed(2);
            }

            // Set subtotal to the input field with ID "material_value"
            document.getElementById("material_value").value = subtotal.toFixed(2);

            // Calculate grand total after updating the subtotal
            calculateGrandTotal(subtotal);

        }

        function calculateGrandTotalOnInput() {
            var subtotal = parseFloat(document.getElementById("material_value").value);
            calculateGrandTotal(subtotal);
        }

        function calculateGrandTotal(subtotal) {
            var totalSGST = parseFloat(document.getElementById("totalSGST").value) || 0;
            var totalCGST = parseFloat(document.getElementById("totalCGST").value) || 0;
            var totalIGST = parseFloat(document.getElementById("totalIGST").value) || 0;
            var freight = parseFloat(document.getElementById("freight").value);
            var additional_charges = parseFloat(document.getElementById("additional_charges").value);
            var loading = parseFloat(document.getElementById("loading").value);
            var other_gst = 18;
            var freight_gst = freight * (other_gst / 100);
            var additional_charges_gst = additional_charges * (other_gst / 100);
            var loading_gst = loading * (other_gst / 100);
            var total_other_gst = freight_gst + additional_charges_gst + loading_gst;
            var totalWithoutTax = (subtotal + freight + additional_charges + loading + total_other_gst) - total_other_gst;
            var totalTax = 0;
            if (totalSGST || totalCGST) {

                var grand_total_cgst = totalCGST + (total_other_gst / 2);
                // if (grand_total_cgst - Math.floor(grand_total_cgst) > 0.5) {
                //     grand_total_cgst = Math.ceil(grand_total_cgst);
                // } else {
                //     grand_total_cgst = Math.floor(grand_total_cgst);
                // }
                document.getElementById("grandcgst").value = grand_total_cgst.toFixed(2);
                document.getElementById("grandsgst").value = grand_total_cgst.toFixed(2);


                totalSGST += total_other_gst / 2;
                totalCGST += total_other_gst / 2;

                // if (totalSGST - Math.floor(totalSGST) > 0.5) {
                //     totalSGST = Math.ceil(totalSGST);
                // } else {
                //     totalSGST = Math.floor(totalSGST);
                //     totalCGST = Math.floor(totalSGST);
                // }
                totalTax = totalSGST + totalCGST;
            } else if (totalIGST) {
                var grand_total_igst = totalIGST + total_other_gst;
                // if (grand_total_igst - Math.floor(grand_total_igst) > 0.5) {
                //     grand_total_igst = Math.ceil(grand_total_igst);
                // } else {
                //     grand_total_igst = Math.floor(grand_total_igst);
                // }
                document.getElementById("grandigst").value = grand_total_igst.toFixed(2);

                totalIGST += total_other_gst;
                // if (totalIGST - Math.floor(totalIGST) > 0.5) {
                //     totalIGST = Math.ceil(totalIGST);
                // } else {
                //     totalIGST = Math.floor(totalIGST);
                // }
                totalTax = totalIGST;

            }
            var grandTotal = totalWithoutTax + totalTax;
            grandTotal_round = Math.round(grandTotal);
            document.getElementById("grandTotal").value = grandTotal_round.toFixed(0);
            // document.getElementById("grandTotal").value = grandTotal.toFixed(2);
        }
    </script>


    <script>
        function get_subcategory(selectElement) {
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
        function get_subcategory_details(selectElement) {
            let item_id = selectElement.value;

            let row = selectElement.parentNode.parentNode; // Get the parent row of the select element
            let subcategorySelect = row.querySelector(
                '.subcategory-select'); // Find the subcategory select element in the same row

            $.ajax({
                url: "{{ url('get_subcategory_details') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    let data = JSON.parse(res);

                    if (data) {
                        let weightInput = row.querySelector('.weight-input');
                        let priceInput = row.querySelector('.price-input');
                        let weightInput2 = row.querySelector('.weight-input2');


                        let margin = data.category_margin;
                        let price = data.category_price;
                        let diff = data.difference;
                        let total = (Number(margin) + Number(price) + Number(diff)) / 1000;

                        weightInput.value = data.weight;
                        weightInput2.value = data.weight;
                        priceInput.value = total.toFixed(2);

                    }
                }

            });
        }
    </script>

    <script>
        function get_state(lastItemId) {
            console.log(lastItemId);
            $('#addRowBtn').show();
            var type = $('#selected_type').val();
            if (type === 'state_gst') {
                $('#cgst').show();
                $('#igst').hide();
                $('#divSGST').show(); // Corrected to jQuery syntax
                $('#divCGST').show(); // Corrected to jQuery syntax
                $('#divIGST').hide(); // Corrected to jQuery syntax
            } else {
                $('#cgst').hide();
                $('#igst').show();
                $('#divSGST').hide(); // Corrected to jQuery syntax
                $('#divCGST').hide(); // Corrected to jQuery syntax
                $('#divIGST').show(); // Corrected to jQuery syntax
            }


            if (type === 'state_gst') {

            } else {

            }

        }
    </script>

    <script>
        function get_sub_category(selectElement) {
            let item_id = selectElement.value;
            let row = selectElement.parentNode.parentNode;

            $.ajax({
                url: "{{ url('get_sub_category') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {

                    // Assuming res is an object with a nested array under key 'subcategory'
                    let htmldata = '<option value="">Select</option>';
                    for (let item of res.subcategory) {
                        htmldata += `
                <option value="${item.id}">${item.sub_category}</option>
            `;
                    }
                    $(.set_sub_category).html(htmldata);
                }
            });
        }
    </script>

    <script>
        function check_same_data(lastItemId) {
            console.log(lastItemId);
            const currentItemId = document.getElementById(`item_id${lastItemId}`).value;
            const currentItemSubCategory = document.getElementById(`subcategory_${lastItemId}`).value;
            const currentLength = document.getElementById(`length_${lastItemId}`).value;
            // console.log(currentItemId);

            let isDuplicate = false;

            //  check for duplicates
            for (let i = {{ $count - 1 }}; i < lastItemId; i++) {
                const itemId = document.getElementById(`item_id${i}`).value;
                // console.log(currentItemId);
                const itemSubCategory = document.getElementById(`subcategory_${i}`).value;
                const length = document.getElementById(`length_${i}`).value;

                if (currentItemId === itemId && currentItemSubCategory === itemSubCategory && currentLength === length) {
                    // if (currentItemId === itemId ) {
                    console.log('check');
                    isDuplicate = true;
                    break;
                }
            }

            if (isDuplicate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Opps!',
                    text: 'Duplicate entry found.'
                }).then(() => {
                    resetRow_in_same_data(lastItemId);
                });
            }
        }

        function resetRow_in_same_data(lastItemId) {

            let pcs = $(`#pcs_${lastItemId}`).val();
            let weight = $(`#weight_${lastItemId}`).val();
            let amount = $(`#amount${lastItemId}`).val();
            let totalWeight = $(`#overall_total_weight`).val();
            let totalPcs = $(`#overall_total_pcs`).val();
            let totalamount = $(`#material_value`).val();

            let mainWeight = (totalWeight - weight).toFixed(3);
            let mainPcs = totalPcs - pcs;
            let mainAmount = totalamount - amount;

            $(`#overall_total_weight`).val(mainWeight);
            $(`#overall_total_pcs`).val(mainPcs);
            $(`#material_value`).val(mainAmount);

            $(`#item_id${lastItemId}`).val('');
            $(`#subcategory_${lastItemId}`).val('');

            $(`#length_${lastItemId}`).val('');
            $(`#pcs_${lastItemId}`).val('');
            $(`#weight_${lastItemId}`).val('');
            $(`#price_${lastItemId}`).val('');
            $(`#gst_percent_${lastItemId}`).val('');
            $(`#qty_${lastItemId}`).val('');
            $(`#amount${lastItemId}`).val('');

            location.reload();

        }

        function category_reset(lastItemId) {

            let pcs = parseFloat($(`#pcs_${lastItemId}`).val()) || 0;
            let weight = parseFloat($(`#weight_${lastItemId}`).val()) || 0;
            let amount = parseFloat($(`#amount${lastItemId}`).val()) || 0;
            let igst = parseFloat($(`#igst_${lastItemId}`).val()) || 0;
            let cgst = parseFloat($(`#cgst_${lastItemId}`).val()) || 0;
            let sgst = parseFloat($(`#sgst_${lastItemId}`).val()) || 0;
            if (pcs == 0) {
                console.log('new row');
            } else {
                // Get values from the totals
                let totalWeight = parseFloat($(`#overall_total_weight`).val()) || 0;
                let totalPcs = parseInt($(`#overall_total_pcs`).val()) || 0;
                let totalamount = parseFloat($(`#material_value`).val()) || 0;
                let totaligst = parseFloat($(`#grandigst`).val()) || 0;
                let totalcgst = parseFloat($(`#grandcgst`).val()) || 0;
                let totalsgst = parseFloat($(`#grandsgst`).val()) || 0;

                // Calculate new totals
                let mainWeight = (totalWeight - weight).toFixed(3);
                let mainPcs = totalPcs - pcs;
                let mainAmount = (totalamount - amount).toFixed(2);
                let mainIgst = (totaligst - igst).toFixed(2);
                let mainCgst = (totalcgst - cgst).toFixed(2);
                let mainSgst = (totalsgst - sgst).toFixed(2);

                // Update totals
                $(`#overall_total_weight`).val(mainWeight);
                $(`#overall_total_pcs`).val(mainPcs);
                $(`#material_value`).val(mainAmount);
                $(`#grandigst`).val(mainIgst);
                $(`#grandcgst`).val(mainCgst);
                $(`#grandsgst`).val(mainSgst);

                // Calculate the grand total
                let totalSGST = parseFloat($(`#grandigst`).val()) || 0;
                let totalCGST = parseFloat($(`#grandcgst`).val()) || 0;
                let totalIGST = parseFloat($(`#grandsgst`).val()) || 0;
                let freight = parseFloat($(`#freight`).val()) || 0;
                let additional_charges = parseFloat($(`#additional_charges`).val()) || 0;
                let loading = parseFloat($(`#loading`).val()) || 0;

                let grand_total = (totalSGST + totalCGST + totalIGST + freight + additional_charges + loading + parseFloat(
                    mainAmount)).toFixed(2);
                $(`#grandTotal`).val(grand_total);

                // Clear the row inputs
                $(`#length_${lastItemId}`).val('');
                $(`#pcs_${lastItemId}`).val('');
                $(`#weight_${lastItemId}`).val('');
                $(`#price_${lastItemId}`).val('');
                $(`#gst_percent_${lastItemId}`).val('');
                $(`#qty_${lastItemId}`).val('');
                $(`#amount${lastItemId}`).val('');
                $(`#igst_${lastItemId}`).val('');
                $(`#cgst_${lastItemId}`).val('');
                $(`#sgst_${lastItemId}`).val('');
            }


        }
    </script>
@endsection
