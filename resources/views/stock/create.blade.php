@extends('layouts.main')
@section('title','Stock - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Add Stock</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Stock</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('stock.save') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>
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



                                            <table id="myTable" class="col-md-4 col-sm-4 col-xl-12 table">
                                                <thead>
                                                    <tr>

                                                        <th>Item Category <span class="required-classes">*</span></th>
                                                        <th>Item sub category​ <span class="required-classes">*</span></th>
                                                        <th>Qty <span class="required-classes">*</span></th>
                                                        <th>Length(Ft)<span class="required-classes">*</span></th>
                                                        <th>Uom type​ <span class="required-classes">*</span></th>
                                                        <th>PCs <span class="required-classes">*</span></th>
                                                        <th>Weight (kg)​ <span class="required-classes">*</span></th>
                                                        <th>Current Qty <span class="required-classes">*</span></th>
                                                        <th>Action </th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>

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
            <select name="item_category[]" id="item_id${lastItemId}" onchange="get_sub_category(this) " style="height: 34px; width: 220px;" class="select_item_category form-control item-select-${lastItemId}" required>
                <option value="" disabled selected>Select Item</option>
                @foreach ($category as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </select>
        `;
                                                    $('.item-select-' + lastItemId).select2();
                                                    cell2.innerHTML = `
            <select name="item_sub_category[]" id="item_sub_category${lastItemId}" onchange="get_subcategory_list(this) " style="height: 34px; width: 220px;" class="form-control subcategory-select item-sub_category-select-${lastItemId}" required>
                <option value="" disabled selected>Item Sub Category</option>
            </select>`;
                                                    $('.item-sub_category-select-' + lastItemId).select2();

                                                    cell3.innerHTML =
                                                        `
            <input type="number" id="qty_${lastItemId}" name="quantity[]" class="form-control quantity-input" style="height: 34px" min="1" placeholder="Quantity" oninput="change_status('${lastItemId}')" required>`;

                                                    cell4.innerHTML =
                                                        `
            <input type="number" name="length[]" id="length_${lastItemId}" class="form-control length-input" style="height: 34px" min="1"  oninput="change_status('${lastItemId}'); check_same_data('${lastItemId}'); get_current_quantity(this)" placeholder="Length" required >`;

                                                    cell5.innerHTML = `
            <div class="toggle-switch-container" style="display: flex; align-items: center; height: 34px;">
                <span style="margin-right: 8px;">PCs</span>
                <label class="toggle-switch">
                    <input type="checkbox" id="uom_${lastItemId}" class="uom-checkbox" onclick="change_status(${lastItemId})" name="uom_${lastItemId}">
                    <span class="slider"></span>
                </label>
                <span style="margin-left: 8px;">Kg</span>
            </div>
            <input type="hidden" value="weight"  id="uom_main_${lastItemId}"  name="uom[]">
        `;

                                                    cell6.innerHTML =
                                                        `
            <input type="number" id="pcs_${lastItemId}" name="piece[]" class="form-control price-input" style="height: 34px" min="1" placeholder="PCs" required readonly>`;
                                                    cell7.innerHTML =
                                                        `
            <input type="number" id="weight_${lastItemId}" name="weight[]" class="form-control price-input weight-input" style="height: 34px" placeholder="Weight (Kg)" required readonly>
            <input type="hidden" id="weight_hidden_${lastItemId}"  class="form-control weight-input2" style="height: 34px; width: 101px" placeholder="weight" onchange="calculateTotal()" required readonly>`;


                                                    cell8.innerHTML =
                                                        `
            <input type="text" id="current_qty${lastItemId}" name="current_quantity[]" class="form-control current-quantity" id="set_current_qty" oninput="get_current_quantity(this)"  style="height: 34px" placeholder="Current Qty" required readonly>`;

                                                    cell9.innerHTML = `
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
                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-5">
                                            <button type="submit" class="btn btn-primary">Add Stock</button>
                                            <a class="btn btn-secondary" href="{{ route('stock.index') }}">Back</a>
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
            const checkbox = document.getElementById(`uom_${lastItemId}`);
            // console.log(checkbox);
            const checkbox_main = document.getElementById(`uom_main_${lastItemId}`);
            const quantityInput = document.getElementById(`qty_${lastItemId}`);
            const weightInput = document.getElementById(`weight_${lastItemId}`);
            const weightInput2 = document.getElementById(`weight_hidden_${lastItemId}`);
            const pcsInput = document.getElementById(`pcs_${lastItemId}`);
            const lengthInput = document.getElementById(`length_${lastItemId}`);

            const quantity = parseFloat(quantityInput.value) || 0;
            const length = parseFloat(lengthInput.value) || 0;

            // console.log(length);
            const weight2 = parseFloat(weightInput2.value) || 0;

            let total_weight;
            let total_pcs_qty;

            if (checkbox.checked) {
                var main_uom = 'weight';
                checkbox_main.value = main_uom;

                // Calculate total_weight and handle NaN case
                total_weight = length * weight2;
                if (isNaN(total_weight) || total_weight === 0) {
                    total_weight = 0;
                }

                weightInput.value = quantityInput.value;

                // Calculate total_pcs_qty and handle NaN case
                total_pcs_qty = quantityInput.value / total_weight;
                if (isNaN(total_pcs_qty) || total_pcs_qty === Infinity || total_pcs_qty === 0) {
                    total_pcs_qty = 0;
                } else {
                    total_pcs_qty = Math.round(total_pcs_qty);
                }

                pcsInput.value = total_pcs_qty;
            } else {
                var main_uom = 'pcs';
                checkbox_main.value = main_uom;
                total_weight = length * weight2;
                total_weight_qty = total_weight * quantity;
                pcsInput.value = quantity;
                weightInput.value = (total_weight_qty).toFixed(3);
            }

            // Recalculate and update the overall total weight
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
                        let weightInput = row.querySelector('.weight-input');
                        let priceInput = row.querySelector('.price-input');
                        let weightInput2 = row.querySelector('.weight-input2');

                        // weightInput.value = data.weight;
                        weightInput2.value = data.weight;

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
            $(`#current_qty${lastItemId}`).val('');
        }
    </script>
@endsection
