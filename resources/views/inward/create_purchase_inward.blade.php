@extends('layouts.main')
@section('title', 'Inward - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Add Inward</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Inward</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('inward.save') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>

                                <!-- Horizontal Form -->

                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>PO Number :
                                            </strong></label>
                                        <label for="inputEmail3" class="fw-bold col-form-label">
                                            {{ $po_number }} </label>
                                        <input type="hidden" name="po_number" id="po_document_number"
                                            value="{{ $po_number }}">

                                    </div>
                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Inward Number :
                                            </strong></label>
                                        <label for="inputEmail3" class="fw-bold col-form-label">
                                            {{ $inward_id }} </label>
                                        <input type="hidden" name="inward_id" value="{{ $inward_id }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Seller Name</strong>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="hidden" id="for_seller_companies" class="form-control"
                                            value="{{ $seller_companies->id }}" name="seller_id" id="inputPassword"
                                            required>
                                        <label for="inputEmail3" class="fw-bold col-form-label">
                                            {{ $seller_companies->company_name }} </label>
                                        <input type="hidden" class="form-control" name="seller_company_name"
                                            value="{{ $seller_companies->company_name }}" id="inputPassword" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Virtual Store :
                                            </strong></label>
                                        <label for="inputEmail3" class="fw-bold col-form-label">
                                            {{ $seller_companies->virtual_store }} </label>
                                    </div>
                                </div>
                                <?php
                                $currentDate = date('Y-m-d');
                                ?>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Date</strong><span
                                            class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" value="{{ $currentDate }}"
                                            name="date" id="inputPassword" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Remarks</strong>
                                    </label>
                                    <!-- Main Select Element -->
                                    <div class="col-sm-4">

                                        <textarea class="form-control" name="inw_remarks" id="" rows="3"></textarea>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">Select Item</h4>
                                            <button type="button" id="addRowBtn"
                                                class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1"
                                                onclick="addRow()">Add
                                                Row</button>
                                        </div>

                                        <div class="btn-list">
                                            {{-- <input type="text" id="searchInput" placeholder="Search by item name"> --}}


                                            <div style="overflow-x: scroll;">
                                                <table class="col-md-4 col-sm-4 col-xl-12 table">
                                                    <thead>
                                                        <tr>

                                                            <th>Item Category <span class="required-classes">*</span></th>
                                                            <th>Item sub category​ <span class="required-classes">*</span>
                                                            </th>
                                                            <th>PO Quantity (Q)​ <span class="required-classes">*</span>
                                                            </th>
                                                            <th>Total Inward Qty(Q)​ <span
                                                                    class="required-classes">*</span></th>
                                                            <th>Remaining Qty(Q)​ <span class="required-classes">*</span>
                                                            </th>
                                                            <th>Quantity(Q)​ <span class="required-classes">*</span></th>
                                                            <th>Action </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="myTable">
                                                        <tr></tr>
                                                    </tbody>

                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th class="text-end" style="height: 34px;    width: 263px;">
                                                                Total</th>
                                                            <th> <input type="text" class="form-control"
                                                                    name="total_weight" value="0"
                                                                    id="overall_total_weight_2"
                                                                    style="height: 34px; width: 220px;" readonly></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>

                                                </table>

                                            </div>

                                            {{-- <table id="myTable" class="col-md-4 col-sm-4 col-xl-12 table">
                                                <tfoot>
                                                    <tr>
                                                        <td style="height: 34px; width: 220px;"></td>
                                                        <td style="height: 34px; width: 220px;"></td>
                                                        <th style="height: 34px;    width: 263px;">Total</th>
                                                        <td style="width: 20px;"><input type="number"
                                                                class="form-control" name="total_pcs" value="0"
                                                                id="overall_total_pcs" placeholder="Total Weight"
                                                                style="height: 34px; width: 220px;" readonly></td>
                                                        <td> <input type="text" class="form-control"
                                                                name="total_weight" value="0"
                                                                id="overall_total_weight_2"
                                                                style="height: 34px; width: 220px;" readonly></td>
                                                    </tr>
                                                </tfoot>

                                            </table> --}}

                                            <script>
                                                var lastItemId = 1; // Initialize a global counter for item IDs

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

                                                    cell1.innerHTML = `
            <select name="item_category[]" id="item_id${lastItemId}" onchange="get_sub_category(this)" style="height: 34px; width: 220px;" class="select_item_category form-control item-select-${lastItemId}" required>
                <option value="" disabled selected>Select Item</option>
                @foreach ($category as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach  
            </select>
        `;
                                                    $('.item-select-' + lastItemId).select2();

                                                    cell2.innerHTML = `
            <select name="item_sub_category[]" id="item_sub_category${lastItemId}" onchange="get_current_quantity(this, ${lastItemId}) " style="height: 34px; width: 220px;" class="form-control subcategory-select  sub_category-item-select-${lastItemId}" required>
                <option value="" disabled selected>Item Sub Category</option>
            </select>`;
                                                    $('.sub_category-item-select-' + lastItemId).select2();

                                                    cell3.innerHTML =
                                                        `
                                        <input type="number" name="po_qty[]" id="po_qty${lastItemId}" value="0" class="form-control current-quantity" style="height: 34px" placeholder="PO Quantity (Q)"  readonly required>`;
                                                    cell4.innerHTML =
                                                        `
                                        <input type="number" name="po_qty[]" id="inward_qty${lastItemId}" value="0" class="form-control inward-quantity" style="height: 34px" placeholder="Total Inward Qty(Q)"  readonly required>`;
                                                    cell5.innerHTML =
                                                        `
                                        <input type="number" name="po_qty[]" id="remaining_qty${lastItemId}" value="0" class="form-control remaining-quantity" style="height: 34px" placeholder="Remaining Qty(Q)​ ​"  readonly required>`;

                                                    cell6.innerHTML =
                                                        `
            <input type="number" id="weight_${lastItemId}" name="weight[]" class="form-control quantity-input" value="0" style="height: 34px; width: 220px;" placeholder="Quantity(Q)​" oninput="change_Weight_value('${lastItemId}');check_current_qty('${lastItemId}');check_current_qyt_('${lastItemId}')"  required >
            <input type="hidden" id="weight_hidden_${lastItemId}"  class="form-control weight-input2" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal()" required readonly>`;


                                                    cell7.innerHTML = `
            <button class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>`;

                                                    // Focus the search box when the dropdown is opened
                                                    $('.item-select-' + lastItemId).on('select2:open', function() {
                                                        document.querySelector('.select2-search__field').focus();
                                                    });

                                                    // Focus the search box when the subcategory dropdown is opened
                                                    $('#item_sub_category' + lastItemId).on('select2:open', function() {
                                                        document.querySelector('.select2-search__field').focus();
                                                    });


                                                    lastItemId++;
                                                }



                                                function deleteRow(button) {
                                                    var row = button.parentNode.parentNode;
                                                    row.parentNode.removeChild(row);
                                                    updateOverallTotalWeight();

                                                }
                                            </script>





                                        </div>


                                        <div class="row mt-5">


                                        </div>

                                        <input type="hidden" name="company_id"
                                            class="form-control"value="{{ $seller_companies->id }}" required>
                                        <input type="hidden" name="type"
                                            class="form-control"value="{{ $inward_type }}" required>
                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-3">
                                            <button type="submit" id="submit_button"
                                                class="btn btn-primary">Submit</button>
                                            <a class="btn btn-secondary" href="{{ route('inward.index') }}">Back</a>
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
        function change_Weight_value(lastItemId) {
            updateOverallTotalWeight();
        }


        function updateOverallTotalWeight() {
            const weightInputs = document.querySelectorAll('[id^="weight_"]:not([id^="weight_hidden_"])');

            let overallTotalWeight = 0;
            weightInputs.forEach(input => {
                const weight = parseFloat(input.value) || 0;
                overallTotalWeight += weight;
            });
            const overallTotalWeightInput_2 = document.getElementById('overall_total_weight_2');
            // console.log(overallTotalWeightInput_2.value);

            // Update the overall total weight input box
            overallTotalWeightInput_2.value = overallTotalWeight.toFixed(3);
        }
    </script>









    <script>
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
        function get_subcategory_list(selectElement) {
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
                    // console.log(res);
                    let data = JSON.parse(res);

                    if (data) {

                        let priceInput = row.querySelector('.price-input-hidden');
                        let weightInput2 = row.querySelector('.weight-input2');


                        weightInput2.value = data.weight;
                        priceInput.value = data.category_price;
                        // console.log(weightInput2);

                    }
                }

            });
        }

        function check_current_qyt_(lastItemId) {
            let po_qty = parseInt($(`#po_qty${lastItemId}`).val()) || 0;
            let quantity_qty = parseInt($(`#weight_${lastItemId}`).val()) || 0;

            let buyer_id = $(`#buyer_name_id${lastItemId}`).val();
            let brand_id = $(`#brand_name_id${lastItemId}`).val();
            let bag_id = $(`#bag_name_id${lastItemId}`).val();

            let sum_enter_qty = 0;

            // Loop through all rows up to lastItemId
            for (let i = 1; i <= lastItemId; i++) {
                let enter_buyer_name_id = $(`#buyer_name_id${i}`).val();
                let enter_brand_id = $(`#brand_name_id${i}`).val();
                let enter_bag_id = $(`#bag_name_id${i}`).val();

                // Only sum quantities for rows that match the current row's brand_id and bag_id
                const current_enter_qty = parseInt($(`#weight_${i}`).val()) || 0;
                sum_enter_qty += current_enter_qty;
            }

            // Check if the sum of quantities for the matching row exceeds the current quantity
            if (sum_enter_qty > po_qty) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Total quantity cannot exceed the PO quantity'
                }).then(() => {
                    resetRow_in_check_current_qty(lastItemId);
                });
            } else if (quantity_qty > po_qty) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Quantity cannot be greater than PO quantity'
                }).then(() => {
                    resetRow_in_check_current_qty(lastItemId);
                });
            }
        }

        function resetRow_in_check_current_qty(lastItemId) {
            // Reset specific input fields in the row
            $(`#weight_${lastItemId}`).val('');
        }

        function get_current_quantity(selectElement, lastItemId) {
            const for_seller_companies = $('#for_seller_companies').val();

            const item_id = $(`#item_id${lastItemId}`).val();
            const item_sub_category = $(`#item_sub_category${lastItemId}`).val();
            const po_document_number = $('#po_document_number').val();
            // console.log(for_seller_companies, item_id, item_sub_category);

            let row_current_qty = selectElement.parentNode.parentNode;
            $.ajax({
                url: "{{ url('get_current_quantity_list_form_po') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    item_sub_category: item_sub_category,
                    seller_id: for_seller_companies,
                    po_document_number: po_document_number,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    let inward_qty = parseFloat(res.inward_qty) || 0;
                    let po_qty = 0;

                    // console.log(inward_qty, remaining_qty);
                    let currentquantityInput = row_current_qty.querySelector('.current-quantity');
                    let inwardInput = row_current_qty.querySelector('.inward-quantity');
                    let remainingInput = row_current_qty.querySelector('.remaining-quantity');
                    if (res && res.data && res.data.quantity !== undefined) {
                        currentquantityInput.value = res.data.quantity;
                        po_qty = parseFloat(res.data.quantity) || 0;
                    } else {
                        currentquantityInput.value = 0;
                    }
                    let remaining_qty = po_qty - inward_qty;
                    inwardInput.value = inward_qty;
                    if (lastItemId == 1) {
                        remainingInput.value = remaining_qty;
                    }
                    if (lastItemId != 1) {
                        set_remaining_value(lastItemId);
                    }

                },
                error: function() {
                    let currentquantityInput = row_current_qty.querySelector('.current-quantity');
                    currentquantityInput.value = 0;
                }
            });
        }

        function set_remaining_value(lastItemId) {
            let current_item_id = lastItemId - 1;

            const item_id = $(`#item_id${lastItemId}`).val();
            const item_sub_category = $(`#item_sub_category${lastItemId}`).val();

            let quantityInput = document.querySelector(`#weight_${current_item_id}`);
            let remainingInput = document.querySelector(`#remaining_qty${current_item_id}`);

            // console.log(quantityValue,remainingValue );

            let set_remainingInput = document.querySelector(`#remaining_qty${lastItemId}`);
            let quantityValue = parseFloat(quantityInput.value) || 0;
            let remainingValue = parseFloat(remainingInput.value) || 0;

            // console.log(quantityValue,remainingValue );
            let remaining_qty = remainingValue - quantityValue;

            set_remainingInput.value = remaining_qty;
            if (remaining_qty == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Opps!',
                    text: 'PO Quantity is completed'
                }).then(() => {
                    deleteRow(button);
                })
            }

        }


        function check_same_data(lastItemId) {
            const currentItemId = document.getElementById(`item_id${lastItemId}`).value;
            const currentItemSubCategory = document.getElementById(`item_sub_category${lastItemId}`).value;
            const currentLength = document.getElementById(`length_${lastItemId}`).value;

            let isDuplicate = false;

            //  check for duplicates
            for (let i = 1; i < lastItemId; i++) {
                const itemId = document.getElementById(`item_id${i}`).value;
                const itemSubCategory = document.getElementById(`item_sub_category${i}`).value;
                const length = document.getElementById(`length_${i}`).value;

                if (currentItemId === itemId && currentItemSubCategory === itemSubCategory && currentLength === length) {
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
            // Reset specific input fields in the row
            $(`#item_id${lastItemId}`).val('').trigger('change');
            $(`#item_sub_category${lastItemId}`).val('').trigger('change');
            $(`#length_${lastItemId}`).val('');
            $(`#weight_${lastItemId}`).val('');
            $(`#quantity_${lastItemId}`).val('');
        }

        function check_current_qty(lastItemId) {
            // Retrieve the values of weight and quantity
            let current_weight = parseFloat($(`#weight_${lastItemId}`).val());
            let current_qty = parseFloat($(`#quantity_${lastItemId}`).val());

            // Log the values for debugging
            // console.log(current_weight, current_qty);

            // Check if current weight is greater than current quantity
            if (current_weight > current_qty) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Weight cannot be greater than current quantity'
                }).then(() => {
                    // Reset weight input field if condition is met
                    reset_row_in_weight(lastItemId);
                });
            }
        }

        function reset_row_in_weight(lastItemId) {
            // Reset the weight input field to an empty string
            $(`#weight_${lastItemId}`).val('');
        }
    </script>
@endsection
