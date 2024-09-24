@extends('layouts.main')
@section('title','Outward - Saraswati Globals')
@section('content')
    <style>
        .custom-border-bottom {
            border-bottom: 1px dashed black;
        }
    </style>
    <main id="main" class="main">
        <div class="dashboard-header pagetitle">
            <h1>Add Outward</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Outward</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('outward_sales.store') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>

                                <!-- Horizontal Form -->

                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Buyer Name :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $company->company_name }} </label>
                                    </div>
                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Outward Number :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $outward_id }} </label><br>
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Sales Order Number
                                                :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $sales_order->so_number }} </label>
                                        <input type="hidden" name="so_id" value="{{ $sales_order->id }}">
                                        <input type="hidden" name="outward_id" value="{{ $outward_id }}">

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Address :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $company->address }} </label>
                                    </div>
                                    {{-- <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Outward Type :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $outward_type }} </label>
                                        <input type="hidden" value="{{ $outward_type }}">
                                    </div> --}}
                                </div>

                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Warehouse :</strong>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="hidden" class="form-control" value="{{ $warehouse->id }}"
                                            name="warehouse_id" id="inputPassword" required>

                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $warehouse->warehouse_title }} </label>
                                    </div>
                                </div>

                                <?php
                                $currentDate = date('Y-m-d');
                                ?>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong> Outward
                                            Date</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" value="{{$currentDate}}" name="date" id="inputPassword"
                                            required>
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
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Loading
                                            Supervisor</strong><span class="required-classes">*</span></label>
                                    <!-- Main Select Element -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="supervisor" id=""
                                            required>
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

                                        </div>

                                        <div class="btn-list">
                                            {{-- <input type="text" id="searchInput" placeholder="Search by item name"> --}}
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>S.NO <span class="required-classes">*</span>
                                                        </th>
                                                        <th>Item Category <span class="required-classes">*</span>
                                                        </th>
                                                        <th>Item SubCategory<span class="required-classes">*</span>
                                                        </th>

                                                        <th>Length(ft)<span class="required-classes">*</span>
                                                        </th>
                                                        <th>UOM Type <span class="required-classes">*</span></th>
                                                        <th>PCs <span class="required-classes">*</span></th>
                                                        <th>Weight(kg) <span class="required-classes">*</span></th>
                                                        <th>Current Qty <span class="required-classes">*</span></th>
                                                        <th>Action<span class="required-classes">*</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($so_items as $so_item)
                                                        @if ($so_item->rest_pcs == 0)
                                                            @continue
                                                        @endif

                                               
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $so_item->name }}</td>
                                                            <input type="text" class="form-control"
                                                                style="display: none" name="category_id[]"
                                                                name="category_id"
                                                                id="item_category_{{ $so_item->so_item_id }}"
                                                                value="{{ $so_item->item_category }}" disabled>
                                                            <td>{{ $so_item->sub_category }}</td>
                                                            <input type="text" style="display: none"
                                                                name="subcategory_id[]" name="subcategory_id"
                                                                id="item_subcategory_{{ $so_item->so_item_id }}"
                                                                value="{{ $so_item->item_subcategory }}" disabled>
                                                            <td>{{ $so_item->length }}</td>
                                                            <input type="text" style="display: none" name="length[]"
                                                                name="length"
                                                                id="item_length_{{ $so_item->so_item_id }}"
                                                                value="{{ $so_item->length }}" disabled>
                                                            <td>{{ $so_item->uom_type }}</td>
                                                            <input type="text" style="display: none" name="uom[]"
                                                                id="item_uomtype_{{ $so_item->so_item_id }}"
                                                                value="{{ $so_item->uom_type }}" disabled>
                                                            <input type="text" style="display: none" name="qty[]"
                                                                id="item_qty_{{ $so_item->so_item_id }}"
                                                                value="{{ $so_item->qty }}" disabled>

                                                            @if ($so_item->rest_pcs < $so_item->stock_piece)
                                                                <td> <input type="number" class="form-control"
                                                                        id="item_pcs_val_{{ $so_item->so_item_id }}"
                                                                        value="{{ $so_item->rest_pcs }}"
                                                                        min="1"
                                                                        disabled
                                                                        oninput="calculateRow({{ $so_item->so_item_id }})">
                                                                </td>
                                                                <input type="number" style="display: none"
                                                                    name="pcs[]"
                                                                    id="item_pcs_{{ $so_item->so_item_id }}"
                                                                    min="1" 
                                                                    value="{{ $so_item->rest_pcs }}" disabled>
                                                            @else
                                                                <td> <input type="number" class="form-control"
                                                                        id="item_pcs_val_{{ $so_item->so_item_id }}"
                                                                        value="{{ $so_item->stock_piece ?? 0 }}"
                                                                        
                                                                        min="1" disabled
                                                                        oninput="calculateRow({{ $so_item->so_item_id }})">
                                                                </td>
                                                                <input type="number" style="display: none"
                                                                    name="pcs[]"
                                                                    id="item_pcs_{{ $so_item->so_item_id }}"
                                                                    
                                                                    value="{{ $so_item->stock_piece ?? 0 }}"
                                                                    min="1" disabled>
                                                            @endif

                                                            <td><input type="text" class="form-control"
                                                                    id="item_weight_main_{{ $so_item->so_item_id }}"
                                                                    name="weight[]" value="0"
                                                                    oninput = "calculateTotalWeight()" disabled></td>

                                                            <input type="text" style="display: none;"
                                                                id="item_weight_{{ $so_item->so_item_id }}"
                                                                value="0" disabled>

                                                            <input type="text" style="display: none;"
                                                                id="item_weight_hidden_{{ $so_item->so_item_id }}"
                                                                value="{{ $so_item->weight }}" disabled>

                                                            <td>{{ $so_item->stock_piece ?? 0 }}</td>
                                                            <input type="text" style="display: none"
                                                                class="form-control"
                                                                id="current_{{ $so_item->so_item_id }}"
                                                                name="current_qty[]"
                                                                value="{{ $so_item->stock_piece ?? 0 }}" disabled
                                                                readonly>

                                                            <td><input type="checkbox"
                                                                    id="item_checkbox_{{ $so_item->so_item_id }}"
                                                                    value="{{ $so_item->so_item_id }}"
                                                                    class="item-checkbox"></td>
                                                            <input type="text" style="display: none"
                                                                name="so_item_id[]"
                                                                id="item_checkbox_val_{{ $so_item->so_item_id }}"
                                                                value="{{ $so_item->so_item_id }}" disabled>
                                                        </tr>

                                                
                                                    @endforeach
                                                    @foreach ($extra_items as $so_item)
                                                        @if ($so_item->rest_pcs == 0)
                                                            @continue
                                                        @endif
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $so_item->name }}</td>
                                                            <input type="text" class="form-control"
                                                                style="display: none" name="category_id[]"
                                                                name="category_id"
                                                                id="item_category_{{ $so_item->so_item_main_id }}"
                                                                value="{{ $so_item->item_category }}" disabled>
                                                            <td>{{ $so_item->sub_category }}</td>
                                                            <input type="text" style="display: none"
                                                                name="subcategory_id[]" name="subcategory_id"
                                                                id="item_subcategory_{{ $so_item->so_item_main_id }}"
                                                                value="{{ $so_item->item_subcategory }}" disabled>
                                                            <td>{{ $so_item->length }}</td>
                                                            <input type="text" style="display: none" name="length[]"
                                                                name="length"
                                                                id="item_length_{{ $so_item->so_item_main_id }}"
                                                                value="{{ $so_item->length }}" disabled>
                                                            <td>{{ $so_item->uom_type }}</td>
                                                            <input type="text" style="display: none" name="uom[]"
                                                                id="item_uomtype_{{ $so_item->so_item_main_id }}"
                                                                value="{{ $so_item->uom_type }}" disabled>
                                                            <input type="text" style="display: none" name="qty[]"
                                                                id="item_qty_{{ $so_item->so_item_main_id }}"
                                                                value="{{ $so_item->qty }}" disabled>
                                                            <td><input type="number" class="form-control"
                                                                    id="item_pcs_val_{{ $so_item->so_item_main_id }}"
                                                                    value="0" max="0" min="1" disabled
                                                                    oninput="calculateRow({{ $so_item->so_item_main_id }})">
                                                            </td>
                                                            <input type="number" style="display: none" name="pcs[]"
                                                                id="item_pcs_{{ $so_item->so_item_main_id }}"
                                                                min="1" max="0" value="0" disabled>
                                                            <td><input type="text" class="form-control"
                                                                    id="item_weight_main_{{ $so_item->so_item_main_id }}"
                                                                    name="weight[]" value="0" disabled></td>

                                                            <input type="text" style="display: none;"
                                                                id="item_weight_{{ $so_item->so_item_main_id }}"
                                                                value="0" disabled>

                                                            <input type="text" style="display: none;"
                                                                id="item_weight_hidden_{{ $so_item->so_item_main_id }}"
                                                                value="{{ $so_item->weight }}" disabled>

                                                            <td>{{ $so_item->stock_piece ?? 0 }}</td>
                                                            <input type="text" style="display: none"
                                                                class="form-control"
                                                                id="current_{{ $so_item->so_item_main_id }}"
                                                                name="current_qty[]"
                                                                value="{{ $so_item->stock_piece ?? 0 }}" disabled
                                                                readonly>

                                                            <td><input type="checkbox"
                                                                    id="item_checkbox_{{ $so_item->so_item_main_id }}"
                                                                    value="{{ $so_item->so_item_main_id }}"
                                                                    class="item-checkbox"></td>
                                                            <input type="text" style="display: none"
                                                                name="so_item_id[]"
                                                                id="item_checkbox_val_{{ $so_item->so_item_main_id }}"
                                                                value="{{ $so_item->so_item_main_id }}" disabled>
                                                        </tr>
                                                    @endforeach

                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <th>Total</th>
                                                            
                                                            <td><input type="number" class="form-control" name="total_pcs"
                                                                value="0" id="overall_total_pcs" placeholder="Total Weight" readonly></td>
                                                                <td><input type="number" class="form-control" name="total_weight"
                                                                    value="0" id="overall_total_weight" placeholder="Total PCs" readonly></td>
                                                        </tr>
                                                    </tfoot>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-lg-6"></div>
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-4 ">
                                                <div class="row">

