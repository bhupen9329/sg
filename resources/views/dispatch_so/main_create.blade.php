@extends('layouts.main')
@section('title', 'Create - Dispatch')
@section('content')
<style>
    .custom-checkbox {
        width: 15px; /* Adjust width */
        height: 15px; /* Adjust height */
        transform: scale(1.5); /* Adjust scale for finer control */
    }
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
                            <form id="dispatchForm" class="row g-3" method="post" action="{{ route('dispatch.store') }}">
                                @csrf
                                <div class="row mb-3">

                                    <?php
                                    $currentDate = date('Y-m-d');
                                    ?>

                                    <div class="col-md-6 mt-2"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="to_company_id" class="form-label">Dispatch Date<span
                                                class="required-classes">*</span></label>
                                        <input type="date" class="form-control" name="date" id="raised_date_input"
                                            value="{{ $currentDate }}" required>
                                    </div>

                                    <div class="col-md-6 mt-2"> <!-- Change this to col-md-6 for equal width -->
                                        <label for="to_company_id" class="form-label">Vehicle Number</label>
                                        <input type="text" class="form-control" name="vehicle_number">
                                    </div>



                                    <div class="col-md-6 mt-4">
                                        <label for="get_miller_id" class="form-label">From</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company custom-select" id="get_miller_id"
                                            name="po_company_id">
                                            <option value="{{ $dispatch_po_company->id }}" selected>{{ $dispatch_po_company->company_name }}</option>
                                            
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-4">
                                        <label for="to_company_id" class="form-label">To</label><span
                                            class="required-classes">*</span>
                                        <select class="form-select Select-Company custom-select" id="to_company_id"
                                            name="so_company_id">
                                            <option value="{{ $dispatch_so_company->id }}" selected>{{ $dispatch_so_company->company_name }}</option>
                                           
                                        </select>
                                    </div>
                                </div>

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
                                            <th>Dispatch Quantity (Q)</th>
                                            <th>PO Unit Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($po_items as $po_item)
                                       <tr>
                                        <td>{{date('d-M-Y', strtotime($po_item->date))}} </td>
                                        <td>{{ $po_item->document_number }}</td>
                                        <td>{{ $po_item->name }}</td>
                                        <td>{{ $po_item->po_item_no }}</td>
                                        <td>{{ $po_item->qty }}</td>
                                        <td>{{ $po_item->po_dispatch_rest_qty }}</td>
                                        <td>{{ $po_item->dispatch_po_qty }}</td>

                                        <td>{{ $po_item->unit_price }}</td>
                                       </tr>
                                       @endforeach
                                    </tbody>
                                </table>



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
                                            <th>Dispatch Quantity (Q)</th>
                                            <th>SO Unit Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be dynamically added here -->

                                        @foreach($so_items as $so_item)
                                        <tr>
                                         <td>{{date('d-M-Y', strtotime($so_item->date))}} </td>
                                         <td>{{ $so_item->so_number }}</td>
                                         <td>{{ $so_item->name }}</td>
                                         <td>{{ $so_item->so_item_no }}</td>
                                         <td>{{ $so_item->qty }}</td>
                                         <td>{{ $so_item->so_dispatch_rest_qty }}</td>
                                        <td>{{ $so_item->dispatch_so_qty }}</td>
                                         <td>{{ $so_item->unit_price }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                                
                                {{-- ............................................................. Dispatch Details................................................................  --}}
                                <div class="row mt-5">
                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">Dispatch Details</h4>
                                    {{-- <button type="button" id="addRowBtn" class="btn btn-success col-md-12 col-sm-12 col-xl-1 mb-1" onclick="addRow()">Add Row</button> --}}
                                </div>

                                <table id="poTable" class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="11" class="text-center bg-light">SO Items</th>
                                        </tr>
                                        <tr>
                                            <th>Base Item</th>
                                            <th>SO Item No.</th>
                                            <th>Conv Item</th>
                                            <th>Conv Price</th>
                                            <th>Loading + Insurance</th>
                                            <th>SO Unit Price</th>
                                            <th>Gross SO Price</th>
                                            <th>SO Qty</th>
                                            <th>SORest Qty</th>
                                            <th>Dispatch Qty</th>
                                            <th>Enter Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($so_items as $so_item)
                                        @php
                                         $conv_item = DB::table('subcategories')->where('category_id', $so_item->item_category)->get();
                                        @endphp
                                            <!-- SO Item Row -->
                                            <tr class="bg-primary text-white">
                                                <!-- Base Item Name as Input -->
                                                <td>
                                                    <input type="text" name="base_item_name[]" value="{{ $so_item->name }}" class="form-control" readonly />
                                                </td>
                                                
                                                <!-- SO Item No. as Input -->
                                                <td>
                                                    <input type="text" name="so_item_no[]" value="{{ $so_item->so_item_no }}" class="form-control" readonly />
                                                </td>
                                                
                                                <!-- Conversion Rate Select -->
                                                <td>
                                                    <select name="sub_cat_id[]" onchange="get_conv_price(this)" class="form-select">
                                                        <option value="">Select</option>
                                                        @foreach($conv_item as $data)
                                                        <option value="{{ $data->id }}">{{ $data->sub_category }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                
                                                <!-- Conversion Rate Input -->
                                                <td>
                                                    <input type="number" name="conv_rate[]" class="form-control" value="0" oninput="calculateTotal(this)" readonly />
                                                </td>
                                                
                                                <!-- Loading Insurance Input -->
                                                <td>
                                                    <input type="number" name="dispatch_freight_insuance[]" class="form-control" value="0" oninput="calculateTotal(this)" />
                                                </td>
                                                
                                                <!-- Unit Price Input -->
                                                <td>
                                                    <input type="text" name="so_unit_price[]" value="{{ $so_item->unit_price }}" class="form-control" readonly />
                                                </td>
                                                
                                                <!-- Gross SO Price Input -->
                                                <td>
                                                    <input type="number" name="gross_so_price[]" class="form-control" value="{{ $so_item->unit_price }}" oninput="calculateTotal(this)" readonly/>
                                                </td>
                                                
                                                <!-- Quantity Input -->
                                                <td>
                                                    <input type="number" name="so_quantity[]" value="{{ $so_item->qty }}" class="form-control" readonly />
                                                </td>
                                                
                                                <!-- SO Dispatch Rest Qty Input -->
                                                <td>
                                                    <input type="number" name="so_dispatch_rest_qty[]" value="{{ $so_item->so_dispatch_rest_qty }}" class="form-control" readonly />
                                                </td>

                                                    <!-- SO Dispatch Qty Input -->
                                                    <td>
                                                        <input type="number" name="dispatch_so_qty[]" value="{{ $so_item->dispatch_so_qty }}" class="form-control" readonly />
                                                    </td>
                                                
                                                <!-- Final Gross SO Price Input -->
                                                <td>
                                                    <input type="number" name="quantity[]" id="so_quantity_{{ $so_item->so_item_id }}" value="{{ $so_item->dispatch_so_qty }}" class="form-control" value="0" oninput="calculateTotal(this)" />
                                                </td>
                                            </tr>
                                            
                                
                                            <!-- PO Items Header -->
                                            <tr>
                                                <th colspan="11" class="text-center bg-light">PO Items</th>
                                            </tr>
                                            <tr>
                                                <th>Action</th>
                                                <th>Base Item</th>
                                                <th>PO Item No.</th>
                                                <th>PO Qty</th>
                                                <th>PORest Qty</th>
                                                <th>PO Unit Price</th>
                                                <th>Gross PO Price</th>
                                                <th>Dispatch Qty</th>
                                                <th>Enter Qty</th>
                                                <th colspan="4"></th>
                                            </tr>
                                            
                                            <!-- Related PO Items for this SO Item -->
                                            @foreach($po_items as $po_item)
                                            <tr>
                                                <!-- Action Column with Checkbox -->
                                                <td>
                                                    <input type="checkbox" name="po_item_select[]" value="{{ $po_item->po_item_id }}" 
                                                        onchange="toggleCheckbox('{{  $po_item->po_item_id }}', '{{ $so_item->so_item_id }}')" 
                                                        id="item_checkbox_{{  $po_item->po_item_id }}_{{ $so_item->so_item_id }}" class="form-check-input custom-checkbox custom-checkbox_{{ $so_item->so_item_id }}" />
                                                </td>
                                    
                                                <td>
                                                    <input type="text" name="base_item_name[]" value="{{ $po_item->name }}" class="form-control" readonly />
                                                </td>
                                    
                                                <td>
                                                    <input type="text" name="po_item_no[]" value="{{ $po_item->po_item_no }}" class="form-control" readonly />
                                                </td>
                                    
                                                <td>
                                                    <input type="number" name="po_qty[]" id="po_qty_{{  $po_item->po_item_id }}_{{ $so_item->so_item_id }}" value="{{ $po_item->qty }}" class="form-control" readonly/>
                                                </td>
                                    
                                                <td>
                                                    <input type="number" name="po_rest_qty[]" class="form-control" value="{{ $po_item->po_dispatch_rest_qty }}" oninput="calculateTotal(this)" readonly/>
                                                </td>
                                    
                                                <td>
                                                    <input type="number" name="po_unit_price[]" value="{{ $po_item->unit_price }}"  id="po_unit_price_{{  $po_item->po_item_id }}_{{ $so_item->so_item_id }}" class="form-control" readonly />
                                                </td>
                                    
                                                <td>
                                                    <input type="number" name="gross_po_price[]" class="form-control"  id="gross_po_price_{{  $po_item->po_item_id }}_{{ $so_item->so_item_id }}" value="{{ $po_item->unit_price }}" oninput="calculateTotal(this)" readonly disabled/>
                                                </td>

                                                     <!-- SO Dispatch Qty Input -->
                                                     <td>
                                                        <input type="number" name="dispatch_po_qty[]"  id="po_rest_qty_{{  $po_item->po_item_id }}_{{ $so_item->so_item_id }}" value="{{ $po_item->dispatch_po_qty }}" class="form-control dispatch_po_quantity" readonly />
                                                    </td>
                                    
                                                <td>
                                                    <input type="number" name="qty[]"  id="qty_{{  $po_item->po_item_id }}_{{ $so_item->so_item_id }}" value="{{ $so_item->dispatch_po_qty }}" class="form-control po_quantity po_quantity_{{ $po_item->po_item_id }}" value="0" onchange="changeOtherPO(this)" disabled/>
                                                </td>
                                    
                                                <td colspan="4"></td>
                                            </tr>
                                            @endforeach
                                            
                                            <!-- Separator Row -->
                                    
                                            <tr class="group">
                                                <td colspan="11" style="text-align: center; border-top: 2px solid #8B0000;"></td>
                                            </tr>
                                            <tr>
                                                <th colspan="11" class="text-center bg-light">SO Items</th>
                                            </tr>

                                            <tr>
                                                <th>Base Item</th>
                                                <th>SO Item No.</th>
                                                <th>Conv Item</th>
                                                <th>Conv Price</th>
                                                <th>Loading + Insurance</th>
                                                <th>SO Unit Price</th>
                                                <th>Gross SO Price</th>
                                                <th>SO Qty</th>
                                                <th>SORest Qty</th>
                                                <th>Dispatch Qty</th>
                                                <th>Enter Qty</th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                
                                <input type="hidden" id="po_item_id">
                                <input type="hidden" id="so_item_no" name="">
                                <input type="hidden" id="po_item_no" name="">

                                <div class="col-md-4">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks here..."></textarea>
                                </div>

                                <div class="text-end mt-5">
                                    <button type="submit" onclick="check_" class="btn btn-primary">Submit</button>
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
        // ............................................................................. fetch conv price.................................................................. 
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
                    // const convRateFieldShow = $(selectElement).closest('tr').find(
                    //     'input[name="conv_rate_show[]"]');

                    if (response && response.item_price) {
                        // Set the conversion rate from the response
                        convRateField.val(response.item_price);
                        // convRateFieldShow.val(response.item_price);

                    } else {
                        convRateField.val(0);
                        // convRateFieldShow.val(0);
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
        // ........................................................................................................................................................................ 


        // .............................................................................calculateTotal.................................................................. 

        function calculateTotal(element) {
            const row = element.closest('tr');

            const quantityInput = row.querySelector('input[name="quantity[]"]');
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;

            const convRate = parseFloat(row.querySelector('input[name="conv_rate[]"]').value) || 0;
            const so_rest_quantity = parseFloat(row.querySelector('input[name="so_dispatch_rest_qty[]"]').value) || 0;
            const sounitPrice = parseFloat(row.querySelector('input[name="so_unit_price[]"]').value) || 0;
            const freight_insurance = parseFloat(row.querySelector('input[name="dispatch_freight_insuance[]"]').value) || 0;
            const gross_so_price = parseFloat(row.querySelector('input[name="gross_so_price[]"]').value) || 0;

            const totalSoAmountGross = (sounitPrice + freight_insurance + convRate);
            row.querySelector('input[name="gross_so_price[]"]').value = totalSoAmountGross.toFixed(2);

            if (quantity > so_rest_quantity) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Quantity Exceeded',
                    text: 'The entered quantity exceeds the available SO quantity.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reset the quantity to 0 after SweetAlert
                    quantityInput.value = 0;
                });
                return; // Exit the function if the condition is met
            }
           
        }

        // ........................................................................................................................................................................ 


                // .............................................................................Pochange.................................................................. 

                function changeOtherPO(element) {
            const row = element.closest('tr');

            const quantityInput = row.querySelector('input[name="quantity[]"]');
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;


            const convRate = parseFloat(row.querySelector('input[name="conv_rate[]"]').value) || 0;
            const so_rest_quantity = parseFloat(row.querySelector('input[name="so_dispatch_rest_qty[]"]').value) || 0;
            const sounitPrice = parseFloat(row.querySelector('input[name="so_unit_price[]"]').value) || 0;
            const freight_insurance = parseFloat(row.querySelector('input[name="dispatch_freight_insuance[]"]').value) || 0;
            const gross_so_price = parseFloat(row.querySelector('input[name="gross_so_price[]"]').value) || 0;

            const totalSoAmountGross = (sounitPrice + freight_insurance + convRate);
            row.querySelector('input[name="gross_so_price[]"]').value = totalSoAmountGross.toFixed(2);

            if (quantity > so_rest_quantity) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Quantity Exceeded',
                    text: 'The entered quantity exceeds the available SO quantity.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reset the quantity to 0 after SweetAlert
                    quantityInput.value = 0;
                });
                return; // Exit the function if the condition is met
            }
           
        }

        // ........................................................................................................................................................................ 

        // ..................................................................................toggleCheckbox........................................................................


        function toggleCheckbox(poItemId, soItemId) {
    // Get the checkbox, quantity input, and gross price input elements
    const checkbox = document.getElementById('item_checkbox_' + poItemId + '_' + soItemId);
    const qtyInput = document.getElementById('qty_' + poItemId + '_' + soItemId);
    const grossPriceInput = document.getElementById('gross_po_price_' + poItemId + '_' + soItemId);

    // Get SO quantity, PO Rest quantity, and other related values
    const poRestQty = parseFloat(document.getElementById('po_rest_qty_' + poItemId + '_' + soItemId).value) || 0;
    const soQuantity = parseFloat(document.getElementById('so_quantity_' + soItemId).value) || 0;

    const allCheckboxes = document.querySelectorAll('.custom-checkbox_' + soItemId);
  
    let totalQuantity = 0;

    allCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            // Find the corresponding quantity input field in the same row as the checkbox
            const row = checkbox.closest('tr');
            const qtyInput = row.querySelector('.po_quantity');
            if (qtyInput) {
                totalQuantity += parseFloat(qtyInput.value) || 0;
            }
        } else {
            const row = checkbox.closest('tr');
            const qtyInput = row.querySelector('.po_quantity');
            if (qtyInput) {
                totalQuantity -= parseFloat(qtyInput.value) || 0;
            }
        }
    });

    const allCheckboxesPO = document.querySelectorAll('.custom-checkbox');

    let totalQuantityParticularPO = 0;
    allCheckboxesPO.forEach(checkbox => {
        if (checkbox.checked) {
            // Find the corresponding quantity input field in the same row as the checkbox
            const row = checkbox.closest('tr');
            const qtyInput = row.querySelector('.po_quantity_' + poItemId);
            if (qtyInput) {
                totalQuantityParticularPO += parseFloat(qtyInput.value) || 0;
            }
        } else {
            const row = checkbox.closest('tr');
            const qtyInput = row.querySelector('.po_quantity_' + poItemId);
            if (qtyInput) {
                totalQuantity -= parseFloat(qtyInput.value) || 0;
            }
        }
    });


    // Calculate the PO quantity based on SO quantity and PO Rest quantity
    let poCalculateQuantity = 0;

    if (soQuantity > poRestQty) {
        if (totalQuantity != 0) {
            poCalculateQuantity = soQuantity - (poRestQty + totalQuantity);
        } else {
            poCalculateQuantity = poRestQty;
        }
    } else {
        poCalculateQuantity = (soQuantity - totalQuantity);
    }


    // Limit the value to 3 digits for PO quantity
    poCalculateQuantity = Math.abs(poCalculateQuantity);

    
    if(soQuantity > totalQuantityParticularPO){
        if (qtyInput) {
        qtyInput.value = (poCalculateQuantity - totalQuantityParticularPO);
    }
    }
    else{
        if (qtyInput) {
        qtyInput.value =  (poRestQty - totalQuantityParticularPO);
    }
    }



    // Ensure qtyInput exists before setting the value
  

    // Enable or disable the inputs based on the checkbox status
    if (checkbox.checked) {
        if (qtyInput) qtyInput.disabled = false;
        if (grossPriceInput) grossPriceInput.disabled = false;
    } else {
        if (qtyInput) {
            qtyInput.disabled = true;
            qtyInput.value = 0;  // Reset quantity when unchecked
        }
        if (grossPriceInput) grossPriceInput.disabled = true;
    }
}


        // ........................................................................................................................................................................ 


    </script>
@endsection
