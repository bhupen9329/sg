@extends('layouts.main')
@section('title','Stock Adjustment - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Add Stock Adjustment</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Stock Adjustment</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('stock-adjustment​.save') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <?php
                                        $currentDate = date('Y-m-d');
                                        ?>
                                        <div class="row  ">
                                            <label for="inputEmail3" class="col-sm-3  col-form-label"><strong>Date<span
                                                        class="required-classes">*</span>
                                                </strong></label>
                                            <div class="col-sm-8 ms-5">
                                                <input type="date" class="form-control" value="{{ $currentDate }}"
                                                    name="date" id="inputPassword" required>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6 text-end pe-5">
                                        <label for="inputEmail3" class="col-sm-6 col-form-label"><strong>Adjustment Number :
                                            </strong>{{ $stock_adjustment_number }}</label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="row  ">
                                            <label for="inputEmail3" class="col-sm-3  col-form-label"><strong>Warehouse :
                                                </strong></label>
                                            <div class="col-sm-8 ms-5">
                                                <input type="hidden" id="for_warehouse" class="form-control warehouse_id"
                                                    value="{{ $warehouse->id }}" name="warehouse_id" id="inputPassword"
                                                    required>
                                                <input type="text" class="form-control" name="warehouse_title"
                                                    value="{{ $warehouse->warehouse_title }}" id="inputPassword" readonly>
                                            </div>
                                        </div>

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



                                            <table id="myTable" class="col-md-4 col-sm-4 col-xl-12 table">
                                                <thead>
                                                    <tr>

                                                        <th>Item Category <span class="required-classes">*</span></th>
                                                        <th>Item sub category​ <span class="required-classes">*</span></th>
                                                        <th>Length<span class="required-classes">*</span></th>
                                                        <th>PCs <span class="required-classes">*</span></th>
                                                        <th>Weight (kg)​ <span class="required-classes">*</span></th>
                                                        <th>Current Qty <span class="required-classes">*</span></th>
                                                        <th>Type <span class="required-classes">*</span></th>
                                                        {{-- <th>Action </th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>

                                            <script>
                                                var lastItemId = 1;

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

                                                    cell1.innerHTML = `
            <select name="item_category[]" id="item_id${lastItemId}" onchange="get_sub_category(this) " style="height: 34px; width: 220px;" class="select_item_category form-control item-select-${lastItemId}" required>
                <option value="" disabled selected>Select Item</option>
                @foreach ($category as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </select>
        `;
                                                    $('.item-select-' + lastItemId).select2();
                                                    cell2.innerHTML = `
            <select name="item_sub_category[]" id="item_sub_category${lastItemId}" onchange="get_subcategory_list(this) " style="height: 34px; width: 220px;" class="form-control subcategory-select item-sub-select-${lastItemId}" required>
                <option value="" disabled selected>Item Sub Category</option>
            </select>`;
                                                    $('.item-sub-select-' + lastItemId).select2();

                                                    cell3.innerHTML =
                                                        `
            <input type="number" name="length[]" id="length_${lastItemId}" class="form-control length-input"  min="1" style="height: 34px" placeholder="Length" oninput="change_status('${lastItemId}'); check_same_data('${lastItemId}'); get_current_quantity(this)" required >`;


                                                    cell4.innerHTML =
                                                        `
            <input type="number" id="pcs_${lastItemId}" name="piece[]"  class="form-control price-input"  min="1" style="height: 34px" placeholder="PCs" oninput="change_status('${lastItemId}'); handleTypeChange('${lastItemId}')" required  >
            <input type="hidden" id="pcs_${lastItemId}" class="form-control price-input-hidden" style="height: 34px" placeholder="PCs" readonly>`;
                                                    cell5.innerHTML =
                                                        `
            <input type="text" id="weight_${lastItemId}" name="weight[]" class="form-control price-input weight-input" style="height: 34px" placeholder="Weight (Kg)" required readonly>
            <input type="hidden" id="weight_hidden_${lastItemId}"  class="form-control weight-input2" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal()" required readonly>`;


                                                    cell6.innerHTML =
                                                        `
            <input type="text" name="current_quantity[]" id="quantity_${lastItemId}" oninput="get_current_quantity(this)" class="form-control current-quantity" style="height: 34px" placeholder="Current Qty"  readonly required>`;

                                                    cell7.innerHTML = `
                                                        <select name="type[]" id="type_${lastItemId}" style="height: 34px; width: 220px;" class="form-control type-select-${lastItemId}" onchange="handleTypeChange('${lastItemId}')" required>
                                                            <option value="" disabled selected>Select Type</option>
                                                            <option value="Addition">Addition (+)</option>
                                                            <option value="Subtraction">Subtraction (-)</option>
                                                        </select>
                                                    `;

                                                    // cell8.innerHTML = `
        //     <button class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>

        // Focus the search box when the dropdown is opened
        $('.item-select-' + lastItemId).on('select2:open', function() {
            document.querySelector('.select2-search__field').focus();
        });

        // Focus the search box when the subcategory dropdown is opened
        $('#item_sub_category' + lastItemId).on('select2:open', function() {
            document.querySelector('.select2-search__field').focus();
        });

        // `;

                                                    lastItemId++;
                                                }

                                                function deleteRow(button) {
                                                    var row = button.parentNode.parentNode;
                                                    row.parentNode.removeChild(row);
                                                }
                                            </script>


                                        </div>
                                        {{-- ..........................................................  --}}
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="inputEmail3"
                                                    class="col-sm-2 col-form-label"><strong>Remarks</strong></label>
                                                <textarea class="form-control" name="remark" placeholder="Remark" id="floatingTextarea" style="height: 100px;"></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="adjustment_number"
                                            class="form-control"value="{{ $stock_adjustment_number }}" required>
                                        <input type="hidden" name="user_id" class="form-control"
                                            value="{{ $user_id }}" required>
                                        <div class="text-end mt-5">
                                            <button type="submit" class="btn btn-primary"
                                                onclick="return validateAndSubmit()">Update Stock</button>
                                            <a class="btn btn-secondary" href="{{ route('adjustment.index') }}">Back</a>
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
        $(document).ready(function() {
            //click add row button
            $('#addRowBtn').click();

            //then hide button 
            $('#addRowBtn').hide();
        });

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
            weightInput.value = total_weight.toFixed(1);

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

                        let priceInput = row.querySelector('.price-input-hidden');
                        // console.log(priceInput);
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
    </script>



    <script>
        function validatePcs(itemId) {
            // console.log(itemId);
            var quantityElement = document.getElementById(`quantity_${itemId}`);
            var quantityValue = parseFloat(quantityElement.value) || 0;
            // console.log(quantityValue);
            var pcsValue = parseFloat($(`#pcs_${itemId}`).val()) || 0;
            // console.log(pcsValue);
            var typeValue = $(`#type_${itemId}`).val();

            if (typeValue === 'Subtraction' && pcsValue > quantityValue) {
                Swal.fire({
                    icon: 'error',
                    // title: 'Invalid Input',
                    text: 'PCS value cannot be greater than Current Quantity.'
                }).then(() => {
                    // $(`#pcs_${itemId}`).val(quantityValue).trigger('input');
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
            $(`#type_${itemId}`).val('').trigger('change');
        }

        function handleTypeChange(itemId) {
            validatePcs(itemId);
            // $(`#pcs_${itemId}`).prop('readonly', true);
            // $(`#length_${itemId}`).prop('readonly', true);
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
