@extends('layouts.main')
@section('title', 'Update - Dispatch')
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
                            <form class="row g-3"  id="dispatchForm" method="post">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="to_company_id" class="form-label">Dispatch Date<span
                                                class="required-classes">*</span></label>
                                        <input type="date" class="form-control" name="date"
                                            value="{{ $dispatch_date }}" required>
                                    </div>
                                    <div class="col-md-6"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="to_company_id" class="form-label">Vehicle Number</label>
                                        <input type="text" class="form-control" id="vehicle"
                                            value="{{ $dispatch_vehicle }}" name="vehicle_number">
                                    </div>
                                    <!-- Company Dropdown (From) -->
                                    <div class="col-md-6 mt-4"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="get_miller_id" class="form-label">From</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company" id="get_miller_id" name="po_company_id"
                                            onchange="fetchPoNumbers(this)" required disabled>
                                            <option value="">
                                                {{ $dispatch_po_company }}</option>
                                        </select>
                                    </div>

                                    <!-- PO Items Dropdown (To) -->
                                    <div class="col-md-6  mt-4"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="to_company_id" class="form-label">To</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company" id="to_company_id" name="so_company_id"
                                            onchange="fetchSalesOrders(this)" required disabled>
                                            <option>
                                                {{ $dispatch_so_company }}</option>
                                        </select>
                                    </div>

                                    <input type="hidden" class="form-control" id=""
                                        value="{{ $dispatch_number }}" name="dispatch_number">

                                </div>

                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">Dispatch Details</h4>
                                    {{-- <button type="button" id="addRowBtn" class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1" onclick="addRow()">Add Row</button> --}}
                                </div>
                                <div class="table-responsive" style="overflow-x: auto;">
                                    <table id="myTable" class="col-md-4 col-sm-4 col-xl-12 table">
                                        <thead>
                                            <tr>
                                                <th class="table_heading_long">Base Item<span
                                                        class="required-classes">*</span></th>
                                                <th style="width: 115px;" class="table_heading_long">PO Item No.</th>
                                                <th style="width: 150px;" class="table_heading_long">Conv Item</th>
                                                <th style="width: 115px;" class="table_heading_long">Conv Price</th>
                                                <th style="width: 115px;" class="table_heading_long">PO Price</th>
                                                <th style="width: 115px;" class="table_heading_long">Gross PO Price</th>
                                                <th style="width: 115px;" class="table_heading_long">Loading + Insurance
                                                </th>
                                                <th style="width: 115px;" class="table_heading_normal">PORest Qty<span
                                                        class="required-classes">*</span></th>
                                                <th style="width: 115px;" class="table_heading_normal">Qty<span
                                                        class="required-classes">*</span></th>
                                                <th style="width: 115px;" class="table_heading_long">Payable Total<span
                                                        class="required-classes">*</span></th>
                                                <th style="width: 250px;" class="table_heading_long">SO Item No.<span
                                                        class="required-classes">*</span></th>
                                                <th style="width: 115px;" class="table_heading_long">SORest Qty</th>
                                                <th style="width: 115px;" class="table_heading_long">SO Price</th>
                                                <th style="width: 115px;" class="table_heading_long">SO Gross Price</th>
                                                <th style="width: 115px;" class="table_heading_long">Receivable Total<span
                                                        class="required-classes">*</span></th>
                                                <th class="table_heading_action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">PO Details</h4>
                                    {{-- <button type="button" id="addRowBtn" class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1" onclick="addRow()">Add Row</button> --}}
                                </div>

                                <table class="col-md-4 col-sm-4 col-xl-12 table">
                                    <thead>
                                        <tr>
                                            <th>Date (DD/MM/YY)</th>
                                            <th>PO Number</th>
                                            <th>Item Name</th>
                                            <th>PO Item No.</th>
                                            <th>Quantity (Q)</th>
                                            <th>Rest Quantity (Q)</th>
                                            <th>PO Unit Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        @foreach ($po_items as $po_item)
                                            <tr>
                                                <td>{{ date('d-M-Y', strtotime($po_item->date)) }}</td>
                                                <td>{{ $po_item->document_number }}</td>
                                                <td>{{ $po_item->name }}</td>
                                                <td>{{ $po_item->po_item_no }}</td>
                                                <td>{{ $po_item->qty }}</td>
                                                <td>{{ $po_item->po_dispatch_rest_qty }}</td>
                                                <td>{{ $po_item->unit_price }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">SO Details</h4>
                                    {{-- <button type="button" id="addRowBtn" class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1" onclick="addRow()">Add Row</button> --}}
                                </div>
                                <table class="col-md-4 col-sm-4 col-xl-12 table">
                                    <thead>
                                        <tr>
                                            <th>Date (DD/MM/YY)</th>
                                            <th>SO Number</th>
                                            <th>Item Name</th>
                                            <th>SO Item No.</th>
                                            <th>Quantity (Q)</th>
                                            <th>Rest Quantity (Q)</th>
                                            <th>SO Unit Price</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($so_items as $so_item)
                                            <tr>
                                                <td>{{ date('d-M-Y', strtotime($so_item->date)) }}</td>
                                                <td>{{ $so_item->so_number }}</td>
                                                <td>{{ $so_item->name }}</td>
                                                <td>{{ $so_item->so_item_no }}</td>
                                                <td>{{ $so_item->qty }}</td>
                                                <td>{{ $so_item->so_dispatch_rest_qty }}</td>
                                                <td>{{ $so_item->unit_price }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <input type="hidden" id="po_item_id">


                                {{-- <div class="col-md-4">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" value="{{ $disaptch_data->remarks }}"
                                        placeholder="Enter remarks here...">{{ $disaptch_data->remarks }}</textarea>
                                </div> --}}

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
        // function deleteRow(button) {
        //     var row = button.parentNode.parentNode;
        //     row.parentNode.removeChild(row);
        // }

        function deleteRow(button) {
    // Find the row that was clicked
    const row = button.closest('tr');

    // Get the value of 'po_item_number[]' from the current row
    const currentPoItemInput = row.querySelector('[name="po_item_number[]"]');
    const currentPoItemValue = currentPoItemInput ? currentPoItemInput.value : null;

    if (!currentPoItemValue) {
        alert("PO Item not found. Cannot delete row.");
        return;
    }

    // Get the value of 'so_item_no[]' from the current row
    const currentSoItemSelect = row.querySelector('[name="so_item_no[]"]');
    const currentSoItemValue = currentSoItemSelect ? currentSoItemSelect.value : null;

    if (!currentSoItemValue) {
        alert("SO Item not selected. Cannot proceed.");
        return;
    }

    // Get the quantity of the row to be deleted from the 'quantity[]' input
    const currentQuantityInput = row.querySelector('[name="quantity[]"]');
    const currentQuantity = parseFloat(currentQuantityInput.value) || 0;

    // Handle SO Item rest quantity update
    let nextRow = row.nextElementSibling;

    while (nextRow) {
        const nextSoItemSelect = nextRow.querySelector('[name="so_item_no[]"]');
        const nextSoItemValue = nextSoItemSelect ? nextSoItemSelect.value : null;

        if (nextSoItemValue === currentSoItemValue) {
            // Found a matching SO Item row, update its 'so_rest_qty_show[]'
            const nextSoRestQtyInput = nextRow.querySelector('[name="so_rest_qty_show[]"]');
            if (nextSoRestQtyInput) {
                const nextSoRestQty = parseFloat(nextSoRestQtyInput.value) || 0;
                nextSoRestQtyInput.value = nextSoRestQty + currentQuantity;
            }
            break;
        }

        nextRow = nextRow.nextElementSibling;
    }

    // Handle PO Item rest quantity update
    nextRow = row.nextElementSibling;

    while (nextRow) {
        const nextPoItemInput = nextRow.querySelector('[name="po_item_number[]"]');
        const nextPoItemValue = nextPoItemInput ? nextPoItemInput.value : null;

        if (nextPoItemValue === currentPoItemValue) {
            // Found a matching PO Item row, update its 'po_rest_qty_show'
            const nextPoRestQtyInput = nextRow.querySelector('[name="po_rest_qty_show"]');
            if (nextPoRestQtyInput) {
                const nextPoRestQty = parseFloat(nextPoRestQtyInput.value) || 0;
                nextPoRestQtyInput.value = nextPoRestQty + currentQuantity;
            }
            break;
        }

        nextRow = nextRow.nextElementSibling;
    }

    // Remove the current row
    row.remove();
    calculateTotal(button);
}


        function fetchrow() {
            var table = document.getElementById("myTable");
            var subcategories = @json($subcategories);
            var remainingQuantities = {};
            var dispatchQtyTotals = {};
            var soDispatchQtyTotals = {};
            @foreach ($dispatch_data as $data)
                var poItemNo = "{{ $data->po_item_no }}";
                if (!dispatchQtyTotals[poItemNo]) {
                    dispatchQtyTotals[poItemNo] = 0;
                }
                dispatchQtyTotals[poItemNo] += {{ $data->dispatched_quantity }};
            @endforeach

            @foreach ($dispatch_data as $data)
                var soItemNo = "{{ $data->so_item_no }}";
                if (!soDispatchQtyTotals[soItemNo]) {
                    soDispatchQtyTotals[soItemNo] = 0;
                }
                soDispatchQtyTotals[soItemNo] += {{ $data->dispatched_quantity }};
            @endforeach


            @foreach ($dispatch_data as $data)
                var poItemNumber = "{{ $data->po_item_no }}";
                var soItemNumber = "{{ $data->so_item_no }}";
                var isFirstRowForPoItemNo = table.querySelectorAll(
                    `input[name="po_item_number[]"][value="${poItemNumber}"]`).length === 0;

                var isFirstRowForSoItemNo = table.querySelectorAll(
                    `input[name="so_item_number[]"][value="${soItemNumber}"]`).length === 0;

                // Determine initialQuantity
                var initialQuantity = isFirstRowForPoItemNo ?
                    dispatchQtyTotals[poItemNumber] + {{ $data->po_dispatch_rest_qty }} :
                    {{ $data->po_dispatch_rest_qty }} + {{ $data->dispatched_quantity }};

                var initialQuantitySo = isFirstRowForSoItemNo ?
                    soDispatchQtyTotals[soItemNumber] + {{ $data->so_dispatch_rest_qty }} :
                    {{ $data->so_dispatch_rest_qty }} + {{ $data->dispatched_quantity }};


                var dispatchQuantity = {{ $data->dispatched_quantity }};

                // Initialize remaining quantity for the first occurrence
                if (!remainingQuantities[poItemNumber]) {
                    remainingQuantities[poItemNumber] = initialQuantity;
                }

                if (!remainingQuantities[soItemNumber]) {
                    remainingQuantities[soItemNumber] = initialQuantitySo;
                }

                // Calculate the remaining quantity for this row
                var currentRestQty = remainingQuantities[poItemNumber];
                remainingQuantities[poItemNumber] -= dispatchQuantity;

                var currentRestQtySo = remainingQuantities[soItemNumber];
                remainingQuantities[soItemNumber] -= dispatchQuantity;

                var filteredSubItems = subcategories.filter(sub => sub.category_id == {{ $data->category_id }});

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
                var cell12 = newRow.insertCell(11);
                var cell13 = newRow.insertCell(12);
                var cell14 = newRow.insertCell(13);
                var cell15 = newRow.insertCell(14);
                var cell16 = newRow.insertCell(15);
                var cell17 = newRow.insertCell(15);



                // Calculating the Gross PO Price by adding Conv Price + PO Price + (Loading + Insurance)
                var convPrice = parseFloat("{{ $data->conv_rate }}") || 0;
                var poPrice = parseFloat("{{ $data->po_item_unit_price }}") || 0;
                var loadingInsurance = parseFloat("{{ $data->dispatch_other }}") || 0;
                var soPrice = parseFloat("{{ $data->dispatch_so_unit_price }}") || 0;
                var grossPoPrice = convPrice + poPrice + loadingInsurance;
                var grossSoPrice = convPrice + soPrice + loadingInsurance;


                cell1.innerHTML = `
            <td>{{ $data->category_name }}</td>
            <td><input type="hidden" name="cat_id[]" class="form-control" value="{{ $data->category_id }}" required></td>
        `;
                cell2.innerHTML = `
             <input type="text" name="po_item_number[]" class="form-control" value="{{ $data->po_item_no }}" readonly required>
          
        `;

                cell3.innerHTML = `
            <select name="sub_cat_id[]" class="form-select select_brand_name" onchange="get_conv_price(this)">
                <option value="{{ $data->subcategory_id }}" selected>{{ $data->sub_category_name }}</option>
                ${filteredSubItems.map(sub => `<option value="${sub.id}">${sub.sub_category}</option>`).join('')}
            </select>
        `;

                cell4.innerHTML = `
            <td><input type="number" name="conv_rate[]"  class="form-control" onchange="calculateTotal(this)" value="{{ $data->conv_rate }}" min="1" readonly required /></td>
        `;

                cell5.innerHTML = `
            <td><input type="number" class="form-control" onchange="calculateTotal(this)" value="{{ $data->po_item_unit_price }}" min="1" readonly required /></td>
        `;

                cell6.innerHTML = `
            <td><input type="number" name="dispatch_unit_price[]" class="form-control" onchange="calculateTotal(this)" value="${grossPoPrice}" min="1" readonly required /></td>
        `;

                cell7.innerHTML = `
    <td>
        <input type="number" name="dispatch_fregiht_insuance[]" oninput="calculateTotal(this)" value="{{ $data->dispatch_other }}" class="form-control" required />

  
        <input type="hidden" name="dispatch_freight[]" value="{{ $data->dispatch_freight }}" class="form-control" oninput="calculateTotal(this)" required />
        <input type="hidden" name="dispatch_other[]" value="{{ $data->dispatch_other }}" class="form-control" oninput="calculateTotal(this)" required />
        <input type="hidden" name="dispatch_so_freight[]" value="{{ $data->dispatch_so_freight }}" class="form-control" oninput="calculateTotal(this)" required />
        <input type="hidden" name="dispatch_so_other[]" value="{{ $data->dispatch_so_other }}" class="form-control" oninput="calculateTotal(this)" required />
        <input type="hidden" name="dispatch_so_other_actual[]" value="0" class="form-control" oninput="calculateTotal(this)" required />
        <input type="hidden" name="dispatch_unit_price_actual[]" class="form-control" value="{{ $data->dispatch_unit_price }}" readonly required />
        <input type="hidden" name="dispatch_so_unit_price_actual[]" id="so_unit_price_actual" value="{{ $data->dispatch_so_unit_price }}" class="form-control" readonly required />
            <input type="hidden" name="quantity_po[]" oninput="calculateTotal(this)" step="0.001" min="1" class="form-control" value="{{ $data->so_dispatch_rest_qty + $data->dispatched_quantity }}" required />
         <input type="hidden"  name="" value="{{ $data->po_item_no }}">
    </td>
`;

                cell8.innerHTML = `
            <td><input type="number" name="po_rest_qty_show"  oninput="calculateTotal(this)" step="0.001" min="0.001" class="form-control" value="${currentRestQty}" readonly /></td>
        `;


                cell9.innerHTML = `
            <td><input type="number" name="quantity[]" class="form-control" step="0.001" value="{{ $data->dispatched_quantity }}" oninput="calculateTotal(this)" min="0.001" required /></td>
        `;

                cell10.innerHTML = `
            <td><input type="number" name="dispatch_total[]" value="{{ $data->dispatch_total }}" class="form-control" readonly required /></td>
        `;

                cell11.innerHTML = `
<td>
  <select name="so_item_no[]" onchange="fetchUnitPrice(this)" class="form-select" required>
    <option value="{{ $data->so_item_no }}" selected> {{ $data->category_name }}, {{ $data->dispatch_so_unit_price }}, {{ $data->so_dispatch_rest_qty + $data->dispatched_quantity }} ({{ $data->so_item_no }})</option>
    @foreach ($so_items as $so_data)
    @if ($so_data->so_item_no != $data->so_item_no)
      <option value="{{ $so_data->so_item_no }}">
        {{ $so_data->name }}, {{ $so_data->unit_price }}, {{ $so_data->so_dispatch_rest_qty + $data->dispatched_quantity }} ({{ $so_data->so_item_no }})
      </option>
      @endif
    @endforeach
  </select>
</td>
`;

                cell12.innerHTML = `
            <td><input type="number" name="so_rest_qty_show[]"   step="0.001" min="0.001" class="form-control" value="${currentRestQtySo}" readonly /></td>
        `;


                cell13.innerHTML = `
            <td><input type="number" id="so_unit_price" value="{{ $data->dispatch_so_unit_price }}" class="form-control" readonly required /></td>
        `;

                // Setting the Gross PO Price in the corresponding field
                cell14.innerHTML = `
            <td><input type="number" name="dispatch_so_unit_price[]" id="so_unit_price" value="${grossSoPrice}" class="form-control" readonly required /></td>
        `;

                cell15.innerHTML = `
            <td><input type="number" name="dispatch_so_total[]" value="{{ $data->dispatch_so_total }}" class="form-control" readonly required /></td>
        `;
                cell16.innerHTML = `
             <td>
    <button type="button" class="btn btn-danger" onclick="deleteRow(this)">
        <i class="fas fa-minus-circle"></i>
    </button>
</td>
        `;
                cell17.innerHTML = `
        <td>
    <button type="button" class="btn btn-success" onclick="cloneRow(this)">
        <i class="fas fa-copy"></i>
    </button>
</td>`;
            @endforeach
        }



        document.addEventListener("DOMContentLoaded", function() {
            fetchrow();
        });

        function get_conv_price(selectElement) {
            let item_id = selectElement.value;

            $.ajax({
                url: "{{ url('get_conv_price') }}",
                method: "POST",
                data: {
                    subcategory_item_id: item_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    const convRateField = $(selectElement).closest('tr').find('input[name="conv_rate[]"]');
                    if (response && response.item_price) {
                        // Set the conversion rate from the response
                        convRateField.val(response.item_price);

                    } else {
                        convRateField.val(0);
                        console.error('Conversion rate not found in response');
                    }

                    // Call calculateTotal with the updated convRateField
                    calculateTotal(convRateField[0]);
                },
                error: function(xhr) {
                    console.error('Error fetching conversion rate:', xhr);
                    alert('An error occurred while fetching the conversion rate. Please try again.');
                }
            });
        }


        function calculateTotal(element) {

            const row = element.closest('tr');
            const unitPrice = parseFloat(row.querySelector('input[name="dispatch_unit_price[]"]').value) || 0;
            const unitPriceActual = parseFloat(row.querySelector('input[name="dispatch_unit_price_actual[]"]').value) || 0;
            const convRate = parseFloat(row.querySelector('input[name="conv_rate[]"]').value) || 0;
            const quantityInput = row.querySelector('input[name="quantity[]"]');

            const quantity = parseFloat(quantityInput.value) || 0;
            const po_rest_quantity = parseFloat(row.querySelector('input[name="po_rest_qty_show"]').value) || 0;
            const so_rest_quantity = parseFloat(row.querySelector('input[name="so_rest_qty_show[]"]').value) || 0;
            const po_item_number = row.querySelector('input[name="po_item_number[]"]').value;



            // Check if the quantity exceeds the available PO or SO quantity
            if (quantity > po_rest_quantity || quantity > so_rest_quantity) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Quantity Exceeded',
                    text: 'The entered quantity exceeds the available PO or SO quantity.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reset the quantity to 0 after SweetAlert
                    quantityInput.value = 0;
                });
                return; // Exit the function if the condition is met
            }

            // Update subsequent rows
            let remainingPoQty = po_rest_quantity - quantity;
            let remainingSoQty = so_rest_quantity - quantity;

            let isCurrentRow = true;
            const currentPoItemNumber = element.closest('tr').querySelector('input[name="po_item_number[]"]').value;

            Array.from(document.querySelector("#myTable").rows).forEach((row) => {
                if (isCurrentRow) {
                    if (row === element.closest('tr')) {
                        isCurrentRow = false; // Current row found; start updating next rows
                    }
                    return;
                }

                const rowPoItemNumber = row.querySelector('input[name="po_item_number[]"]').value;
                // Only process rows with the same po_item_number
                if (rowPoItemNumber !== currentPoItemNumber) {
                    return;
                }

                const nextPoRestQtyInput = row.querySelector('input[name="po_rest_qty_show"]');
                const nextSoRestQtyInput = row.querySelector('input[name="so_rest_qty_show[]"]');

                if (nextPoRestQtyInput) {
                    nextPoRestQtyInput.value = Math.max(remainingPoQty, 0).toFixed(3);
                }

                if (nextSoRestQtyInput) {
                    nextSoRestQtyInput.value = Math.max(remainingSoQty, 0).toFixed(3);
                }

                remainingPoQty -= parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
                remainingSoQty -= parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            });

            // Recalculate the total values for PO and SO based on the quantity
            const sounitPrice = parseFloat(row.querySelector('input[name="dispatch_so_unit_price[]"]').value) || 0;
            const sounitPriceActual = parseFloat(row.querySelector('input[name="dispatch_so_unit_price_actual[]"]')
                .value) || 0;
            const freight_insurance = parseFloat(row.querySelector('input[name="dispatch_fregiht_insuance[]"]').value) || 0;

            let totalPOUnitPrice = unitPriceActual + convRate + freight_insurance;
            let totalSOUnitPrice = sounitPriceActual + convRate + freight_insurance;

            row.querySelector('input[name="dispatch_unit_price[]"]').value = totalPOUnitPrice.toFixed(2);
            row.querySelector('input[name="dispatch_so_unit_price[]"]').value = totalSOUnitPrice.toFixed(2);

            let totalAmount = 0;
            let totalSoAmount = 0;

            if (quantity) {
                totalAmount = (totalPOUnitPrice * quantity);
                totalSoAmount = (totalSOUnitPrice * quantity);
                row.querySelector('input[name="dispatch_total[]"]').value = totalAmount.toFixed(2);
                row.querySelector('input[name="dispatch_so_total[]"]').value = totalSoAmount.toFixed(2);
            } else {
                totalAmount = totalPOUnitPrice;
                totalSoAmount = totalSOUnitPrice;
                row.querySelector('input[name="dispatch_total[]"]').value = totalAmount.toFixed(2);
                row.querySelector('input[name="dispatch_so_total[]"]').value = totalSoAmount.toFixed(2);
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#vehicle').focus(); // Example code
        });
    </script>

    <script>
        function fetchUnitPrice(selectElement) {
            const soItemNo = selectElement.value; // Get the selected SO item number
            const row = selectElement.closest('tr'); // Find the current table row
            const unitPriceField = row.querySelector(
                'input[name="dispatch_so_unit_price_actual[]"]'); // Unit price field in the same row
            const QtyField = row.querySelector('input[name="quantity[]"]'); // Quantity field in the same row
            const QtyFieldValue = parseFloat(row.querySelector('input[name="quantity_po[]"]')
                .value); // Get the current quantity value as a number
            const QtyFieldShow = row.querySelector('input[name="so_rest_qty_show[]"]');

            if (soItemNo) {
                $.ajax({
                    url: '/get-so-unit-price', // Replace with your actual route URL
                    method: 'POST',
                    data: {
                        so_item_no: soItemNo,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        if (response.success) {
                            const responseRestQty = parseFloat(response.qty); // Parse response.qty as a number
                            const responseQty = (responseRestQty + QtyFieldValue);

                            unitPriceField.value = response.unit_price; // Set the unit price

                            // Compare quantities properly
                            if (responseQty <= QtyFieldValue) {
                               // Set the quantity field to the returned quantity
                                QtyField.setAttribute('max',
                                    responseQty); // Set the max attribute to the returned quantity
                            } else {
                                 // Restore the original quantity if it's less than the returned value
                                QtyField.setAttribute('max',
                                    QtyFieldValue); // Set the max to the original quantity
                            }
                            QtyFieldShow.value = responseQty;

                            calculateTotal(selectElement); // Call your function to recalculate totals
                        } else {
                            console.error("Failed to fetch unit price:", response.message);
                            unitPriceField.value = ''; // Clear the field in case of failure
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching unit price:", error);
                        unitPriceField.value = ''; // Clear the field in case of error
                    }
                });
            } else {
                unitPriceField.value = ''; // Clear the unit price if no SO item is selected
            }
        }

        function cloneRow(button) {
            const row = button.closest('tr'); // Find the current row
            const clonedRow = row.cloneNode(true); // Clone the row

            // Get the values of the original row's input fields
            const originalQty = parseFloat(row.querySelector('input[name="po_rest_qty_show"]').value) || 0;
            const originalQtySO = parseFloat(row.querySelector('input[name="so_rest_qty_show[]"]').value) || 0;
            const originalQuantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;

            // Calculate the value for 'po_rest_qty_show' and 'so_rest_qty_show' in the cloned row
            const newRestQty = originalQty - originalQuantity;
            const newRestQtySO = originalQtySO - originalQuantity;
            // Update the cloned row values
            clonedRow.querySelectorAll('input').forEach(input => {
                if (input.name === 'po_rest_qty_show') {
                    input.value = newRestQty.toFixed(3); // Set the new calculated value
                } else if (input.name === 'so_rest_qty_show[]') {
                    input.value = newRestQtySO.toFixed(3); // Set the new calculated value
                } else if (input.name === 'quantity[]') {
                    input.value = '0'; // Reset quantity to 0 for the new row
                } else {
                    input.value = input.value; // Retain other input values
                }
            });

            // Retain the selected option for each select dropdown
            clonedRow.querySelectorAll('select').forEach(select => {
                const originalSelect = row.querySelector(
                    `select[name="${select.name}"]`); // Find the original select
                select.value = originalSelect.value; // Set the selected value of the cloned select
            });

            // Reset dependent fields to prevent errors in cloned row
            clonedRow.querySelectorAll('input[type="number"]').forEach(input => {
                if (input.name.includes('dispatch') || input.name === 'quantity[]') {
                    input.value = '0'; // Reset numeric inputs
                }
            });

            // Insert the cloned row below the current row
            row.parentNode.insertBefore(clonedRow, row.nextSibling);
        }
    </script>

<script>
    $(document).ready(function() {
        $('#dispatchForm').on('submit', function(e) {
            e.preventDefault(); // Prevent page refresh

            let formData = $(this).serialize(); // Serialize form data

            $.ajax({
                url: "{{ route('dispatch.update') }}", // Backend route
                type: "POST",
                data: formData,
                success: function(response) {
                    window.location.href = response.redirect;
                },
                error: function(xhr) {
                    // Determine error message
                    let error = xhr.responseJSON?.error  || 'Something went wrong!';
                    if (xhr.status === 400) {
                        Swal.fire({
                            title: 'Validation Error!',
                            text: error,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    } else if (xhr.status === 500) {
                        Swal.fire({
                            title: 'Server Error!',
                            text: 'An internal server error occurred. Please try again later.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        });
    });
</script>

@endsection