{{-- 
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="col-form-label"><strong>
                                                                Total Weight</strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="total_weight"
                                                            value="0" id="overall_total_weight" readonly>
                                                    </div> --}}

                                                
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="col-form-label"><strong>
                                                                Loading Cutting</strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control"
                                                            oninput = "calculateTotal()" name="loading_charges"
                                                            value="0" min="0" id="loading_cutting">
                                                    </div>
                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="  col-form-label"><strong>Additional Charges</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" class="form-control"
                                                            name="additional_charges" id="additional_charges"
                                                            oninput = "calculateTotal()" min="0" value="0">
                                                    </div>

                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="  col-form-label"><strong>Freight</strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="number" oninput = "calculateTotal()"
                                                            class="form-control" name="freight" min="0" id="freight"
                                                            value="0">
                                                    </div>

                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3"
                                                            class="  col-form-label"><strong>Total</strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="total_charges"
                                                            id="total" value="0" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <input type="hidden" name="company_id"
                                            class="form-control"value="{{ $company->id }}" required>
                                        <input type="hidden" name="type"
                                            class="form-control"value="{{ $outward_type }}" required>
                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a class="btn btn-secondary" href="{{ route('outward.index') }}">Back</a>
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
            let checkboxes = document.querySelectorAll('.item-checkbox');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    let itemId = this.value;
                    let categoryInput = document.getElementById('item_category_' + itemId);
                    let subcategoryInput = document.getElementById('item_subcategory_' + itemId);
                    let lengthInput = document.getElementById('item_length_' + itemId);
                    let uomtypeInput = document.getElementById('item_uomtype_' + itemId);
                    let pcsvalInput = document.getElementById('item_pcs_val_' + itemId);
                    let pcsInput = document.getElementById('item_pcs_' + itemId);
                    let qtyInput = document.getElementById('item_qty_' + itemId);
                    let weightInput = document.getElementById('item_weight_' + itemId);
                    let weightInputhidden = document.getElementById('item_weight_hidden_' + itemId);
                    let weightInputmain = document.getElementById('item_weight_main_' + itemId);
                    let checkbox_valInput = document.getElementById('item_checkbox_val_' + itemId);
                    let currentvalInput = document.getElementById('current_' + itemId);

                    if (this.checked) {
                        categoryInput.removeAttribute('disabled');
                        subcategoryInput.removeAttribute('disabled');
                        lengthInput.removeAttribute('disabled');
                        uomtypeInput.removeAttribute('disabled');
                        pcsInput.removeAttribute('disabled');
                        pcsvalInput.removeAttribute('disabled');
                        qtyInput.removeAttribute('disabled');
                        weightInput.removeAttribute('disabled');
                        checkbox_valInput.removeAttribute('disabled');
                        pcsvalInput.removeAttribute('disabled');
                        weightInputhidden.removeAttribute('disabled');
                        weightInputmain.removeAttribute('disabled');
                        currentvalInput.removeAttribute('disabled');
                        calculateRow(itemId);
                    } else {
                        categoryInput.setAttribute('disabled', true);
                        subcategoryInput.setAttribute('disabled', true);
                        lengthInput.setAttribute('disabled', true);
                        uomtypeInput.setAttribute('disabled', true);
                        pcsInput.setAttribute('disabled', true);
                        pcsvalInput.setAttribute('disabled', true);
                        qtyInput.setAttribute('disabled', true);
                        weightInput.setAttribute('disabled', true);
                        weightInput.setAttribute('disabled', true);
                        checkbox_valInput.setAttribute('disabled', true);
                        pcsvalInput.setAttribute('disabled', true);
                        weightInputhidden.setAttribute('disabled', true);
                        weightInputmain.setAttribute('disabled', true);
                        currentvalInput.setAttribute('disabled', true);
                        weightInputmain.value = 0; // Reset weight for unchecked item
                        calculateRow(itemId);
                    }
                    // Recalculate total weight
                    calculateTotalWeight();
                });
            });
        });

        function calculateRow(itemId) {
            const pcsval = document.getElementById('item_pcs_val_' + itemId).value;
            const weightval = document.getElementById('item_weight_' + itemId);
            const weightval2 = document.getElementById('item_weight_hidden_' + itemId).value;
            const length = document.getElementById('item_length_' + itemId).value;
            const pcsval_main = document.getElementById('item_pcs_' + itemId);
            const weight_main = document.getElementById('item_weight_main_' + itemId);
            const total_weight = length * weightval2;
            const total_weight_qty = (total_weight * pcsval).toFixed(3);

            weight_main.value = total_weight_qty;
            weightval.value = total_weight_qty;
            pcsval_main.value = pcsval;

            calculateTotalWeight();
        }

        function calculateTotalWeight() {
            let totalWeightInput = document.getElementById('overall_total_weight');
            let totalPCsInput = document.getElementById('overall_total_pcs');
            let totalWeight = 0;
            let totalPCs = 0;

            document.querySelectorAll('.item-checkbox').forEach(function(checkbox) {
                let itemId = checkbox.value;
                let weightInput = document.getElementById('item_weight_main_' + itemId);
                let pcsInput = document.getElementById('item_pcs_val_' + itemId);

                if (checkbox.checked) {
                    let itemWeight = parseFloat(weightInput.value) || 0;
                    let itempcs = parseFloat(pcsInput.value) || 0;
                    totalWeight += itemWeight;
                    totalPCs += itempcs;

                }
            });

            // Set the updated total weight back to the input field
            totalWeightInput.value = totalWeight.toFixed(3);
            totalPCsInput.value = totalPCs.toFixed(0);
        }

        function calculateTotal() {
            let total_charges = document.getElementById('total');
            const loading_cutting = parseFloat(document.getElementById('loading_cutting').value) || 0;
            const freight = parseFloat(document.getElementById('freight').value) || 0;
            const additional_charges = parseFloat(document.getElementById('additional_charges').value) || 0;
            var total = loading_cutting + freight + additional_charges;
            total_charges.value = total;
        }
    </script>
@endsection
