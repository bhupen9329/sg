@extends('layouts.main')
@section('title','Inward - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Update Inward</h1>
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
                    <form method="POST" action="{{ route('inward.update', $inward_data->id) }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>

                                <!-- Horizontal Form -->

                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Bill to :
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
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Date</strong><span
                                            class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" value="{{ $inward_data->date }}"
                                            name="date" id="inputPassword" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Vehicle
                                            Number</strong><span class="required-classes">*</span></label>
                                    <!-- Main Select Element -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control"
                                            value="{{ $inward_data->vehicle_number }}" name="vehicle_number"
                                            id="inputPassword" required>
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
                                                        <th>Current Qty <span class="required-classes">*</span></th>
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
                                                        <th style="height: 34px;    width: 273px;">Total</th>
                                                        <th style="width: 20px;"><input type="number"
                                                                class="form-control" name="total_pcs" value="0"
                                                                id="overall_total_pcs" style="height: 34px; width: 220px;"
                                                                readonly></th>
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
                                                var lastItemId = 0;

                                                function fetchrow() {
                                                    var table = document.getElementById("myTable");
                                                    @foreach ($inward_item as $inw_item)
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
    <select name="item_category[]" id="item_id${lastItemId}" onchange="get_sub_category(this); check_same_data('${lastItemId}') " style="height: 34px; width: 220px;" class="select_item_category f  item-select-${lastItemId}" required>
        <option value="{{ $inw_item->category_id }}" selected>{{ $inw_item->category_name }}</option>
        @foreach ($category as $data)
            @if ($data->id != $inw_item->category_id)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
            @endif
        @endforeach
    </select>
`;

                                                        $('.item-select-' + lastItemId).select2();

                                                        cell2.innerHTML = `
            <select name="item_sub_category[]" id="item_sub_category${lastItemId}" onchange="get_subcategory_list(this) ; check_same_data('${lastItemId}') " style="height: 34px; width: 220px;" class="form-control subcategory-select sub_category-item-select-${lastItemId}" required>
                <option value="{{ $inw_item->sub_category_id }}" selected>{{ $inw_item->subcategory_name }}</option>
                 @foreach ($sub_category as $data)
                <option value="{{ $data->id }}">{{ $data->sub_category }}</option>
                @endforeach
            </select>`;
                                                        $('.sub_category-item-select-' + lastItemId).select2();
                                                        cell3.innerHTML =
                                                            `
            <input type="number" min="1" name="length[]" id="length_${lastItemId}" value="{{ $inw_item->length }}" class="form-control length-input" style="height: 34px; width: 220px;" placeholder="Length" oninput="change_status('${lastItemId}'); check_same_data('${lastItemId}'); get_current_quantity(this); get_weight_in_edit(this)" required >`;

                                                        cell4.innerHTML =
                                                            `
            <input type="number" min="1" id="pcs_${lastItemId}" value="{{ $inw_item->piece }}" oninput="change_status('${lastItemId}'); get_weight_in_edit(this)"  name="piece[]" class="form-control price-input" style="height: 34px; width: 220px;" placeholder="PCs" required>`;
                                                        cell5.innerHTML =
                                                            `
            <input type="text" id="weight_${lastItemId}" value="{{ $inw_item->inward_weight }}" name="weight[]" class="form-control price-input weight-input" style="height: 34px" placeholder="Weight (Kg)" oninput="change_Weight_value('${lastItemId}')" required>
            <input type="hidden" id="weight_hidden_${lastItemId}" value="{{ $inw_item->subcategory_weight }}"  class="form-control" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal()" required readonly>`;


                                                        cell6.innerHTML =
                                                            `
            <input type="text" id="current_qty${lastItemId}" name="current_quantity[]" value="{{ $inw_item->stock_item_pcs ?? 0 }}" class="form-control current-quantity" oninput="get_current_quantity(this)" style="height: 34px" placeholder="Current Qty"  readonly>`;

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
                                                var lastItemId = {{ $count - 1 }}; // Initialize a global counter for item IDs

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
            <select name="item_category[]" id="item_id${lastItemId}" onchange="get_sub_category(this) ; check_same_data('${lastItemId}')" style="height: 34px; width: 220px;" class="select_item_category form-control item-select-${lastItemId}" required>
                <option value="" disabled selected>Select Item</option>
                @foreach ($category as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </select>
        `;
                                                    $('.item-select-' + lastItemId).select2();

                                                    cell2.innerHTML = `
            <select name="item_sub_category[]" id="item_sub_category${lastItemId}" onchange="get_subcategory_list(this) ; check_same_data('${lastItemId}') " style="height: 34px; width: 220px;" class="form-control subcategory-select sub_category-item-select-${lastItemId}" required>
                <option value="" disabled selected>Item Sub Category</option>
            </select>`;
                                                    $('.sub_category-item-select-' + lastItemId).select2();
                                                    cell3.innerHTML =
                                                        `
            <input type="number" min="1" name="length[]" id="length_${lastItemId}" class="form-control length-input" style="height: 34px" placeholder="Length" oninput="change_status('${lastItemId}'); check_same_data('${lastItemId}'); get_current_quantity(this); get_weight_in_edit(this)"required >`;

                                                    cell4.innerHTML =
                                                        `
            <input type="number" min="1" id="pcs_${lastItemId}" name="piece[]" class="form-control price-input" oninput="change_status('${lastItemId}')" style="height: 34px" placeholder="PCs" required >`;
                                                    cell5.innerHTML =
                                                        `
            <input type="text" id="weight_${lastItemId}" name="weight[]" class="form-control price-input weight-input" style="height: 34px" placeholder="Weight (Kg)" oninput="change_Weight_value('${lastItemId}')" required>
            <input type="hidden" id="weight_hidden_${lastItemId}"  class="form-control weight-input2" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal()" required readonly>`;


                                                    cell6.innerHTML =
                                                        `
            <input type="text" id="current_qty${lastItemId}" name="current_quantity[]" class="form-control current-quantity" oninput="get_current_quantity(this)" style="height: 34px" placeholder="Current Qty" required readonly>`;

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
                                                            id="overall_total_weight"
                                                            value="{{ $inward_data->total_weight }}" readonly>
                                                    </div>
                                                    {{-- <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="  col-form-label"><strong>
                                                                Godown Weight<span
                                                                    class="required-classes">*</span></strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control" name="godown_weight"
                                                            id="godown_weight" value="{{ $inward_data->godown_weight }}"
                                                            required>
                                                    </div>
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="  col-form-label"><strong>Plant
                                                                Weight<span class="required-classes">*</span></strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control" name="plant_weight"
                                                            id="plant_weight" value="{{ $inward_data->plant_weight }}"
                                                            required>
                                                    </div>
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="  col-form-label"><strong>Shortage<span
                                                                    class="required-classes">*</span></strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="shortage"
                                                            id="shortage" value="{{ $inward_data->shortage }}" readonly>
                                                        <input type="hidden" class="form-control" name="shortage"
                                                            id="company_setting_shortage_value"
                                                            value="{{ $company_setting->shortage_value }}" readonly>
                                                    </div>

                                                    <p id="error_box" style="display:none; color:red;"> Alert! Weight
                                                        difference percent exceeded the limit of
                                                        {{ $company_setting->shortage_value }}%</p> --}}
                                                    <style>
                                                        .custom-border-bottom {
                                                            border-bottom: 1px dashed black;
                                                        }
                                                    </style>

                                                    <div class="col-lg-12">
                                                        <h6 class="pb-2  custom-border-bottom"><strong>Unloading
                                                                Charges​<span class="required-classes">*</span></strong>
                                                        </h6>
                                                    </div>

                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="ps-4  col-form-label"><strong>Crane<span
                                                                    class="required-classes">*</span></strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control" name="crane_charge"
                                                            id="inputPassword" value="{{ $inward_data->crane_charge }}"
                                                            required>
                                                    </div>
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="ps-4  col-form-label"><strong>Labour<span
                                                                    class="required-classes">*</span> </strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control" name="labour_charge"
                                                            id="inputPassword" value="{{ $inward_data->labour_charge }}"
                                                            required>
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
                                                        <input type="text" class="form-control" name="kanta_charge"
                                                            id="inputPassword" value="{{ $inward_data->kanta_charge }}"
                                                            required>
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
                                            @can('Inward-edit')
                                                @if ($inward_data->status != 'Approved')
                                                    <button type="submit" id="submit_button"
                                                        class="btn btn-primary">Submit</button>
                                                @endif
                                            @endcan
                                            {{-- <a class="btn btn-secondary" href="{{ route('inward.index') }}">Back</a> --}}
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
            updateOverallTotalWeight();
        });

        function change_status(lastItemId) {
           
            const lengthInput = document.getElementById(`length_${lastItemId}`);
            const pcsInput = document.getElementById(`pcs_${lastItemId}`);
            const weightInput = document.getElementById(`weight_${lastItemId}`);
            const weightInput2 = document.getElementById(`weight_hidden_${lastItemId}`);
            const pcs = parseFloat(pcsInput.value) || 0;
            const length = parseFloat(lengthInput.value) || 0;
            const weight2 = parseFloat(weightInput2.value) || 0;
            total_pcs = length * weight2;
            total_weight = pcs * total_pcs;
            weightInput.value = total_weight.toFixed(3);
            // Recalculate and update the overall total weight
            updateOverallTotalWeight();
        }

        function change_Weight_value(lastItemId) {
            updateOverallTotalWeight();
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
            overallTotalPcsInput.value = overallTotalPcs; // Assuming you want to show two decimal places



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
                    let data = JSON.parse(res);

                    if (data) {
                        // let weightInput = row.querySelector('.weight-input');
                        // let priceInput = row.querySelector('.price-input');
                        let weightInput2 = row.querySelector('.weight-input2');
                        // console.log(weightInput2, weightInput);

                        // weightInput.value = data.weight;
                        weightInput2.value = data.weight;
                        console.log(weightInput2.value);
                        // priceInput.value = data.category_price;

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



        // function check_same_data(lastItemId) {
        //     // Get the current item elements based on lastItemId
        //     const currentItemElement = document.getElementById(`item_id${lastItemId}`);
        //     const currentItemSubCategoryElement = document.getElementById(`item_sub_category${lastItemId}`);
        //     const currentLengthElement = document.getElementById(`length_${lastItemId}`);

        //     // Check if any of the elements do not exist
        //     if (!currentItemElement || !currentItemSubCategoryElement || !currentLengthElement) {
        //         return;
        //     }

        //     // Get the values of the current elements
        //     const currentItemId = currentItemElement.value;
        //     const currentItemSubCategory = currentItemSubCategoryElement.value;
        //     const currentLength = currentLengthElement.value;

        //     let isDuplicate = false;

        //     // Check for duplicates
        //     for (let i = 1; i < lastItemId; i++) {
        //         const itemElement = document.getElementById(`item_id${i}`);
        //         const itemSubCategoryElement = document.getElementById(`item_sub_category${i}`);
        //         const lengthElement = document.getElementById(`length_${i}`);

        //         // Skip iteration if any of the elements do not exist
        //         if (!itemElement || !itemSubCategoryElement || !lengthElement) {
        //             continue;
        //         }

        //         const itemId = itemElement.value;
        //         const itemSubCategory = itemSubCategoryElement.value;
        //         const length = lengthElement.value;

        //         if (currentItemId === itemId && currentItemSubCategory === itemSubCategory && currentLength === length) {
        //             isDuplicate = true;
        //             break;
        //         }
        //     }

        //     if (isDuplicate) {
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Oops!',
        //             text: 'Duplicate entry found.'
        //         }).then(() => {
        //             resetRow_in_same_data(lastItemId);
        //         });
        //     }
        // }

        function check_same_data(lastItemId) {
        const currentItemId = document.getElementById(`item_id${lastItemId}`).value;
        const currentItemSubCategory = document.getElementById(`item_sub_category${lastItemId}`).value;
        const currentLength = document.getElementById(`length_${lastItemId}`).value;
        // console.log(currentItemId);

        let isDuplicate = false;
        //  check for duplicates
        for (let i = {{ $count - 1 }}; i < lastItemId; i++) {
            const itemId = document.getElementById(`item_id${i}`).value;
            const itemSubCategory = document.getElementById(`item_sub_category${i}`).value;
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
            // Reset specific input fields in the row
            $(`#item_id${lastItemId}`).val('').trigger('change');
            $(`#item_sub_category${lastItemId}`).val('').trigger('change');
            $(`#pcs_${lastItemId}`).val('');
            $(`#length_${lastItemId}`).val('');
            $(`#weight_${lastItemId}`).val('');
            $(`#current_qty${lastItemId}`).val('');
        }
    </script>
@endsection
