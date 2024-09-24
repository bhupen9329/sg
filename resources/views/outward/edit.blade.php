@extends('layouts.main')
@section('title','Outward - Saraswati Globals')
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
            <h1>Update Outward</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">outward</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('outward.update', $outward_data->id) }}">
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
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>outward Number :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $outward_data->outward_number }} </label>
                                        <input type="hidden" name="outward_id" value="{{ $outward_data->outward_number }}">
                                        {{-- <input type="hidden" name="total_weight" id="overall_total_weight"
                                            value="{{ $outward_data->total_weight }}"> --}}
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
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>outward Type :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $outward_data->type }} </label>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Warehouse :</strong>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="hidden" class="form-control" value="{{ $warehouse->id }}"
                                            name="warehouse_id" id="warehouse_id" required>

                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $warehouse->warehouse_title }} </label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Date</strong><span
                                            class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" value="{{ $outward_data->date }}"
                                            name="date" id="inputPassword" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Vehicle
                                            Number</strong><span class="required-classes">*</span></label>
                                    <!-- Main Select Element -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control"
                                            value="{{ $outward_data->vehicle_number }}" name="vehicle_number"
                                            id="inputPassword" required>
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Loading
                                            Supervisor</strong><span class="required-classes">*</span></label>
                                    <!-- Main Select Element -->
                                    <div class="col-sm-4">
                                        <input type="text" value="{{ $outward_data->supervisor }}" class="form-control"
                                            name="supervisor" id="" required>
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

                                                        <th>Item category​ <span class="required-classes">*</span></th>
                                                        <th>Item sub category​ <span class="required-classes">*</span></th>
                                                        <th>Length(ft)<span class="required-classes">*</span></th>
                                                        <th>Qty <span class="required-classes">*</span></th>
                                                        <th>Uom type​ <span class="required-classes">*</span></th>
                                                        <th>PCs <span class="required-classes">*</span></th>
                                                        <th>Weight (kg)​ <span class="required-classes">*</span></th>
                                                        <th>Current Qty​ <span class="required-classes">*</span></th>
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
                                                        <td style="height: 34px; width: 57px">Total</td>
                                                        <td style="height: 34px; width: 105px">
                                                            <input type="text" class="form-control smaller-font"
                                                                name="total_pcs" id="overall_total_pcs"
                                                                style="height: 34px; width: 105px; " required readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control smaller-font"
                                                                name="total_weight" id="overall_total_weight"
                                                                style="height: 34px; width: 105px; " required readonly>
                                                        </td>
                                                        <th></th>
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
                                                    @foreach ($outward_item as $inw_item)
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
            <select name="item_category[]" id="item_id${lastItemId}" onchange="get_sub_category(this)" class="select_item_category item-select-${lastItemId}" required>
                <option value="{{ $inw_item->category_id }}" selected>{{ $inw_item->name }}</option>
                @foreach ($category as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </select>
        `;

                                                        $('.item-select-' + lastItemId).select2();
                                                        cell2.innerHTML = `
            <select name="item_sub_category[]" id="item_sub_category${lastItemId}" onchange="get_subcategory_list(this)" class="subcategory-select" required>
                <option value="{{ $inw_item->sub_category_id }}" selected>{{ $inw_item->sub_category }}</option>
                @foreach ($sub_category as $data)
                <option value="{{ $data->id }}">{{ $data->sub_category }}</option>
            @endforeach
            </select>`;
                                                        $('#item_sub_category' + lastItemId).select2();

                                                        cell4.innerHTML =
                                                            `
            <input type="text" id="qty_${lastItemId}" name="quantity[]" value="{{ $inw_item->quantity }}" onchange ="handleTypeChange('${lastItemId}')" class="form-control quantity-input" style="height: 34px" placeholder="Quantity" oninput="change_status('${lastItemId}')" required>`;

                                                        cell3.innerHTML =
                                                            `
            <input type="text" name="length[]" id="length_${lastItemId}" value="{{ $inw_item->length }}" class="form-control length-input" style="height: 34px" placeholder="Length" oninput="change_status('${lastItemId}'); check_same_data('${lastItemId}'); get_current_quantity(this)" required >`;

                                                        cell5.innerHTML =
                                                            `
                                                                    <div class="toggle-switch-container" style="display: flex; align-items: center; height: 34px;">
                                                                        <span style="margin-right: 8px;">PCs</span>
                                                                        <label class="toggle-switch">
                                                                            @if ($inw_item->uom_type == 'weight')
                                                                            <input type="checkbox" id="uom_${lastItemId}" class="uom-checkbox" value="{{ $inw_item->uom_type }}" oninput="change_status('${lastItemId}')"  checked>
                                                                            @else
                                                                            <input type="checkbox" id="uom_${lastItemId}" class="uom-checkbox" value="{{ $inw_item->uom_type }}" oninput="change_status('${lastItemId}')" >
                                                                            @endif
                                                                            <span class="slider"></span>
                                                                         
                                                                        </label>
                                                                        <span style="margin-left: 8px;">Kg</span>
                                                                    </div>
                                                                    <input type="hidden" id="uom_main_${lastItemId}"  value="{{ $inw_item->uom_type }}"  name="uom[]">`;



                                                        cell6.innerHTML =
                                                            `
            <input type="number" id="pcs_${lastItemId}" value="{{ $inw_item->piece }}" name="piece[]" max="{{ $inw_item->stock_piece }}" min="1" class="form-control pcs-input" style="height: 34px" placeholder="PCs" required readonly>`;
                                                        cell7.innerHTML =
                                                            `
            <input type="text" id="weight_${lastItemId}" value="{{ $inw_item->outward_weight }}" step="any" oninput="updateOverallTotalWeight()" name="weight[]" class="form-control price-input weight-input" style="height: 34px; width:103px;" placeholder="Weight (Kg)" required>
            <input type="hidden" id="weight_hidden_${lastItemId}" value="{{ $inw_item->weight }}"  class="form-control weight-input2" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal()" required readonly>`;





                                                        cell9.innerHTML = `
            <button class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>`;

                                                        cell8.innerHTML =
                                                            ` <input type="text" name="current_quantity[]" id="quantity_${lastItemId}" oninput="get_current_quantity(this)" value="{{ $inw_item->stock_piece }}" class="form-control current-quantity" style="height: 34px" placeholder="Current Qty"  readonly required>`;

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
            <select name="item_category[]" id="item_id${lastItemId}" onchange="get_sub_category(this)" style="height: 34px; width: 220px;" class="select_item_category form-control item-select-${lastItemId}" required>
                <option value="" disabled selected>Select Item</option>
                @foreach ($category as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </select>
        `;
                                                    $('.item-select-' + lastItemId).select2();
                                                    cell2.innerHTML = `
            <select name="item_sub_category[]" id="item_sub_category${lastItemId}" onchange="get_subcategory_list(this)" style="height: 34px; width: 220px;" class="form-control subcategory-select" required>
                <option value="" disabled selected>Item Sub Category</option>
            </select>`;

                                                    $('#item_sub_category' + lastItemId).select2();

                                                    cell4.innerHTML =
                                                        `
            <input type="text" id="qty_${lastItemId}" name="quantity[]" class="form-control quantity-input" style="height: 34px" onchange ="handleTypeChange('${lastItemId}')" placeholder="Quantity" oninput="change_status('${lastItemId}')" required>`;

                                                    cell3.innerHTML =
                                                        `
            <input type="text" name="length[]" id="length_${lastItemId}" class="form-control length-input" style="height: 34px" placeholder="Length" oninput="change_status('${lastItemId}'); check_same_data('${lastItemId}'); get_current_quantity(this)" required >`;

                                                    cell5.innerHTML = `
        <div class="toggle-switch-container" style="display: flex; align-items: center; height: 34px;">
            <span style="margin-right: 8px;">PCs</span>
            <label class="toggle-switch">
                <input type="checkbox" id="uom_${lastItemId}" class="uom-checkbox" oninput="change_status('${lastItemId}')" name="uom_${lastItemId}">
                <span class="slider"></span>
            </label>
            <span style="margin-left: 8px;">Kg</span>
        </div>
        <input type="hidden" id="uom_main_${lastItemId}"  name="uom[]">`;

                                                    cell6.innerHTML =
                                                        `
            <input type="number" id="pcs_${lastItemId}" name="piece[]" max="0" min="1"  class="form-control pcs-input" style="height: 34px" placeholder="PCs" required readonly>`;
                                                    cell7.innerHTML =
                                                        `
            <input type="text" id="weight_${lastItemId}" name="weight[]" step="any" oninput="updateOverallTotalWeight()" class="form-control price-input weight-input" style="height: 34px; width:103px;" placeholder="Weight (Kg)" required>
            <input type="hidden" id="weight_hidden_${lastItemId}"  class="form-control weight-input2" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal()" required readonly>`;



                                                    cell9.innerHTML = `
            <button class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>`;

                                                    cell8.innerHTML =
                                                        ` <input type="number" name="current_quantity[]" value="0"  min="1" id="quantity_${lastItemId}" oninput="get_current_quantity(this)"  class="form-control current-quantity" style="height: 34px" placeholder="Current Qty" required readonly>`;
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
                                                                Loading Cutting</strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="loading_charges"
                                                            value="{{ $outward_data->loading_charges }}"
                                                            id="input_weight">
                                                    </div>
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="  col-form-label"><strong>Additional Charges</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control"
                                                            name="additional_charges" id=""
                                                            value="{{ $outward_data->additional_charges }}">
                                                    </div>

                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="  col-form-label"><strong>Freight</strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="freight"
                                                            id="freight" value="{{ $outward_data->freight }}">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <input type="hidden" name="company_id"
                                            class="form-control"value="{{ $company->id }}" required>

                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-3">
                                            @if ($outward_data->status != 'Approved')
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            @else
                                            @endif
                                            {{-- <a class="btn btn-secondary" href="{{ route('outward.index') }}">Back</a> --}}
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


        function validatePcs(itemId) {
            var quantityElement = document.getElementById(`quantity_${itemId}`);
            var quantityValue = parseFloat(quantityElement.value) || 0;
            var pcsValue = parseFloat($(`#pcs_${itemId}`).val());


            if ((pcsValue > quantityValue) || (quantityValue == 0)) {
                Swal.fire({
                    icon: 'error',
                    // title: 'Invalid Input',
                    text: 'PCS value cannot be greater than Current Quantity.'
                }).then(() => {
                    // $(`#pcs_${itemId}`).val(quantityValue).trigger('input');
                    let pcs = $(`#pcs_${itemId}`).val();
                    let weight = $(`#weight_${itemId}`).val();
                    let totalWeight = $(`#overall_total_weight`).val();
                    let totalPcs = $(`#overall_total_pcs`).val();

                    let mainWeight = (totalWeight - weight).toFixed(3);
                    let mainPcs = totalPcs - pcs;

                    $(`#overall_total_weight`).val(mainWeight);
                    $(`#overall_total_pcs`).val(mainPcs);
                    resetRow(itemId);
                    $(`#pcs_${itemId}`).val('').prop('readonly', false);
                    $(`#length_${itemId}`).val('').prop('readonly', false);
                });
            }
        }

        function resetRow(itemId) {
            // Reset specific input fields in the row
            $(`#item_id${itemId}`).val('').trigger('change');
            $(`#item_sub_category${itemId}`).val('').trigger('change');
            $(`#pcs_${itemId}`).val('');
            $(`#length_${itemId}`).val('');
            $(`#weight_${itemId}`).val('');
            $(`#quantity_${itemId}`).val('');
            $(`#qty_${itemId}`).val('');
            location.reload();
        }

        function handleTypeChange(itemId) {

            validatePcs(itemId);
        }

        function change_status(lastItemId) {
            const checkbox = document.getElementById(`uom_${lastItemId}`);
            const quantityInput = document.getElementById(`qty_${lastItemId}`);
            const weightInput = document.getElementById(`weight_${lastItemId}`);
            const checkbox_main = document.getElementById(`uom_main_${lastItemId}`);
            const weightInput2 = document.getElementById(`weight_hidden_${lastItemId}`);
            const pcsInput = document.getElementById(`pcs_${lastItemId}`);
            const lengthInput = document.getElementById(`length_${lastItemId}`);
            const quantity = parseFloat(quantityInput.value) || 0;
            const length = parseFloat(lengthInput.value) || 0;
            const weight2 = parseFloat(weightInput2.value) || 0;
            console.log(weight2);

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
            console.log(overallTotalWeightInput);
            const overallTotalPcsInput = document.getElementById('overall_total_pcs');
            console.log(overallTotalPcsInput);
            overallTotalWeightInput.value = overallTotalWeight.toFixed(3); // Assuming you want to show two decimal places
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
                    let data = JSON.parse(res);

                    if (data) {
                        let weightInput = row.querySelector('.weight-input');
                        let weightInput2 = row.querySelector('.weight-input2');
                        weightInput.value = data.weight;
                        weightInput2.value = data.weight;

                    }
                }

            });
        }


        function get_current_quantity(selectElement) {
            console.log(1);
            const lengthValue = selectElement.value;
            const lastItemId = selectElement.id.split('_')[1];
            const itemIdValue = $('#item_id' + lastItemId).val();
            const itemSubCategoryValue = $('#item_sub_category' + lastItemId).val();
            const warehouse_id = $('#warehouse_id').val();
            let row_current_qty = selectElement.parentNode.parentNode;
            $.ajax({
                url: "{{ url('get_current_quantity_list') }}",
                method: "POST",
                data: {
                    item_id: itemIdValue,
                    item_sub_category: itemSubCategoryValue,
                    length: lengthValue,
                    warehouse_id: warehouse_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    console.log(res);
                    let currentquantityInput = row_current_qty.querySelector('.current-quantity');
                    let currentpcsInput = row_current_qty.querySelector('.pcs-input');
                    if (res && res.data && res.data.piece !== undefined) {
                        currentquantityInput.value = res.data.piece;
                        currentpcsInput.setAttribute('max', res.data.piece);

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
    </script>
@endsection

<script>
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
        $(`#length_${lastItemId}`).val('');
        $(`#weight_${lastItemId}`).val('');
        $(`#price_${lastItemId}`).val('');
        $(`#gst_percent_${lastItemId}`).val('');
        $(`#qty_${lastItemId}`).val('');
        $(`#amount${lastItemId}`).val('');
        $(`#quantity_${lastItemId}`).val('');
        location.reload();
    }
</script>
