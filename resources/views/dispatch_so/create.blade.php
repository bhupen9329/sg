@extends('layouts.main')
@section('title', 'Create - Dispatch')
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
            <h1>New Dispatch</h1>
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
                            <form id="dispatchForm" class="row g-3" method="post" action="{{ route('dispatch_main.redirect') }}">
                                @csrf
                                <div class="row mb-3">

                                    <?php
                                    $currentDate = date('Y-m-d');
                                    ?>

                                    <div class="col-md-6 mt-4">
                                        <label for="get_miller_id" class="form-label">From</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company custom-select" id="get_miller_id"
                                            name="po_company_id" onchange="fetchPoNumbers(this)">
                                            <option value="" disabled selected>Select Company</option>
                                            @foreach ($companies_po as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-4">
                                        <label for="to_company_id" class="form-label">To</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company custom-select" id="to_company_id"
                                            name="so_company_id" onchange="fetchSalesOrders(this)">
                                            <option value="" disabled selected>Select Company</option>
                                            @foreach ($companies_so as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                           {{-- ............................................................. Sales Details................................................................  --}}
                                           <div class="row mt-5">
                                            <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">SO Selected Details</h4>
                                            {{-- <button type="button" id="addRowBtn" class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1" onclick="addRow()">Add Row</button> --}}
                                        </div>
        
                                        <table id="soTable" class="col-md-4 col-sm-4 col-xl-12 table">
                                            <thead>
                                                <tr>
                                                    <th>Date (DD/MM/YY)</th>
                                                    <th>SO Number</th>
                                                    <th>Item Name</th>
                                                    <th>SO Item No.</th>
                                                    <th>Quantity (Q)</th>
                                                    <th>Rest Quantity (Q)</th>
                                                    <th>SO Unit Price</th>
                                                    <th>SO Price</th>
                                                    <th>Select Conv Item</th>
                                                    <th>Enter Dispatch Qty</th>
                                                    <th>Duplicate</th>
                                                    <th>Edit</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Rows will be dynamically added here -->
                                            </tbody>
                                            
                                            <tfoot>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total Dispatch Qty SO</th>
                                                <th></th>
                                                <th><input type="number" id="total_so_dispatch_qty" class="form-control" value="0" readonly></th>
                                                <th></th>
                                            </tfoot>
                                        </table>

                                {{-- ............................................................. Purchase Details................................................................  --}}
                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">PO Selected Details</h4>
                                    {{-- <button type="button" id="addRowBtn" class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1" onclick="addRow()">Add Row</button> --}}
                                </div>

                                <table id="poTable" class="col-md-4 col-sm-4 col-xl-12 table">
                                    <thead>
                                        <tr>
                                            <th>Date (DD/MM/YY)</th>
                                            <th>PO Number</th>
                                            <th>Item Name</th>
                                            <th>PO Item No.</th>
                                            <th>Quantity (Q)</th>
                                            <th>Rest Quantity (Q)</th>
                                            <th>PO Unit Price</th>
                                            <th>PO Price</th>
                                            <th>Enter Dispatch Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be dynamically added here -->
                                    </tbody>

                                     
                                    <tfoot>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Total Dispatch Qty PO</th>
                                        <th></th>
                                        <th><input type="number" id="total_po_dispatch_qty" class="form-control" value="0" readonly></th>
                                        <th></th>
                                    </tfoot>
                                </table>
                     
                                <input type="hidden" id="po_item_id">
                                <input type="hidden" id="so_item_no" name="">
                                <input type="hidden" id="po_item_no" name="">


                                <div class="text-end mt-5" id="submit_button">
                                    <button type="submit"   class="btn btn-primary">Submit</button>
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



    <!-- Purchase Order Modal -->
    <div class="modal fade" id="companyModal" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="/your-action-url">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="companyModalLabel">Select Purchase Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Date (DD/MM/YY)</th>
                                        <th>PO Number</th>
                                        <th>Item Name</th>
                                        <th>PO Item No.</th>
                                        <th>Quantity (Q)</th>
                                        <th>Rest Quantity (Q)</th>
                                        <th>PO Unit Price</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rows will be populated here by JavaScript -->
                                </tbody>
                    
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="populateDispatchDetails()">Add to
                            Dispatch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Sales Order Modal -->
    <div class="modal fade" id="SalescompanyModal" tabindex="-1" aria-labelledby="companyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="/your-action-url">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="companyModalLabel">Select Sales Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Date (DD/MM/YY)</th>
                                        <th>SO Number</th>
                                        <th>Item Name</th>
                                        <th>SO Item No.</th>
                                        <th>Quantity (Q)</th>
                                        <th>Rest Quantity (Q)</th>
                                        <th>SO Unit Price</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rows will be populated here by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="populateSODispatchDetails()">Add to
                            Dispatch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>

        // function cloneRow(button) {
        //     const row = button.closest('tr'); // Find the current row
        //     const clonedRow = row.cloneNode(true); // Clone the row

        //     // Retain the values for input fields
        //     clonedRow.querySelectorAll('input').forEach(input => {
        //         input.value = input.value; // Retain the value in input fields
        //     });

        //     // Retain the selected option for each select dropdown
        //     clonedRow.querySelectorAll('select').forEach(select => {
        //         const originalSelect = row.querySelector(
        //             `select[name="${select.name}"]`); // Find the original select
        //         select.value = originalSelect.value; // Set the selected value of the cloned select
        //     });

        //     // Insert the cloned row below the current row
        //     row.parentNode.insertBefore(clonedRow, row.nextSibling);
        // }

        //         function cloneRow(button) {
        //     const row = button.closest('tr'); // Find the current row
        //     const clonedRow = row.cloneNode(true); // Clone the row

        //     // Get the values of the original row's input fields
        //     const originalQty = parseFloat(row.querySelector('input[name="po_rest_qty_show"]').value) || 0;
        //     const originalQtySO = parseFloat(row.querySelector('input[name="so_rest_qty_show[]"]').value) || 0;
        //     const originalQuantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;

        //     // Calculate the value for 'po_rest_qty_show' and 'so_rest_qty_show' in the cloned row
        //     const newRestQty = originalQty - originalQuantity;
        //     const newRestQtySO = originalQtySO - originalQuantity;
        //     // Update the cloned row values
        //     clonedRow.querySelectorAll('input').forEach(input => {
        //         if (input.name === 'po_rest_qty_show') {
        //             input.value = newRestQty.toFixed(3); // Set the new calculated value
        //         } else if (input.name === 'so_rest_qty_show[]') {
        //             input.value = newRestQtySO.toFixed(3); // Set the new calculated value
        //         } else if (input.name === 'quantity[]') {
        //             input.value = '0'; // Reset quantity to 0 for the new row
        //         } else {
        //             input.value = input.value; // Retain other input values
        //         }
        //     });

        //     // Retain the selected option for each select dropdown
        //     clonedRow.querySelectorAll('select').forEach(select => {
        //         const originalSelect = row.querySelector(`select[name="${select.name}"]`); // Find the original select
        //         select.value = originalSelect.value; // Set the selected value of the cloned select
        //     });

        //     // Reset dependent fields to prevent errors in cloned row
        //     clonedRow.querySelectorAll('input[type="number"]').forEach(input => {
        //         if (input.name.includes('dispatch') || input.name === 'quantity[]') {
        //             input.value = '0'; // Reset numeric inputs
        //         }
        //     });

        //     // Insert the cloned row below the current row
        //     row.parentNode.insertBefore(clonedRow, row.nextSibling);
        // }

     

        function formatDate(dateString) {
            if (!dateString) return ''; // Return empty string if no date is provided
            const date = new Date(dateString);

            // Format date as 'dd-MMM-yyyy'
            const day = String(date.getDate()).padStart(2, '0'); // Ensure two-digit day
            const month = date.toLocaleString('default', {
                month: 'short'
            }); // Get month in short format (e.g., 'Jan', 'Feb')
            const year = date.getFullYear();

            return `${day}-${month}-${year}`;
        }

        function calculateTotal(element) {
            const row = element.closest('tr');
            const unitPrice = parseFloat(row.querySelector('input[name="dispatch_unit_price[]"]').value) || 0;
            const unitPriceActual = parseFloat(row.querySelector('input[name="dispatch_unit_price_actual[]"]').value) || 0;
            const convRate = parseFloat(row.querySelector('input[name="conv_rate[]"]').value) || 0;
            const quantityInput = row.querySelector('input[name="quantity[]"]');

            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const po_rest_quantity = parseFloat(row.querySelector('input[name="po_rest_qty_show"]').value) || 0;
            const so_rest_quantity = parseFloat(row.querySelector('input[name="so_rest_qty_show[]"]').value) || 0;
            const po_item_number = parseFloat(row.querySelector('input[name="po_item_number[]"]').value) || 0;


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

            Array.from(document.querySelector("#myTable tbody").rows).forEach((row) => {
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


            const sounitPrice = parseFloat(row.querySelector('input[name="dispatch_so_unit_price[]"]').value) || 0;
            const sounitPriceActual = parseFloat(row.querySelector('input[name="dispatch_so_unit_price_actual[]"]')
                .value) || 0;
            const freight_insurance = parseFloat(row.querySelector('input[name="dispatch_fregiht_insuance[]"]').value) || 0;

            // Calculate the total values for PO and SO based on the quantity
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

            // Calculate the total quantity for all rows
            let totalQty = 0;
            const rows = document.querySelectorAll('#myTable tbody tr'); // Change the selector if needed
            rows.forEach(function(row) {
                const qty = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
                totalQty += qty;
            });

            document.getElementById('totalQty').textContent = totalQty.toFixed(3);
        }

        function PORow(Date = '', poNumber = '', ItemName = '', poItemNumber = '', Quantity = '', RestQty = '', UnitPrice =
            '', Price = '', PoId = '') {
            var table = document.getElementById("poTable").getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);
            const formattedDate = formatDate(Date);

            // Assuming `editRoute` is passed to JavaScript as a global variable from Blade
            const editRoute = "{{ route('purchase.edit', ':id') }}";
            const editUrl = editRoute.replace(':id', PoId);

            // Check if Quantity equals RestQty
            const editOption = (Quantity === RestQty) ?
                `<a href="${editUrl}" class="dropdown-item"
               style="text-decoration: underline; color: blue; text-align: center;">
               <i class="fa-solid fa-pen-to-square"></i>
           </a>` :
                '';

            newRow.innerHTML = `
        <td>${formattedDate}</td>
        <td>${poNumber}</td>
        <td>${ItemName}</td>
        <td>
            <input type="hidden" name="po_item_no[]" value="${poItemNumber}">
            ${poItemNumber}
        </td>
        <td>${Quantity}</td>
        <td>${RestQty}</td>
        <td>${UnitPrice}</td>
        <td>${Price}</td>
    
         <td>
            <input type="number" name="dispatch_po_qty[]" oninput="calculateTotalDispatch(this)" class="form-control" value="0" max="${RestQty}" step="0.001">
        </td>
            <td>${editOption}</td>
    `;
       
        }



        function formatDate(dateString) {
            if (!dateString) return ''; // Return empty string if no date is provided
            const date = new Date(dateString);

            // Format date as 'dd-MMM-yyyy'
            const day = String(date.getDate()).padStart(2, '0'); // Ensure two-digit day
            const month = date.toLocaleString('default', {
                month: 'short'
            }); // Get month in short format (e.g., 'Jan', 'Feb')
            const year = date.getFullYear();

            return `${day}-${month}-${year}`;
        }

        function SORow(Date = '', soNumber = '', ItemName = '', soItemNumber = '', Quantity = '', RestQty = '', UnitPrice =
            '', Price = '', SoId = '',  subItems = []) {
            var table = document.getElementById("soTable").getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);
            const formattedDate = formatDate(Date);

            let subItemOptions = '<option readonly>Select Item</option>';
            subItems.forEach(subItem => {
                subItemOptions += `<option value="${subItem.id}">${subItem.sub_category}</option>`;
            });

            // Assuming `editRoute` is passed to JavaScript as a global variable from Blade
            const editRoute = "{{ route('sales.edit', ':id') }}"; // Update route name if needed
            const editUrl = editRoute.replace(':id', SoId);

            // Check if Quantity equals RestQty
            const editOption = (Quantity === RestQty) ?
                `<a href="${editUrl}" class="dropdown-item"
               style="text-decoration: underline; color: blue; text-align: center;">
               <i class="fa-solid fa-pen-to-square"></i>
           </a>` :
                '';

                const cloneOption = 
                `<button type="button" class="btn btn-success" onclick="cloneRow(this)">
        <i class="fas fa-copy"></i>
    </button>`;


            newRow.innerHTML = `
        <td>${formattedDate}</td>
        <td>${soNumber}</td>
        <td>${ItemName}</td>
        <td>
            <input type="hidden" name="so_item_no[]" value="${soItemNumber}">
            ${soItemNumber}
        </td>
        <td>${Quantity}</td>
        <td>${RestQty}</td>
        <td>${UnitPrice}</td>
        <td>${Price}</td>
        <td><select name="sub_cat_id[]" onchange="get_conv_price(this)" class="form-select">${subItemOptions}</select></td>
            <td>
            <input type="number" name="dispatch_so_qty[]" oninput="calculateTotalDispatch(this)" class="form-control" value="0" max="${RestQty}" step="0.001">
        </td>
        <td>${cloneOption}</td>
        <td>${editOption}</td>
    `;
        }



        // function deleteRow(button) {
        //     // Find the row that was clicked
        //     const row = button.closest('tr');

        //     // Get the item number from the clicked row (for po_item_no)
        //     const itemNumber = row.querySelector('[name="po_item_number[]"]').value;

        //     if (!itemNumber) {
        //         alert("Item number not found. Cannot delete row.");
        //         return;
        //     }

        
        //     row.remove();
        // }

        
        function cloneRow(button) {
            const row = button.closest('tr'); // Find the current row
            const clonedRow = row.cloneNode(true); // Clone the row

            // Retain the values for input fields
            clonedRow.querySelectorAll('input').forEach(input => {
                input.value = input.value; // Retain the value in input fields
            });

            // Retain the selected option for each select dropdown
            clonedRow.querySelectorAll('select').forEach(select => {
                const originalSelect = row.querySelector(
                    `select[name="${select.name}"]`); // Find the original select
                select.value = originalSelect.value; // Set the selected value of the cloned select
            });

            // Insert the cloned row below the current row
            row.parentNode.insertBefore(clonedRow, row.nextSibling);

            calculateTotal(button);
        }

        function deleteRow(button) {
    // Find the row that was clicked
    const row = button.closest('tr');

    // Get the value of 'po_item_number[]' from the current row
    const currentPoItemInput = row.querySelector('[name="po_item_number[]"]');
    const currentPoItemValue = currentPoItemInput ? currentPoItemInput.value : null;

    if (!currentPoItemValue) {
        row.remove();
    }

    // Get the value of 'so_item_no[]' from the current row
    const currentSoItemSelect = row.querySelector('[name="so_item_no[]"]');
    const currentSoItemValue = currentSoItemSelect ? currentSoItemSelect.value : null;

    if (!currentSoItemValue) {
        row.remove();
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

function calculateTotalDispatch(input) {
    // Update total for PO table
    const poInputs = document.querySelectorAll("#poTable tbody input[name='dispatch_po_qty[]']");
    let totalPoDispatch = 0;

    poInputs.forEach(input => {
        totalPoDispatch += parseFloat(input.value) || 0; // Add value or 0 if empty
    });
    document.getElementById("total_po_dispatch_qty").value = totalPoDispatch;

    // Update total for SO table
    const soInputs = document.querySelectorAll("#soTable tbody input[name='dispatch_so_qty[]']");
    let totalSoDispatch = 0;

    soInputs.forEach(input => {
        totalSoDispatch += parseFloat(input.value) || 0; // Add value or 0 if empty
    });
    document.getElementById("total_so_dispatch_qty").value = totalSoDispatch;

    const submitButton = document.getElementById("submit_button");
    if (totalPoDispatch !== totalSoDispatch) {
        submitButton.style.display = "none"; 
    } else {
        submitButton.style.display = "block"; 
    }
}

        // function populateDispatchDetails() {
        //     const selectedPOs = document.querySelectorAll('.po-checkbox:checked');

        //     selectedPOs.forEach(po => {
        //         addRow(po.dataset.itemName, po.dataset.quantity, po.dataset.unitPrice);
        //     });

        //     // Close the modal after populating details
        //     var modal = bootstrap.Modal.getInstance(document.getElementById('companyModal'));
        //     modal.hide();
        // }
        function populateSODispatchDetails() {
            const selectedSOs = document.querySelectorAll('.so-checkbox:checked');
            const rows = document.querySelectorAll('#myTable tbody tr'); // Get all rows in the table

            // Iterate over each row to populate dropdowns
            rows.forEach(row => {
                const itemName = row.querySelector('td:first-child').textContent
                    .trim(); // Get the itemName for the current row
                const dropdown = row.querySelector(
                    'select[name="so_item_no[]"]'); // Get the dropdown for the current row

                // Clear existing options
                dropdown.innerHTML = '<option value="" disabled selected>Select SO Item</option>';

                selectedSOs.forEach(so => {
                    const so_item_no = so.dataset.id; // Get SO item number

                    // Fetch item details for the SO item
                    $.ajax({
                        url: '/get-item-details-so', // Adjust to the route handling item details
                        method: 'POST',
                        data: {
                            so_item_no: so_item_no,
                            _token: '{{ csrf_token() }}' // Include CSRF token for security
                        },
                        success: function(response) {
                            const so_item = response.so_items;

                            // Check if the SO item matches the itemName for this row
                            if (so_item.name === itemName) {
                                // Create an option element
                                const option =
                                    `<option value="${so_item.so_item_no}">${so_item.name}, ${so_item.unit_price}, ${so_item.so_dispatch_rest_qty}-(${so_item.so_item_no})</option>`;
                                dropdown.insertAdjacentHTML('beforeend', option);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching item details:", error);
                        }
                    });
                });
            });

            // Keep `SORow` functionality unchanged
            selectedSOs.forEach(so => {
                const so_item_no = so.dataset.id; // Get SO item number

                $.ajax({
                    url: '/get-item-details-so', // Adjust to the route handling item details
                    method: 'POST',
                    data: {
                        so_item_no: so_item_no,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        const so_item = response.so_items;
                        console.log(so_item);

                        // Original `SORow` call
                        SORow(
                            so_item.date,
                            so_item.so_number,
                            so_item.name,
                            so_item.so_item_no,
                            so_item.qty,
                            so_item.so_dispatch_rest_qty,
                            so_item.unit_price,
                            so_item.so_price,
                            so_item.so_id,
                            response.subitem,
                            response.freight_insurance.freight_rate,
                            response.freight_insurance.insurance_rate,
                        );
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching item details:", error);
                    }
                });
            });

            $('#SalescompanyModal').modal('hide');
        }




        function populateDispatchDetails() {
            let po_item = $('#po_item_id').val().trim();

            if (po_item !== null && po_item !== "") {
                Swal.fire({
                    title: 'Dispatch Details',
                    text: 'Dispatch details have been populated already!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
            const selectedPOs = document.querySelectorAll('.po-checkbox:checked');
            selectedPOs.forEach(po => {
                // Get item name, quantity, and unit price from the checkbox dataset
                const itemId = po.dataset.id;
                const itemName = po.dataset.itemName;
                const quantity = po.dataset.quantity;
                const unitPrice = po.dataset.unitPrice;
                const poItemNo = po.dataset.poItemNo;

                $('#po_item_id').val(itemId);
                $('#po_item_no').val(poItemNo);
                // console.log(`Item ID: ${itemId}.Item Name: ${itemName}, Quantity: ${quantity}, Unit Price: ${unitPrice}`);

                // Make an AJAX request to fetch additional item details based on the item name
                $.ajax({
                    url: '/get-item-details', // Adjust to the route handling item details
                    method: 'POST',
                    data: {
                        item_name: itemName,
                        item_id: itemId,
                        poItemNo: poItemNo,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        // Assuming `response` contains the detailed data for the item
                        const details = response.item_details;
                        const subitems = response.subItems;
                        const po_item = response.po_items;
                        const freight = response.freight;
                        const insurance = response.insurance;
                        const qty = response.po_dispatch_rest_qty;
                        // Populate the row with additional details

                        PORow(po_item.date, po_item.document_number, po_item.name, po_item.po_item_no,
                            po_item.qty, po_item.po_dispatch_rest_qty, po_item.unit_price, po_item
                            .po_price, po_item.po_id);

                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching item details:", error);
                    }
                });
            });

            // Close the modal after populating details
            const modal = bootstrap.Modal.getInstance(document.getElementById('companyModal'));
            modal.hide();
        }
    </script>




    <script>
        $(document).ready(function() {
            $('#get_miller_id').on('change', function() {
                const selectedCompanyId = $(this).val();

                if (selectedCompanyId) {
                    // Show the modal
                    $('#companyModal').modal('show');

                    // Optional: Fetch and display data in the modal based on selectedCompanyId
                    // $.ajax({
                    //     url: '/get-company-details', // Adjust this to your route
                    //     type: 'GET',
                    //     data: { company_id: selectedCompanyId },
                    //     success: function(response) {
                    //         $('#companyModal .modal-body').html(response); // Update modal content
                    //     },
                    //     error: function(error) {
                    //         console.error("Error fetching company details:", error);
                    //     }
                    // });
                }
            });
        });

        $(document).ready(function() {
            $('#to_company_id').on('change', function() {
                const selectedCompanyId = $(this).val();

                if (selectedCompanyId) {
                    // Show the modal
                    $('#SalescompanyModal').modal('show');

                }
            });
        });





        function fetchPoNumbers(companySelect) {

            const companyId = companySelect.value;

            $.ajax({
                url: '/get-purchase-orders', // Ensure this is the correct route
                method: 'POST',
                data: {
                    company_id: companyId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Clear previous rows in the table body
                    const tableBody = $('#dataTable tbody');
                    tableBody.empty();

                    // Populate the table with new rows from the response
                    response.purchase_orders.forEach(po => {
                        const dateObj = new Date(po.date);
                        const day = dateObj.getDate().toString().padStart(2, '0');
                        const month = dateObj.toLocaleString('en-GB', {
                            month: 'short'
                        });
                        const year = dateObj.getFullYear();
                        const formattedDate = `${day}-${month}-${year}`;
                        const row = `
                    <tr>
                        <td>
                            <input type="checkbox" class="po-checkbox" 
                                   data-id="${po.id}" 
                                   data-item-name="${po.name}" 
                                   data-quantity="${po.qty}" 
                                   data-unit-price="${po.unit_price}"
                                   data-po-item-no="${po.po_item_no}"
                                   >
                        </td>
                        <td>${formattedDate}</td>
                        <td>${po.document_number}</td>
                        <td>${po.name}</td>
                        <td>${po.po_item_no}</td>
                        <td>${po.qty}</td>
                        <td>${po.po_dispatch_rest_qty}</td>
                        <td>${po.unit_price}</td>
                        <td>${po.remark ?? 'N/A'}</td>
                    </tr>`;
                        tableBody.append(row);
                    });

                    // Show the modal after populating the table
                    $('#companyModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Error fetching purchase orders:', xhr);
                    alert('An error occurred while fetching purchase orders. Please try again.');
                }
            });
        }

        function fetchSalesOrders(selectElement) {
            const companyId = selectElement.value;
            $.ajax({
                url: '/get-sales-orders', // Adjust this URL to match your backend route
                type: 'POST',
                data: {
                    company_id: companyId,
                    "_token": "{{ csrf_token() }}" // CSRF token for security in Laravel
                },
                success: function(response) {
                    // Clear previous rows in the table body
                    const tableBody = $('#dataTable tbody');
                    tableBody.empty();

                    // Check if sales_orders is defined and is an array
                    if (response.salesOrders && Array.isArray(response.salesOrders)) {
                        // Populate the table with new rows from the response
                        response.salesOrders.forEach(so => {
                            const formattedDate = formatDate(so.date);
                            const row = `
                            
                        <tr>
                            <td>
                                <input type="checkbox" class="so-checkbox" 
                                       data-id="${so.so_item_no}" 
                                       data-item-name="${so.so_number}" 
                                       data-quantity="${so.qty}" 
                                       data-unit-price="${so.unit_price}"
                                      >
                            </td>
                            <td>${formattedDate}</td>
                            <td>${so.so_number}</td>
                            <td>${so.name}</td>
                            <td>${so.so_item_no}</td>
                            <td>${so.qty}</td>
                            <td>${so.so_dispatch_rest_qty  }</td>
                            <td>${so.unit_price}</td>
                             <td>${so.terms_condition ?? 'N/A'}</td>
                        </tr>`;
                            tableBody.append(row);
                        });

                        // Show the modal after populating the table
                        $('#SalescompanyModal').modal('show');
                    } else {
                        console.error('No sales orders found or response is not in expected format:', response);
                        alert('No sales orders available for this company.');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching sales orders:', xhr);
                    alert('An error occurred while fetching sales orders. Please try again.');
                }
            });
        }


        let selectedPoItems = [];

        // Fetch Purchase Order Items and save selection
        function fetchPoItems(element) {
            const poId = element.value;

            $.ajax({
                url: '{{ route('getPoItems') }}',
                type: 'POST',
                data: {
                    po_id: poId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#po_item').empty().append('<option selected disabled>Select PO Item</option>');

                    response.poItems.forEach(item => {
                        $('#po_item').append(new Option(item.name, item.id));
                    });

                    // Capture selected items to filter SO items later
                    $('#po_item').on('change', function() {
                        selectedPoItems = $(this).val();
                    });
                }
            });
        }

        function fetchPoItems(poSelect) {
            const poId = poSelect.value;

            $.ajax({
                url: '/get-po-items', // Update to the correct route
                method: 'POST',
                data: {
                    po_id: poId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    let itemOptions = '<option selected disabled>Select PO Item</option>';
                    response.po_items.forEach(item => {
                        itemOptions += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $('#po_item').html(itemOptions);

                }
            });
        }


        function fetchSoItems(selectElement) {
            const salesOrderId = selectElement.value;

            $.ajax({
                url: '/get-so-items', // Adjust the URL to match your route
                type: 'POST',
                data: {
                    sales_order_id: salesOrderId,
                    "_token": "{{ csrf_token() }}" // CSRF token for security
                },
                success: function(response) {
                    let soItemOptions = '<option selected disabled>Select SO Item</option>';
                    response.soItems.forEach(item => {
                        soItemOptions += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $('#so_item').html(soItemOptions);
                }
            });
        }



        function addRowItem() {

            const item_id = document.getElementById('po_item').value
            $.ajax({
                url: '/get-item-details', // Adjust the URL to match your route
                type: 'POST',
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}" // CSRF token for security
                },

                success: function(response) {


                    if (response && response.items) {
                        const selectedItem = response.items;


                        $('#buyer_name_id').val(selectedItem.name);

                        let itemOptions = '<option selected disabled>Select Subcategory</option>';
                        response.subItems.forEach(item => {
                            itemOptions += `<option value="${item.id}">${item.sub_category}</option>`;
                        });
                        $('#brand_name_id').html(itemOptions);
                    } else {
                        console.error("No items found in the response or response structure is incorrect.");
                    }
                },






            });
        }





        function fetchPOItemsRate() {

            const item_id = document.getElementById('brand_name_id')
            console.log(item_id);
            $.ajax({
                url: '/get-po-items-rate', // Adjust the URL to match your route
                type: 'POST',
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}" // CSRF token for security
                },

                success: function(response) {


                    if (response && response.items) {
                        const selectedItem = response.items;


                        $('#buyer_name_id').val(selectedItem.name);

                        let itemOptions = '<option selected disabled>Select Subcategory</option>';
                        response.subItems.forEach(item => {
                            itemOptions += `<option value="${item.id}">${item.sub_category}</option>`;
                        });
                        $('#brand_name_id').html(itemOptions);
                    } else {
                        console.error("No items found in the response or response structure is incorrect.");
                    }
                },
            });
        }
    </script>


    <script>
        function get_selected_type(value) {

            let check_value = value;
            // console.log(check_value);
            if (check_value === 'warehouse') {
                $('#warehouse_option').css('display', 'block');
                $('#miller_option').css('display', 'none');
            } else {
                $('#miller_option').css('display', 'block');
                $('#warehouse_option').css('display', 'none');
            }

        }


        function check_same_data(lastItemId) {
            // Get the current item elements based on lastItemId
            const currentbuyer_name_idItemElement = document.getElementById(`buyer_name_id${lastItemId}`);
            const currentItemElement = document.getElementById(`brand_name_id${lastItemId}`);
            const currentItemSubCategoryElement = document.getElementById(`bag_name_id${lastItemId}`);

            // Check if any of the elements do not exist
            if (!currentbuyer_name_idItemElement || !currentItemElement || !currentItemSubCategoryElement) {
                return;
            }

            // Get the values of the current elements
            const buyerItemId = currentbuyer_name_idItemElement.value;
            const currentItemId = currentItemElement.value;
            const currentItemSubCategory = currentItemSubCategoryElement.value;
            // console.log(buyerItemId, currentItemId, currentItemSubCategory);

            let isDuplicate = false;
            // Check for duplicates
            for (let i = 1; i < lastItemId; i++) {
                const selectbuyer_name_idItemElement = document.getElementById(`buyer_name_id${i}`);
                const itemElement = document.getElementById(`brand_name_id${i}`);
                const itemSubCategoryElement = document.getElementById(`bag_name_id${i}`);

                // Skip iteration if any of the elements do not exist
                if (!selectbuyer_name_idItemElement || !itemElement || !itemSubCategoryElement) {
                    continue;
                }
                const selected_buyer_id = selectbuyer_name_idItemElement.value;
                const itemId = itemElement.value;
                const itemSubCategory = itemSubCategoryElement.value;

                // console.log(selected_buyer_id, itemId, itemSubCategory);


                if (buyerItemId === selected_buyer_id && currentItemId === itemId && currentItemSubCategory ===
                    itemSubCategory) {
                    isDuplicate = true;
                    break;
                }
            }

            if (isDuplicate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Duplicate entry found.'
                }).then(() => {
                    resetRow_in_same_data(lastItemId);
                });
            }
        }


        function resetRow_in_same_data(lastItemId) {
            // Reset specific input fields in the row
            $(`#buyer_name_id${lastItemId}`).val('').trigger('change');
            $(`#brand_name_id${lastItemId}`).val('').trigger('change');
            $(`#bag_name_id${lastItemId}`).val('').trigger('change');
            $(`#bundle_${lastItemId}`).val('');
            $(`#weight_${lastItemId}`).val('');
        }
        $(document).ready(function() {
            $('.Select-Company').select2();
            $('.miller_option-Receiving-Point').select2();
            $('.warehouse_option-Receiving-Point').select2();
            $('.buyer_option-Receiving-Point').select2();
            $('.Select-Bargain').select2();
            //click add row button
            $('#addRowBtn').click();
            $('#addRowBtn').hide();
        });

        function get_seller_id(id) {
            let buyer_id = id.value;
            // console.log(seller_id);

            let bargain_option = document.querySelector('#seller_option');

            // console.log(bargain_option);
            $.ajax({
                url: "{{ url('get_bargain_number') }}",
                method: "POST",
                data: {
                    buyer_id: buyer_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res);
                    let bargain_number = res;
                    if (bargain_number) {
                        let htmldata = '<option value="" selected disabled>Select Bargain</option>';
                        for (let bargain of bargain_number) {
                            htmldata += `<option value="${bargain.id}">${bargain.document_number}</option>`;
                        }
                        bargain_option.innerHTML = htmldata;
                    }

                }
            })

        }

        function get_brand(selectElement) {
            let so_item_id = selectElement.value;
            let row = selectElement.parentNode.parentNode;
            let BrandSelect = row.querySelector('.select_brand_name');

            $.ajax({
                url: "{{ url('get_brand_list') }}",
                method: "POST",
                data: {
                    so_item_id: so_item_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    let data = res.brand;
                    if (data) {
                        let htmldata = '<option value="" disabled selected>Select Brands</option>';
                        data.forEach(item => {
                            htmldata += `<option value="${item.id}">${item.brand_name}</option>`;
                        });
                        BrandSelect.innerHTML = htmldata;
                    }
                },
                error: function(error) {
                    console.error("Error fetching brands:", error);
                }
            });
        }

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
                    const convRateFieldShow = $(selectElement).closest('tr').find(
                        'input[name="conv_rate_show[]"]');

                    if (response && response.item_price) {
                        // Set the conversion rate from the response
                        convRateField.val(response.item_price);
                        convRateFieldShow.val(response.item_price);

                    } else {
                        convRateField.val(0);
                        convRateFieldShow.val(0);
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



        function fetchUnitPrice(selectElement) {
    const soItemNo = selectElement.value; // Get the selected SO item number
    const row = selectElement.closest('tr'); // Find the current table row
    const unitPriceField = row.querySelector('input[name="dispatch_so_unit_price_actual[]"]'); // Unit price field in the same row
    const QtyField = row.querySelector('input[name="quantity[]"]'); // Quantity field in the same row
    const QtyFieldValue = parseFloat(row.querySelector('input[name="quantity_po[]"]').value); // Get the current quantity value as a number
    const QtyFieldShow = row.querySelector('input[name="so_rest_qty_show[]"]'); // SO Rest Qty field

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
                    const responseQty = parseFloat(response.qty); // Parse response.qty as a number
                    unitPriceField.value = response.unit_price; // Set the unit price

                    // Compare quantities properly and set max attribute
                    if (responseQty <= QtyFieldValue) {
                        QtyField.setAttribute('max', responseQty); // Set the max attribute to the returned quantity
                    } else {
                        QtyField.setAttribute('max', QtyFieldValue); // Set the max to the original quantity
                    }

                    QtyFieldShow.value = responseQty;

                    // Update so_rest_qty_show for previous rows
                    let remainingQty = responseQty; // Start with the total response_qty

                    // Loop through all previous rows (above the current row)
                    Array.from(document.querySelector("#myTable tbody").rows).forEach((prevRow) => {
                        if (prevRow === row) return; // Skip the current row
                        
                        const prevSoItemNo = prevRow.querySelector('select[name="so_item_no[]"]').value; // Get SO item number from the previous row
                        const prevSoRestQtyInput = prevRow.querySelector('input[name="so_rest_qty_show[]"]');
                        const prevTotalDispatchQty = parseFloat(prevRow.querySelector('input[name="quantity[]"]').value) || 0;

                        // Only process rows with the same SO item number
                        if (prevSoItemNo === soItemNo) {
                            // Calculate remaining SO quantity for previous rows
                            const remainingSoQty = Math.max(responseQty - prevTotalDispatchQty, 0);

                            // Update the so_rest_qty_show for the current row
                            if (prevSoRestQtyInput) {
                                QtyFieldShow.value = remainingSoQty.toFixed(3); // Set the remaining quantity with 3 decimal places
                            }

                            // Update the remainingQty after processing this row
                            remainingQty -= prevTotalDispatchQty;
                        }
                    });

                    // Call your function to recalculate totals after the update
                    calculateTotal(selectElement);
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

    </script>

    <script>
        $(document).ready(function() {
            // Initialize select2 for all custom-select elements
            $('.custom-select').select2();

            // Focus the date input after select2 initialization


            // Focus the search box when the select2 dropdown is opened
            $('.custom-select').on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-search__field').focus();
                }, 100); // Small delay ensures the search field is rendered before focusing
            });
        });

        $(document).ready(function() {
            // Focus the date input when the page is loaded
            $('#raised_date_input').focus();
        });
    </script>

    

@endsection
