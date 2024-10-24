@extends('layouts.main')
@section('title', 'Sales Order - Saraswati Globals')
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
    <main id="main" class="main">

        <div class=" dashboard-header pagetitle">
            <h1>Add Sales Order</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Sales Order</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('sales.update', $sales_order->so_id) }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>
                                {{-- <input type="hidden" id="overall_total_weight" readonly name="total_weight"> --}}
                                <!-- Horizontal Form -->

                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Bill
                                            To : - </strong></label>
                                    <div class="col-sm-4 mt-1">
                                        <p>{{ $company->company_name }}</p>
                                        <input type="hidden" class="form-control" name="company_name" id="inputText"
                                            value="{{ $company->company_name }}">
                                        <input type="hidden" value="{{ $company->id }}" name="company_id">
                                        {{-- <input type="hidden" value="{{ $so_type }}" name="so_type"> --}}


                                    </div>

                                    <div class="col-lg-6 text-end pe-5">
                                        <label for="inputEmail3" class="col-sm-6 col-form-label"><strong>SO Number :
                                            </strong>{{ $so_number }}</label>
                                        <input type="hidden" value="{{ $so_number }}" name="so_number">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Address : -
                                        </strong></label>
                                    <div class="col-sm-4">
                                        <p>{{ $company->address }}</p>
                                        <textarea class="form-control" name="address" placeholder="Address" id="floatingTextarea" style="height: 100px;"
                                            required readonly hidden>{{ $company->address }}</textarea>
                                    </div>
                                    <div class="col-lg-6 text-end pe-5">

                                    </div>

                                </div>



                                <?php
                                $currentDate = date('Y-m-d');
                                $c_due_date = (int) $custom_due_date->custom_due_date;
                                $due_date = date('Y-m-d', strtotime($currentDate . ' +' . $c_due_date . 'days'));
                                ?>

                                <!-- Date Raised Input -->
                                <div class="row mb-3">
                                    <label for="raisedDate" class="col-sm-2 col-form-label">
                                        <strong>Date Raised</strong><span class="required-classes">*</span>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" name="date"
                                            value="{{ $sales_order->date }}" id="raised_date_input" required>
                                    </div>
                                </div>



                                <!-- Number of Days Input -->
                                <div class="row mb-3">
                                    <label for="numberOfDays" class="col-sm-2 col-form-label">
                                        <strong>Number of Days</strong>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" name="no_of_due_date"
                                            value="{{ $number_of_days }}" id="number_of_due_date" oninput="set_due_date()">
                                    </div>
                                </div>

                                <!-- Due Date Input -->
                                <div class="row mb-3">
                                    <label for="dueDateInput" class="col-sm-2 col-form-label">
                                        <strong>Due Date</strong><span class="required-classes">*</span>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" name="due_date" id="due_date_input"
                                            value="{{ $sales_order->due_date }}" required>
                                    </div>
                                </div>



                                {{-- <div class="row mb-8">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Terms &
                                            Conditions</strong></label>
                                    <div class="col-sm-4">
                                        <textarea class="form-control" name="terms_condition" placeholder="Terms & Conditions" id="floatingTextarea"></textarea>
                                    </div>
                                </div><br> --}}

                                {{-- <div class="row mb-8">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Payment
                                            Mandatory</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input class="form-check-input" type="radio" name="payment_mandatory"
                                            id="gridRadios1" value="yes" required>
                                        <label class="form-check-label" for="gridRadios1">
                                            Yes
                                        </label><br>
                                        <input class="form-check-input" type="radio" name="payment_mandatory"
                                            id="gridRadios2" value="no" required>
                                        <label class="form-check-label" for="gridRadios2">
                                            No
                                        </label>
                                    </div>

                                </div><br> --}}


                                {{-- <div class="row mb-8">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>GST
                                            Type</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="selected_type" name="gst_type" required
                                            onchange="get_state(); resetLastItemId();">
                                            <option value="">Select GST Type</option>
                                            <option value="state_gst">State GST</option>
                                            <option value="central_gst">Central GST</option>
                                        </select>
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                        <br><br>
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
                                                    <button type="button" id="addRowBtn"
                                                        class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1"
                                                        onclick="addRow()" style="display: none">Add
                                                        Row</button>
                                                </div>

                                                <div class="btn-list">
                                                    {{-- <input type="text" id="searchInput" placeholder="Search by item name"> --}}


                                                    <div style="overflow-x: scroll;">
                                                        <table class="col-md-4 col-sm-4 col-xl-12 table">
                                                            <thead>
                                                                <tr>

                                                                    <style>
                                                                        th,
                                                                        td {
                                                                            font-size: 14px;
                                                                        }
                                                                    </style>

                                                                    <th class="smaller-font">Item Category <span
                                                                            class="required-classes">*</span>
                                                                    </th>
                                                                    <th class="smaller-font">Quantity(Q) <span
                                                                            class="required-classes">*</span></th>
                                                                    <th class="smaller-font">Unit Price<span
                                                                            class="required-classes">*</span>
                                                                    </th>
                                                                    <th class="smaller-font">Price </th>
                                                                    <th class="smaller-font">Action </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="myTable">
                                                                <tr></tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                      
                                                                    <th>Total</th>
                                                                    <th>
                                                                        <input type="text"
                                                                            class="form-control smaller-font"
                                                                            name="total_quantity"
                                                                            id="overall_total_quantity"
                                                                            value="{{ $sales_order->total_quantity }}"
                                                                            required readonly>
                                                                    </th>
                                                                    <th>
                                                                        <input type="text"
                                                                            class="form-control smaller-font"
                                                                            name="total_amount" id="overall_total_amount"
                                                                            value="{{ $sales_order->total_price }}"
                                                                            required readonly>
                                                                    </th>
                                                                    <th>
                                                                        <input type="text"
                                                                            class="form-control smaller-font"
                                                                            name="total_price" id="overall_total_price"
                                                                            value="{{ $sales_order->total_amount }}"
                                                                            required readonly>
                                                                    </th>
                                                            
                                                                    <th></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <script>
                                                        var lastItemId = 1; // Initial Item ID

                                                        function fetchrow() {
                                                            var table = document.getElementById("myTable");
                                                            @foreach ($so_items as $so_item)
                                                                var newRow = table.insertRow(table.rows.length);
                                                                // console.log(table);

                                                                var cell1 = newRow.insertCell(0);
                                                                var cell2 = newRow.insertCell(1);
                                                                var cell3 = newRow.insertCell(2);
                                                                var cell4 = newRow.insertCell(3);
                                                                var cell5 = newRow.insertCell(4);
                                                                var cell6 = newRow.insertCell(5);

                                                                @if($so_item->qty == $so_item->so_rest_qty)

                                                                cell1.innerHTML = `
                                                            <select name="item_category[]" id="item_id${lastItemId}"  onchange="check_same_data('${lastItemId}')" style="width:300px!important"  class="form-control item-select-${lastItemId}" required>
                                                                <option value="{{ $so_item->item_category }}" selected>{{ $so_item->name }}</option>
                                                                @foreach ($category_2 as $category)
                                                                @if ($so_item->item_category != $category->id)
                                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>`;
                                                                $('.item-select-' + lastItemId).select2();

                                                                cell2.innerHTML =
                                                                    `
                                                            <input type="number" name="qty[]" step="0.001" value="{{ $so_item->qty }}" min="0.001" id="qty_${lastItemId}" class="form-control smaller-font" oninput="calculatePrice('${lastItemId}')"  placeholder="Qty" min="1"  required>`;


                                                                cell3.innerHTML =
                                                                    `
                                                            <input type="number" name="unit_price_[]"  value="{{ $so_item->unit_price }}" step="0.01" min="0.01" id="unit_price${lastItemId}" class="form-control smaller-font" oninput="calculatePrice('${lastItemId}')"  placeholder="Amount"    required  >`;

                                                                cell4.innerHTML =
                                                                    `
                                                            <input type="text" name="price[]" id="price_${lastItemId}" value="{{ $so_item->price }}"  class="form-control smaller-font"  placeholder="Price" readonly>`;
                                                                cell5.innerHTML =
                                                                    `
                                                            <button class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>`;


                                                                // Focus the search box when the dropdown is opened
                                                                $('.item-select-' + lastItemId).on('select2:open', function() {
                                                                    document.querySelector('.select2-search__field').focus();
                                                                });

                                                                // Focus the search box when the subcategory dropdown is opened
                                                                $('#subcategory_' + lastItemId).on('select2:open', function() {
                                                                    document.querySelector('.select2-search__field').focus();
                                                                });
                                                                @else
                                                                cell1.innerHTML = `
                                                            <select name="item_category[]" id="item_id${lastItemId}" onchange="get_subcategory(this);" style="width:300px!important"  class="form-control item-select-${lastItemId}" disabled required>
                                                                <option value="{{ $so_item->item_category }}" selected>{{ $so_item->name }}</option>
                                                                @foreach ($category_2 as $category)
                                                                @if ($so_item->item_category != $category->id)
                                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>`;
                                                                $('.item-select-' + lastItemId).select2();

                                        

                                                                cell2.innerHTML =
                                                                    `
                                                            <input type="number" name="qty[]" step="0.001" value="{{ $so_item->qty }}" min="0.001" id="qty_${lastItemId}" class="form-control smaller-font" oninput="calculatePrice('${lastItemId}')" disabled placeholder="Qty" min="1"  required>`;


                                                                cell3.innerHTML =
                                                                    `
                                                            <input type="number" name="unit_price_[]"  value="{{ $so_item->unit_price }}" step="0.01" min="0.01" id="unit_price${lastItemId}" class="form-control smaller-font" disabled oninput="calculatePrice('${lastItemId}')"  placeholder="Amount"    required  >`;

                                                                cell4.innerHTML =
                                                                    `
                                                            <input type="text" name="price[]" id="price_${lastItemId}" value="{{ $so_item->price }}"  class="form-control smaller-font"  placeholder="Price"  disabled  >`;
                                                                cell5.innerHTML =
                                                    'N/A';


                                                                // Focus the search box when the dropdown is opened
                                                                $('.item-select-' + lastItemId).on('select2:open', function() {
                                                                    document.querySelector('.select2-search__field').focus();
                                                                });

                                                                // Focus the search box when the subcategory dropdown is opened
                                                                $('#subcategory_' + lastItemId).on('select2:open', function() {
                                                                    document.querySelector('.select2-search__field').focus();
                                                                });
                                                                @endif
                                                        
                                                            lastItemId++;
                                                            @endforeach
                                                        }
                                                    </script>

                                                    <script>
                                                        var lastItemId = 1; // Initial Item ID
                                                        function addRow() {
                                                            var table = document.getElementById("myTable");
                                                            var newRow = table.insertRow(table.rows.length);
                                                            // console.log(table);

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

                                                            cell1.innerHTML = `
                                                            <select name="item_category[]" id="item_id${lastItemId}" onchange="check_same_data('${lastItemId}')" style="width:300px !important"  class="form-control item-select-${lastItemId}" required>
                                                                <option value="" disabled selected>Select Item</option>
                                                                 @foreach ($category_2 as $categories)
                                                                    <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                                                                @endforeach
                                                            </select>`;
                                                            $('.item-select-' + lastItemId).select2();

                                                  

                                                            cell2.innerHTML =
                                                                `
                                                            <input type="number" name="qty[]" step="0.001" min="0.001" id="qty_${lastItemId}" class="form-control smaller-font" oninput="calculatePrice('${lastItemId}')"  placeholder="Qty" min="1"  required>`;


                                                            cell3.innerHTML =
                                                                `
                                                            <input type="number" name="unit_price_[]" value="0" step="0.01" min="0.01" id="unit_price${lastItemId}" class="form-control smaller-font" oninput="calculatePrice('${lastItemId}')"  placeholder="Amount"    required  >`;

                                                            cell4.innerHTML =
                                                                `
                                                            <input type="text" name="price[]" id="price_${lastItemId}"  class="form-control smaller-font"  placeholder="Price" readonly>`;
                                                            cell5.innerHTML =
                                                                `
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
                                                            // calculateTotal(lastItemId);
                                                            updateOveralltotal_quantity();
                                                            updateOveralloverall_total_amount();
                                                            updateOveralltotal_price();
                                                        }
                                                    </script>






                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="row">
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

                                        </div><br><br> --}}

                                        <br><br>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Terms &
                                                        Conditions</strong></label>
                                                <textarea class="form-control" name="terms_condition" placeholder="Terms & Conditions" id="floatingTextarea"
                                                    style="height: 100px;"></textarea>
                                            </div>


                                        </div>


                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <a class="btn btn-secondary" href="{{ route('sales.index') }}">Back</a>
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
        // Function to update the due date based on the entered number of days
        function set_due_date() {
            var raisedDate = document.getElementById('raised_date_input').value; // Get the raised date value
            var numberOfDays = document.getElementById('number_of_due_date').value; // Get the number of days

            if (raisedDate && numberOfDays) {
                var dueDate = new Date(raisedDate); // Convert raised date to JavaScript date
                dueDate.setDate(dueDate.getDate() + parseInt(numberOfDays)); // Add number of days to the raised date

                // Set the due date input value
                document.getElementById('due_date_input').value = dueDate.toISOString().split('T')[0];
            }
        }
    </script>


    <script>
        function calculatePrice(rowId) {
            var qty = document.getElementById(`qty_${rowId}`).value;
            var unitPrice = document.getElementById(`unit_price${rowId}`).value;
            var priceField = document.getElementById(`price_${rowId}`);

            if (qty && unitPrice) {
                var totalPrice = qty * unitPrice;
                priceField.value = totalPrice.toFixed(2); // Display the calculated price
            } else {
                priceField.value = ''; // Clear if values are missing
            }
            updateOveralltotal_quantity();
            updateOveralloverall_total_amount();
            updateOveralltotal_price();
        }


        function updateOveralltotal_quantity() {
            const weightInputs = document.querySelectorAll('[id^="qty_"]');

            let overallTotalWeight = 0;
            weightInputs.forEach(input => {
                const weight = parseFloat(input.value) || 0;
                overallTotalWeight += weight;
            });
            const overallTotalWeightInput_2 = document.getElementById('overall_total_quantity');
            // console.log(overallTotalWeightInput_2.value);

            // Update the overall total weight input box
            overallTotalWeightInput_2.value = overallTotalWeight.toFixed(3);
        }

        function updateOveralloverall_total_amount() {
            const weightInputs = document.querySelectorAll('[id^="unit_price"]');

            let overallTotalWeight = 0;
            weightInputs.forEach(input => {
                const weight = parseFloat(input.value) || 0;
                overallTotalWeight += weight;
            });
            const overallTotaloverall_total_amount = document.getElementById('overall_total_amount');
            // console.log(overallTotalWeightInput_2.value);

            // Update the overall total weight input box
            overallTotaloverall_total_amount.value = overallTotalWeight.toFixed(2);
        }

        function updateOveralltotal_price() {
            const weightInputs = document.querySelectorAll('[id^="price"]');

            let overallTotalWeight = 0;
            weightInputs.forEach(input => {
                const weight = parseFloat(input.value) || 0;
                overallTotalWeight += weight;
            });
            const overallTotalWeightInput_2 = document.getElementById('overall_total_price');
            // console.log(overallTotalWeightInput_2.value);

            // Update the overall total weight input box
            overallTotalWeightInput_2.value = overallTotalWeight.toFixed(3);
        }


        $(document).ready(function() {
            $('.virtual_sotre').select2();
            $('#addRowBtn').show();

        });


        </script>

    <script>
        function check_same_data(lastItemId) {
            const currentItemId = document.getElementById(`item_id${lastItemId}`).value;
            // console.log(currentItemId);

            let isDuplicate = false;

            //  check for duplicates
            for (let i = 1; i < lastItemId; i++) {
                const itemId = document.getElementById(`item_id${i}`).value;
                if (currentItemId === itemId) {
                    // if (currentItemId === itemId ) {
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

            $(`#item_id${lastItemId}`).val('').trigger('change');
            $(`#subcategory_${lastItemId}`).val('').trigger('change');
        }

        function resetRow_in(lastItemId) {

            let pcs = $(`#pcs_${lastItemId}`).val();
            let weight = $(`#weight_${lastItemId}`).val();
            let amount = $(`#amount${lastItemId}`).val();
            let igst = $(`#igst_${lastItemId}`).val();
            let cgst = $(`#cgst_${lastItemId}`).val();
            let sgst = $(`#sgst_${lastItemId}`).val();
            let totalWeight = $(`#overall_total_weight`).val();
            let totalPcs = $(`#overall_total_pcs`).val();
            let totalamount = $(`#material_value`).val();
            let totaligst = $(`#grandigst`).val();
            let totalcgst = $(`#grandcgst`).val();
            let totalsgst = $(`#grandsgst`).val();

            let mainWeight = (totalWeight - weight).toFixed(3);
            let mainPcs = totalPcs - pcs;
            let mainAmount = totalamount - amount;
            let mainIgst = parseFloat(totaligst - igst).toFixed(2);
            let mainCgst = parseFloat(totalcgst - cgst).toFixed(2);
            let mainSgst = parseFloat(totalsgst - sgst).toFixed(2);


            $(`#overall_total_weight`).val(mainWeight);
            $(`#overall_total_pcs`).val(mainPcs);
            $(`#material_value`).val(mainAmount);
            $(`#grandigst`).val(mainIgst);
            $(`#grandcgst`).val(mainCgst);
            $(`#grandsgst`).val(mainSgst);

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

            var totalSGST = parseFloat(document.getElementById("grandigst").value) || 0;
            var totalCGST = parseFloat(document.getElementById("grandcgst").value) || 0;
            var totalIGST = parseFloat(document.getElementById("grandsgst").value) || 0;
            var freight = parseFloat(document.getElementById("freight").value) || 0;
            var additional_charges = parseFloat(document.getElementById("additional_charges").value) || 0;
            var loading = parseFloat(document.getElementById("loading").value) || 0;

            let grand_total = (totalSGST + totalCGST + totalIGST + freight + additional_charges + loading + mainAmount);
            $(`#grandTotal`).val(grand_total);



        }

        document.addEventListener("DOMContentLoaded", function() {
            fetchrow();
        });
    </script>
@endsection
