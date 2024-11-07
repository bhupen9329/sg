@extends('layouts.main')
@section('title', 'Index - Dispatch')
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
                            <form class="row g-3" method="post" action="{{ route('dispatch.store') }}">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="get_miller_id" class="form-label">From</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company" id="get_miller_id" name="po_company_id"
                                            onchange="fetchPoNumbers(this)" required>
                                            <option value="" disabled selected>Select Company</option>
                                            @foreach ($companies_po as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="to_company_id" class="form-label">To</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company" id="to_company_id" name="so_company_id"
                                            onchange="fetchSalesOrders(this)" required>
                                            <option value="" disabled selected>Select Company</option>
                                            @foreach ($companies_so as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-6 mt-4"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="to_company_id" class="form-label">Vehicle Number</label>
                                        <input type="text" class="form-control" name="vehicle_number">
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">Dispatch Details</h4>
                                    {{-- <button type="button" id="addRowBtn" class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1" onclick="addRow()">Add Row</button> --}}
                                </div>

                                <table id="myTable" class="col-md-4 col-sm-4 col-xl-12 table">
                                    <thead>
                                        <tr>
                                            <th class="table_heading_long">Base Item Name<span
                                                    class="required-classes">*</span></th>
                                            <th class="table_heading_long">Conv Item Name</th>
                                            <th class="table_heading_normal">Conv Rate</th>
                                            <th class="table_heading_long">PO Unit Price</th>
                                            <th class="table_heading_long">PO Freight</th>
                                            <th class="table_heading_long">PO Other</th>
                                            <th class="table_heading_long">Payable Total<span
                                                class="required-classes">*</span></th>
                                            <th class="table_heading_long">SO Unit Price</th>
                                            <th class="table_heading_long">SO Freight</th>
                                            <th class="table_heading_long">SO Other</th>
                                            <th class="table_heading_long">Receivable Total<span
                                                class="required-classes">*</span></th>
                                            <th class="table_heading_normal">Quantity<span
                                                    class="required-classes">*</span>
                                            </th>
                                            <th class="table_heading_action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be dynamically added here -->
                                    </tbody>
                                </table>
                                {{-- ............................................................. Purchase Details................................................................  --}}
                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">PO Details</h4>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be dynamically added here -->
                                    </tbody>
                                </table>
                                {{-- ............................................................. Sales Details................................................................  --}}
                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">SO Details</h4>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be dynamically added here -->
                                    </tbody>
                                </table>
                                <input type="hidden" id="po_item_id">
                                <input type="hidden" id="so_item_no" name="so_item_no">
                                <input type="hidden" id="po_item_no" name="po_item_no">

                                <div class="col-md-4">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks here..."></textarea>
                                </div>

                                <div class="text-end mt-5">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
                                        <th>PO Price</th>
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
                                        <th>SO Price</th>
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
        var lastItemId = 1;

        function addRow(itemName = '', itemId = '', quantity = '', unitPrice = '', subItems = []) {
            var table = document.getElementById("myTable").getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);
            let subItemOptions = '<option readonly>Select Item</option>';
            subItems.forEach(subItem => {
                subItemOptions += `<option value="${subItem.id}">${subItem.sub_category}</option>`;
            });

            newRow.innerHTML = `
    <td>${itemName}</td>
    <input type="hidden" name="cat_id[]" class="form-control" value="${itemId}" required>
    <td>
        <select name="sub_cat_id[]" onchange="get_conv_price(this)" class="form-select">${subItemOptions}</select>
    </td>
    <td><input type="number" name="conv_rate[]" value="0" class="form-control" oninput="calculateTotal(this)" required /></td>
    <td><input type="number" name="dispatch_unit_price[]" class="form-control"  value="${unitPrice}"  readonly required /></td>
        <td><input type="number" name="dispatch_freight[]" value="0" class="form-control" oninput="calculateTotal(this)" required /></td>
    <td><input type="number" name="dispatch_other[]" value="0" class="form-control" oninput="calculateTotal(this)" required /></td>
      <td><input type="number" name="dispatch_total[]" value="${unitPrice}" class="form-control" readonly required /></td>
    <td><input type="number" name="dispatch_so_unit_price[]" id="so_unit_price"  class="form-control" readonly required /></td>
     <td><input type="number" name="dispatch_so_freight[]" value="0" class="form-control" oninput="calculateTotal(this)" required /></td>
    <td><input type="number" name="dispatch_so_other[]" value="0" class="form-control" oninput="calculateTotal(this)" required /></td>
  
    <td><input type="number" name="dispatch_so_total[]" value="0" class="form-control" readonly required /></td>
    <td><input type="number" name="quantity[]" step="0.001" class="form-control" value="" required /></td>
    <td>
        <button type="button" class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>
    </td>
`;


            lastItemId++;
        }

        function calculateTotal(element) {
            const row = element.closest('tr');
            const unitPrice = parseFloat(row.querySelector('input[name="dispatch_unit_price[]"]').value) || 0;
            const convRate = parseFloat(row.querySelector('input[name="conv_rate[]"]').value) || 0;
            const freight = parseFloat(row.querySelector('input[name="dispatch_freight[]"]').value) || 0;
            const other = parseFloat(row.querySelector('input[name="dispatch_other[]"]').value) || 0;


            const sounitPrice = parseFloat(row.querySelector('input[name="dispatch_so_unit_price[]"]').value) || 0;
            const sofreight = parseFloat(row.querySelector('input[name="dispatch_so_freight[]"]').value) || 0;
            const soother = parseFloat(row.querySelector('input[name="dispatch_so_other[]"]').value) || 0;

            // Calculate total and update the total_amount field
            const totalAmount = unitPrice + convRate + freight + other;
            const totalSoAmount = sounitPrice + convRate + sofreight + soother;

            row.querySelector('input[name="dispatch_total[]"]').value = totalAmount.toFixed(2);
            row.querySelector('input[name="dispatch_so_total[]"]').value = totalSoAmount.toFixed(2);
        }

        function PORow(Date = '', poNumber = '', ItemName = '', poItemNumber = '', Quantity = '', RestQty = '', UnitPrice =
            '', Price = '', ) {
            var table = document.getElementById("poTable").getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);
            newRow.innerHTML = `
            <td> ${Date}</td>
            <td>${poNumber}</td>
            <td>${ItemName}</td>
            <td>${poItemNumber}</td>
            <td>${Quantity}</td>
            <td>${RestQty}</td>
            <td>${UnitPrice}</td>
            <td>${Price}</td>
           
        `;
            lastItemId++;
        }

        function SORow(Date = '', soNumber = '', ItemName = '', soItemNumber = '', Quantity = '', RestQty = '', UnitPrice =
            '', Price = '', ) {
            var table = document.getElementById("soTable").getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);
            newRow.innerHTML = `
            <td> ${Date}</td>
            <td>${soNumber}</td>
            <td>${ItemName}</td>
            <td>${soItemNumber}</td>
            <td>${Quantity}</td>
            <td>${RestQty}</td>
            <td>${UnitPrice}</td>
            <td>${Price}</td>
        `;
            lastItemId++;
        }



        function deleteRow(button) {
            // Find the row that was clicked
            document.getElementById('po_item_id').value = "";
            document.getElementById('po_item_no').value = "";
            document.getElementById('so_item_no').value = "";
            document.getElementById('get_miller_id').value = "";
            document.getElementById('to_company_id').value = "";
            var row = button.parentNode.parentNode;

            // Get the table ID where the row is located
            var table = row.closest('table');

            // Remove the row from both tables
            if (table.id === 'myTable') {
                // Get the index of the row in myTable
                var index = row.rowIndex;

                // Delete the same row in poTable using the same index
                document.getElementById('poTable').deleteRow(index);
                document.getElementById('soTable').deleteRow(index);
            }

            // Delete the row from myTable
            row.parentNode.removeChild(row);

            document.getElementById('poTable').deleteRow(index);
            document.getElementById('soTable').deleteRow(index);

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

            selectedSOs.forEach(so => {
                // Get item name, quantity, and unit price from the checkbox dataset
                const so_item_no = so.dataset.id;
                const itemName = so.dataset.itemName;
                const quantity = so.dataset.quantity;
                const unitPrice = so.dataset.unitPrice;
                $('#so_item_no').val(so_item_no);
                $.ajax({
                    url: '/get-item-details-so', // Adjust to the route handling item details
                    method: 'POST',
                    data: {
                        so_item_no: so_item_no,

                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        const so_item = response.so_items;
                        // Populate the row with additional details
                        SORow(so_item.date, so_item.so_number, so_item.name, so_item.so_item_no,
                            so_item.qty, so_item.so_dispatch_rest_qty, so_item.unit_price, so_item
                            .so_price);
                 
                            $('#so_unit_price').val(so_item.unit_price);

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
                        // Populate the row with additional details
                        addRow(details.name, details.id, quantity, unitPrice, subitems);
                        PORow(po_item.date, po_item.document_number, po_item.name, po_item.po_item_no,
                            po_item.qty, po_item.po_dispatch_rest_qty, po_item.unit_price, po_item
                            .po_price);

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
                        const row = `
                    <tr>
                        <td>
                            <input type="checkbox" class="po-checkbox" 
                                   data-id="${po.id}" 
                                   data-item-name="${po.name}" 
                                   data-quantity="${po.qty}" 
                                   data-unit-price="${po.unit_price}"
                                   data-po-item-no="${po.po_item_no}"
                                    onchange="singleCheckboxSelection(this)">
                        </td>
                        <td>${new Date(po.date).toLocaleDateString('en-GB')}</td>
                        <td>${po.document_number}</td>
                        <td>${po.name}</td>
                        <td>${po.po_item_no}</td>
                        <td>${po.qty}</td>
                        <td>${po.po_dispatch_rest_qty}</td>
                        <td>${po.unit_price}</td>
                        <td>${po.total_price}</td>
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
            let ItemId = document.getElementById('po_item_id').value;
            $.ajax({
                url: '/get-sales-orders', // Adjust this URL to match your backend route
                type: 'POST',
                data: {
                    company_id: companyId,
                    ItemId: ItemId,
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
                            const row = `
                        <tr>
                            <td>
                                <input type="checkbox" class="so-checkbox" 
                                       data-id="${so.so_item_no}" 
                                       data-item-name="${so.so_number}" 
                                       data-quantity="${so.qty}" 
                                       data-unit-price="${so.unit_price}"
                                       onchange="singleSOCheckboxSelection(this)">
                            </td>
                            <td>${new Date(so.date).toLocaleDateString('en-GB')}</td>
                            <td>${so.so_number}</td>
                            <td>${so.name}</td>
                            <td>${so.so_item_no}</td>
                            <td>${so.qty}</td>
                            <td>${so.so_dispatch_rest_qty  }</td>
                            <td>${so.unit_price}</td>
                            <td>${so.total_price}</td>
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

        function singleCheckboxSelection(selectedCheckbox) {
            $('.po-checkbox').not(selectedCheckbox).prop('checked', false);
        }

        function singleSOCheckboxSelection(selectedCheckbox) {
            $('.so-checkbox').not(selectedCheckbox).prop('checked', false);
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
    </script>






@endsection
