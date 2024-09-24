@extends('layouts.main')
@section('title','Inward - Saraswati Globals')
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
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Seller Name :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $company->company_name }} </label>
                                    </div>
                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Inward Number :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $inward_id }} </label>
                                        <input type="hidden" name="inward_id" value="{{ $inward_id }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Address :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $company->address }} </label>
                                    </div>
                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Inward Type :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $inward_type }} </label>
                                        <input type="hidden" value="{{ $inward_type }}">
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
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Vehicle
                                            Number</strong><span class="required-classes">*</span></label>
                                    <!-- Main Select Element -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="vehicle_number" id="inputPassword"
                                            required>
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Warehouse</strong>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="hidden" id="for_warehouse" class="form-control"
                                            value="{{ $warehouse->id }}" name="warehouse_id" id="inputPassword" required>
                                        <input type="text" class="form-control" name="warehouse_title"
                                            value="{{ $warehouse->warehouse_title }}" id="inputPassword" readonly>
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


                                            <table  class="col-md-4 col-sm-4 col-xl-12 table">
                                                <thead>
                                                    <tr>

                                                        <th>Item Category <span class="required-classes">*</span></th>
                                                        <th>Item sub category​ <span class="required-classes">*</span></th>
                                                        <th>Length(ft)<span class="required-classes">*</span></th>
                                                        <th>PCs <span class="required-classes">*</span></th>
                                                        <th>Weight (kg)​ <span class="required-classes">*</span></th>
                                                        <th>Current Qty (PCs)<span class="required-classes">*</span></th>
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
                                                        <th style="height: 34px;    width: 263px;">Total</th>
                                                        <th style="width: 20px;"><input type="number"
                                                                class="form-control" name="total_pcs" value="0"
                                                                id="overall_total_pcs" placeholder="Total Weight"
                                                                style="height: 34px; width: 220px;" readonly></th>
                                                        <th> <input type="text" class="form-control"
                                                                name="total_weight" value="0"
                                                                id="overall_total_weight_2"
                                                                style="height: 34px; width: 220px;" readonly></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>


                                            </table>
                                        </div>

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
            <select name="item_sub_category[]" id="item_sub_category${lastItemId}" onchange="get_subcategory_list(this) " style="height: 34px; width: 220px;" class="form-control subcategory-select  sub_category-item-select-${lastItemId}" required>
                <option value="" disabled selected>Item Sub Category</option>
            </select>`;
                                                    $('.sub_category-item-select-' + lastItemId).select2();

                                                    cell3.innerHTML =
                                                        `
            <input type="number" min="1" name="length[]" id="length_${lastItemId}" class="form-control length-input" style="height: 34px" placeholder="Length" oninput="change_status('${lastItemId}'); check_same_data('${lastItemId}'); get_current_quantity(this)" required >`;


                                                    cell4.innerHTML =
                                                        `
            <input type="number" min="1" id="pcs_${lastItemId}" name="piece[]"  class="form-control price-input" style="height: 34px; width: 220px;" placeholder="PCs" oninput="change_status('${lastItemId}')" required  >
            <input type="hidden"   class="form-control price-input-hidden" style="height: 34px" placeholder="PCs" readonly>`;
                                                    cell5.innerHTML =
                                                        `
            <input type="number" id="weight_${lastItemId}" name="weight[]" class="form-control price-input weight-input" style="height: 34px; width: 220px;" placeholder="Weight (Kg)" oninput="change_Weight_value('${lastItemId}')" required >
            <input type="hidden" id="weight_hidden_${lastItemId}"  class="form-control weight-input2" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal()" required readonly>`;


                                                    cell6.innerHTML =
                                                        `
            <input type="text" name="current_quantity[]" id="quantity_${lastItemId}" oninput="get_current_quantity(this)" class="form-control current-quantity" style="height: 34px" placeholder="Current Qty"  readonly required>`;

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
                                            <div class="col-lg-6"></div>
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-4 ">
                                                <div class="row">
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="  col-form-label"><strong>
                                                                Godown Weight</strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="total_weight"
                                                            id="overall_total_weight" readonly>
                                                    </div>
                                                    {{-- <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="  col-form-label"><strong>
                                                                Godown Weight<span
                                                                    class="required-classes">*</span></strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control" name="godown_weight"
                                                            id="godown_weight" required>
                                                    </div>
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="  col-form-label"><strong>Plant
                                                                Weight<span class="required-classes">*</span></strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control" name="plant_weight"
                                                            id="plant_weight" required>
                                                    </div>
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="  col-form-label"><strong>Shortage</strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="shortage"
                                                            id="shortage" readonly>
                                                        <input type="hidden" class="form-control" name="shortage"
                                                            id="company_setting_shortage_value"
                                                            value="{{ $company_setting->shortage_value }}" readonly>
                                                    </div>

                                                    <p id="error_box" style="display:none; color:red;">Alert! Weight
                                                        difference percent exceeded the limit of
                                                        {{ $company_setting->shortage_value }}%</p> --}}
                                                    <style>
                                                        .custom-border-bottom {
                                                            border-bottom: 1px dashed black;
                                                        }
                                                    </style>
                                                    <input type="hidden" class="form-control" name="error_message"
                                                        id="error_message">

                                                    <div class="col-lg-12">
                                                        <h6 class="pb-2  custom-border-bottom"><strong>Unloading
                                                                Charges​</strong></h6>
                                                    </div>

                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="ps-4  col-form-label"><strong>Crane<span
                                                                    class="required-classes">*</span></strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control" value="0"
                                                            name="crane_charge" id="inputPassword" required>
                                                    </div>
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="ps-4  col-form-label"><strong>Labour<span
                                                                    class="required-classes">*</span> </strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control" value="0"
                                                            name="labour_charge" id="inputPassword" required>
                                                    </div>
                                                    <div class="col-lg-12 ">
                                                        <h6 class="pb-2  custom-border-bottom"> </h6>
                                                    </div>
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="  col-form-label"><strong>Kanta
                                                                Charge<span class="required-classes">*</span>
                                                            </strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control" value="0"
                                                            name="kanta_charge" id="inputPassword" required>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <input type="hidden" name="company_id"
                                            class="form-control"value="{{ $company->id }}" required>
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
        function change_status(lastItemId) {

            const lengthInput = document.getElementById(`length_${lastItemId}`);
            const pcsInput = document.getElementById(`pcs_${lastItemId}`);
            const weightInput = document.getElementById(`weight_${lastItemId}`);
            const weightInput2 = document.getElementById(`weight_hidden_${lastItemId}`);

            const pcs = parseFloat(pcsInput.value) || 0;
            const length = parseFloat(lengthInput.value) || 0;
            const weight2 = parseFloat(weightInput2.value) || 0;

            // console.log(weight2);

            total_pcs = length * weight2;
            total_weight = pcs * total_pcs;
            // console.log(total_weight);
            weightInput.value = total_weight.toFixed(3);

            // Recalculate and update the overall total weight
            updateOverallTotalWeight();
        }

        function change_Weight_value(lastItemId) {
            updateOverallTotalWeight();
        }

        function updateOverallTotalWeight() {
            // Select all weight inputs
            const weightInputs = document.querySelectorAll('[id^="weight_"]:not([id^="weight_hidden_"])');
            let overallTotalWeight = 0;

            // Sum up all weights
            weightInputs.forEach(input => {
                const weight = parseFloat(input.value) || 0;
                overallTotalWeight += weight;
            });

            // Update the overall total weight input box
            const overallTotalWeightInput = document.getElementById('overall_total_weight');
            const overallTotalWeightInput_2 = document.getElementById('overall_total_weight_2');

            overallTotalWeightInput.value = overallTotalWeight.toFixed(3);
            overallTotalWeightInput_2.value = overallTotalWeight.toFixed(3);


        }

        function updateOverallTotalWeight() {
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

            const overallTotalWeightInput = document.getElementById('overall_total_weight');
            const overallTotalWeightInput_2 = document.getElementById('overall_total_weight_2');
            const overallTotalPcsInput = document.getElementById('overall_total_pcs');

            // Update the overall total weight input box
            overallTotalWeightInput.value = overallTotalWeight.toFixed(3);
            overallTotalWeightInput_2.value = overallTotalWeight.toFixed(3);
            overallTotalPcsInput.value = overallTotalPcs.toFixed(0); // Assuming you want to show two decimal places



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

        function get_current_quantity(selectElement) {
            const lengthValue = selectElement.value;
            const lastItemId = selectElement.id.split('_')[1];
            const itemIdValue = $('#item_id' + lastItemId).val();
            const itemSubCategoryValue = $('#item_sub_category' + lastItemId).val();
            const for_warehouse = $('#for_warehouse').val();
            let row_current_qty = selectElement.parentNode.parentNode;
            $.ajax({
                url: "{{ url('get_current_quantity_list') }}",
                method: "POST",
                data: {
                    item_id: itemIdValue,
                    item_sub_category: itemSubCategoryValue,
                    length: lengthValue,
                    warehouse_id: for_warehouse,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res);
                    let currentquantityInput = row_current_qty.querySelector('.current-quantity');
                    if (res && res.data && res.data.piece !== undefined) {
                        currentquantityInput.value = res.data.piece;
                    } else {
                        currentquantityInput.value = 0;
                    }
                },
                error: function() {
                    let currentquantityInput = row_current_qty.querySelector('.current-quantity');
                    currentquantityInput.value = 0;
                }
            });
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
    </script>
@endsection
