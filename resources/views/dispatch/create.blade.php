@extends('layouts.main')
@section('title', 'Index - Manual Matching')
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
        @if ($message = Session::get('success'))
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
                            <form class="row g-3" method="post" action="{{ route('dispatch.store')}}">
                                @csrf

                                {{-- FROM Purchase orders --}}
                                <div class="row mb-3">
                                    <!-- Company Dropdown -->
                                    {{-- <div class="col-md-4">
                                        <label for="get_miller_id" class="form-label">From</label><span class="required-classes">*</span>
                                        <select class="form-select Select-Company" id="get_miller_id" name="company_id" onchange="fetchPoNumbers(this)" required>
                                            <option selected disabled>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}


                                    <div class="col-md-4">
                                        <label for="get_miller_id" class="form-label">From</label><span class="required-classes">*</span>
                                        <select class="form-select Select-Company" id="get_miller_id" name="po_company_id" onchange="fetchPoNumbers(this)" required>
                                            <option selected disabled>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                
                                    <!-- Purchase Order Number Dropdown -->
                                    <div class="col-md-4">
                                        <label for="po_number" class="form-label">Purchase Order Number</label><span class="required-classes">*</span>
                                        <select class="form-select" id="po_number" name="po_number" onchange="fetchPoItems(this)" required>
                                            <option selected disabled>Select Purchase Order</option>
                                            <!-- Options will be populated dynamically based on the selected company -->
                                        </select>
                                    </div>
                                
                                    <!-- PO Items Dropdown -->
                                    <div class="col-md-4">
                                        <label for="po_item" class="form-label">PO Items</label><span class="required-classes">*</span>
                                        <select class="form-select" id="po_item" name="po_item_id" onchange="addRowItem()" required>
                                            <option selected disabled>Select PO Item</option>
                                            <!-- Options will be populated dynamically based on the selected PO -->
                                        </select>
                                    </div>
                                </div>

                                {{-- To sales orders --}}
                                <div class="row mb-3">
                                    <!-- Company Dropdown (To) -->
                                    <div class="col-md-4">
                                        <label for="get_miller_id" class="form-label">To</label><span class="required-classes">*</span>
                                        <select class="form-select Select-Company" id="get_miller_id" name="so_company_id" onchange="fetchSalesOrders(this)" required>
                                            <option selected disabled>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                
                                    <!-- Sales Order Number Dropdown -->
                                    <div class="col-md-4">
                                        <label for="sales_order_number" class="form-label">Sales Order Number</label><span class="required-classes">*</span>
                                        <select class="form-select" id="sales_order_number" name="sales_order_number" onchange="fetchSoItems(this)" required>
                                            <option selected disabled>Select Sales Order</option>
                                            <!-- Options will be populated dynamically based on the selected company -->
                                          </select>
                                    </div>
                                
                                    <!-- SO Items Dropdown -->
                                    {{-- <div class="col-md-4">
                                        <label for="so_item" class="form-label">SO Items</label><span class="required-classes">*</span>
                                        <select class="form-select" id="so_item" name="so_item" onchange="addRowItem()" required>
                                            <option selected disabled>Select SO Item</option>
                                            <!-- Options will be populated dynamically based on the selected Sales Order -->
                                        </select>
                                    </div> --}}
                                </div>
                              
                              
                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">Dispatch Details</h4>
                                    <button type="button" id="addRowBtn" class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1" onclick="addRow()">Add Row</button>
                                </div>
                                
                                <table id="myTable" class="col-md-4 col-sm-4 col-xl-12 table">
                                    <thead>
                                        <tr>
                                            <th class="table_heading_long">Base Item Name<span class="required-classes">*</span></th>
                                            <th class="table_heading_long">Conv Item Name</th>
                                            <th class="table_heading_normal">Conv Rate</th>
                                            <th class="table_heading_normal">Quantity<span class="required-classes">*</span></th>
                                            <th class="table_heading_action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be dynamically added here -->
                                    </tbody>
                                </table>

                                <div class="col-md-4">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks here..."></textarea>
                                </div>
                                
                                <style>
                                    tbody,
                                    td,
                                    tfoot,
                                    th,
                                    thead,
                                    tr {
                                        border-color: inherit;
                                        border-style: none;
                                        border-width: 0;
                                    }
                                </style>
                                
                                <script>
                                    var lastItemId = 1; // Initialize a global counter for item IDs
                                
                                    function addRow() {
                                        var table = document.getElementById("myTable").getElementsByTagName('tbody')[0];
                                        var newRow = table.insertRow(table.rows.length);
                                
                                        var cell1 = newRow.insertCell(0);
                                        var cell2 = newRow.insertCell(1);
                                        var cell3 = newRow.insertCell(2);
                                        var cell4 = newRow.insertCell(3);
                                        var cell5 = newRow.insertCell(4);
                                
                                        cell1.innerHTML = `
                                        
                                            <input type="text" name="selected_buyer_name[]" id="buyer_name_id" onchange="fetchSoItems(this)" style="height: 34px; width: 220px;" class="form-control" required />

                                        `;
                                        $('#buyer_name_id' + lastItemId).select2();
                                
                                        cell2.innerHTML = `
                                                <select name="sub_cat_id[]" id="brand_name_id" onchange="fetchPOItemsRate(this)" style="height: 34px; width: 220px;" class="form-select select_brand_name">
                                                    <option value="" disabled selected>Select Item</option>
                                                    <!-- Populate this with your brand options -->
                                                </select>
                                            `;
                                            $('#brand_name_id' + lastItemId).select2();

                                
                                        cell3.innerHTML = `                                         
                                            <input type="text" name="conv_rate[]" id="conv_rate"  style="height: 34px; width: 220px;" class="form-control" required />
                                        `;
                                        $('.bag-name-' + lastItemId).select2();

                                
                                        cell4.innerHTML = ` 
                                            <input type="number" name="quantity[]" id="quantity_qty${lastItemId}" class="form-control" style="height: 34px" placeholder="Quantity" required>
                                        `;
                                
                                        cell5.innerHTML = `
                                            <button onclick="addRow()" class="btn btn-success"><i class="fas fa-plus-circle"></i></button>
                                        `;
                                        if (lastItemId > 1) {
                                            cell5.innerHTML += `
                                                <button class="btn btn-danger" onclick="deleteRow(this)"><i class="fas fa-minus-circle"></i></button>
                                            `;
                                        }
                                
                                        lastItemId++;
                                    }
                                
                                    function deleteRow(button) {
                                        var row = button.parentNode.parentNode;
                                        row.parentNode.removeChild(row);
                                    }
                                </script>
                                


                                <div class="text-end mt-5">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a class="btn btn-secondary" href="#">Back</a>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br><br><br>



    </main><!-- End #main -->

    <!-- Modal Structure -->
<div class="modal fade" id="getCompanyData" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">Company Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Other Field 1</th>
                            <th>Other Field 2</th>
                            <!-- Add more columns as needed -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


    <script>
        function fetchPoNumbers(companySelect) {
            const companyId = companySelect.value;
    
            $.ajax({
                url: '/get-purchase-orders',  // Update to the correct route
                method: 'POST',
                data: { company_id: companyId, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    let poOptions = '<option selected disabled>Select Purchase Order</option>';
                    response.purchase_orders.forEach(po => {
                        poOptions += `<option value="${po.id}">${po.document_number}</option>`;
                    });
                    $('#po_number').html(poOptions);
                }
            });
        }

        let selectedPoItems = [];

// Fetch Purchase Order Items and save selection
function fetchPoItems(element) {
    const poId = element.value;
    
    $.ajax({
        url: '{{ route("getPoItems") }}',
        type: 'POST',
        data: { po_id: poId, _token: '{{ csrf_token() }}' },
        success: function (response) {
            $('#po_item').empty().append('<option selected disabled>Select PO Item</option>');
            
            response.poItems.forEach(item => {
                $('#po_item').append(new Option(item.name, item.id));
            });

            // Capture selected items to filter SO items later
            $('#po_item').on('change', function () {
                selectedPoItems = $(this).val();
            });
        }
    });
}
    
        function fetchPoItems(poSelect) {
            const poId = poSelect.value;
    
            $.ajax({
                url: '/get-po-items',  // Update to the correct route
                method: 'POST',
                data: { po_id: poId, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    let itemOptions = '<option selected disabled>Select PO Item</option>';
                    response.po_items.forEach(item => {
                        itemOptions += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $('#po_item').html(itemOptions);
                }
            });
        }

        function fetchSalesOrders(selectElement) {
    const companyId = selectElement.value;

    $.ajax({
        url: '/get-sales-orders',  // Adjust the URL to match your route
        type: 'POST',
        data: {
            company_id: companyId,
            "_token": "{{ csrf_token() }}"  // CSRF token for security
        },
        success: function (response) {
            let salesOrderOptions = '<option selected disabled>Select Sales Order</option>';
            response.salesOrders.forEach(order => {
                salesOrderOptions += `<option value="${order.id}">${order.so_number}</option>`;
            });
            $('#sales_order_number').html(salesOrderOptions);
        }
    });
}

function fetchSoItems(selectElement) {
    const salesOrderId = selectElement.value;

    $.ajax({
        url: '/get-so-items',  // Adjust the URL to match your route
        type: 'POST',
        data: {
            sales_order_id: salesOrderId,
            "_token": "{{ csrf_token() }}"  // CSRF token for security
        },
        success: function (response) {
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
        url: '/get-item-details',  // Adjust the URL to match your route
        type: 'POST',
        data: {
            item_id: item_id,
            "_token": "{{ csrf_token() }}"  // CSRF token for security
        },
    
        success: function (response) {
            
          
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
    url: '/get-po-items-rate',  // Adjust the URL to match your route
    type: 'POST',
    data: {
        item_id: item_id,
        "_token": "{{ csrf_token() }}"  // CSRF token for security
    },

    success: function (response) {
        
      
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

        function get_bags(selectElement) {

            let item_id = selectElement.value;
            let row = selectElement.parentNode.parentNode; // Get the parent row of the select element
            let BrandSelect = row.querySelector(
                '.bag-name'); // Find the subcategory select element in the same row

            $.ajax({
                url: "{{ url('get_bags_list') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res);

                    // Access the 'bag' property of the response
                    let data = res.bag;
                    if (data) {
                        let htmldata = '<option value="" disabled selected>Select Bags</option>';
                        for (let item of data) {
                            htmldata += `
                            <option value="${item.id}">${item.bag_name}</option>
                            `;
                        }
                        BrandSelect.innerHTML =
                            htmldata; // Populate the subcategory select element in the same row with dynamic options
                    }
                }
            });
        }



        function get_weight(selectElement) {
            let item_id = selectElement.value;
            let row = selectElement.parentNode.parentNode; // Get the parent row of the select element
            let WeightSelect = row.querySelector(
                '.bag-weight'); // Find the subcategory select element in the same row

            $.ajax({
                url: "{{ url('get_weight_list') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // Access the 'bag' property of the response
                    let weight = res.bag_weight.bag_size;
                    // console.log(weight);

                    WeightSelect.value =
                        weight;



                }
            });
        }
    </script>






@endsection
