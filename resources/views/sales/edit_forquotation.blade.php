@extends('layouts.main')
@section('title','Sales Order - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Add Sales Order for Quotation</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Sales Order for Quotation</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('sales.update', $sales_orders->so_id) }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>
                                <input type="hidden" id="overall_total_weight" readonly name="total_weight">

                                <!-- Horizontal Form -->

                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Bill
                                            To : - </strong></label>
                                    <div class="col-sm-4 mt-1">
                                        <p>{{ $company->company_name }}</p>
                                        <input type="hidden" class="form-control" name="company_name" id="inputText"
                                            value="{{ $company->company_name }}">
                                        <input type="hidden" value="{{ $company->id }}" name="company_id">
                                        <input type="hidden" value="{{ $sales_orders->so_type }}" name="so_type">

                                    </div>

                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Quotation Number :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $quotation->document_number }}</label>
                                        <input type="hidden" name="qt_id" value="{{ $quotation->id }}">
                                        {{-- <input type="hidden" name="total_weight" value="{{ $quotation->total_weight }}"> --}}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Address : -
                                        </strong></label>
                                    <div class="col-sm-4">
                                        <p>{{ $company->address }}</p>
                                        <textarea class="form-control" name="address" placeholder="Address" id="floatingTextarea" style="height: 100px;"
                                            required readonly hidden>{{ $company->address }}</textarea>
                                    </div>

                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Document Number :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $sales_orders->so_number }}</label>
                                        <input type="hidden" name="document_number"
                                            value="{{ $sales_orders->so_number }}">

                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Date</strong><span
                                            class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" value="{{ $sales_orders->date }}"
                                            name="date" id="inputPassword" required>
                                    </div>
                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-end"><strong>SO Type
                                                :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">{{ $sales_orders->so_type }}
                                        </label>
                                    </div>
                                </div><br>

                                <div class="row mb-8">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Payment
                                            Mandatory</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        @if ($sales_orders->payment_mandatory == 'yes')
                                            <input class="form-check-input" type="radio" name="payment_mandatory"
                                                id="gridRadios1" value="yes" checked>
                                        @else
                                            <input class="form-check-input" type="radio" name="payment_mandatory"
                                                id="gridRadios1" value="yes">
                                        @endif
                                        <label class="form-check-label" for="gridRadios1">
                                            Yes
                                        </label><br>
                                        @if ($sales_orders->payment_mandatory == 'no')
                                            <input class="form-check-input" type="radio" name="payment_mandatory"
                                                id="gridRadios2" value="no" checked>
                                        @else
                                            <input class="form-check-input" type="radio" name="payment_mandatory"
                                                id="gridRadios2" value="no">
                                        @endif
                                        <label class="form-check-label" for="gridRadios2">
                                            No
                                        </label>
                                    </div>


                                </div><br>

                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Warehouse
                                        </strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">

                                        <select class="form-select" id="warehouse_id" name="warehouse_id" required>
                                            <option value="{{ $sales_orders->warehouse_id }}">
                                                {{ $sales_orders->warehouse_title }}</option>
                                            @foreach ($warehouse as $warehouses)
                                                @if ($warehouses->id == $sales_orders->warehouse_id)
                                                    @continue
                                                @endif
                                                <option value="{{ $warehouses->id }}">{{ $warehouses->warehouse_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div><br>

                                <div class="row mb-8">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>GST
                                            Type</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-2">
                                        <select class="form-select" id="selected_type" name="gst_type"
                                            value="{{ $quotation->gst_type }}" onchange="get_state()" disabled>
                                            @if ($quotation->gst_type == 'state_gst')
                                                <option value="{{ $quotation->gst_type }}" selected>State Gst</option>
                                            @else
                                                <option value="{{ $quotation->gst_type }}" selected>Central Gst</option>
                                            @endif




                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Select Item</h5>

                                        <div class="col-md-12 col-sm-12 mb-30">
                                            <div class="pd-20 card-box height-100-p">
                                                <div class="row">
                                                    <h4 class="col-md-12 col-sm-12 mb-15 text-blue h4 col-xl-11">
                                                    </h4>

                                                </div>

                                                <div class="btn-list">
                                                    <table class="table datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>S.NO <span class="required-classes">*</span>
                                                                </th>
                                                                <th>Item Category <span class="required-classes">*</span>
                                                                </th>
                                                                <th>Item SubCategory<span class="required-classes">*</span>
                                                                </th>
                                                                <th>Length<span class="required-classes">*</span>
                                                                </th>
                                                                <th>UOM Type <span class="required-classes">*</span></th>
                                                                <th>PCs <span class="required-classes">*</span></th>
                                                                <th>Weight(kg) <span class="required-classes">*</span></th>
                                                                @can('price')
                                                                    <th>Price(/kg)<span class="required-classes">*</span></th>

                                                                    <th>Tax(%)<span class="required-classes">*</span></th>
                                                                    @if ($quotation->total_igst == 0)
                                                                        <th>SGST<span class="required-classes">*</span></th>
                                                                        <th>CGST<span class="required-classes">*</span></th>
                                                                    @else
                                                                        <th>IGST<span class="required-classes">*</span></th>
                                                                    @endif
                                                                    <th>Amount<span class="required-classes">*</span>
                                                                    <th>Rest Quantity<span class="required-classes">*</span>
                                                                    </th>
                                                                @endcan
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($so_items as $so_item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $so_item->name }}</td>
                                                                    <input type="text" name="category_id[]"
                                                                        style="display: none"
                                                                        value="{{ $so_item->item_category }}">
                                                                    <td>{{ $so_item->sub_category }}</td>
                                                                    <input type="text" name="subcategory_id[]"
                                                                        style="display: none"
                                                                        value="{{ $so_item->item_subcategory }}">
                                                                    <td>{{ $so_item->length }}</td>
                                                                    <td>{{ $so_item->uom_type }}</td>
                                                                    <input type="text" name="subcategory_id[]"
                                                                        style="display: none"
                                                                        value="{{ $so_item->uom_type }}">
                                                                    <input type="text" name="qty[]"
                                                                        style="display: none"
                                                                        value="{{ $so_item->qty }}">
                                                                    <input type="text" name="length[]"
                                                                        style="display: none"
                                                                        value="{{ $so_item->length }}">

                                                                    @if ($so_item->uom_type !== 'weight')
                                                                        <td style="background-color: rgb(0, 255, 255);">
                                                                            {{ $so_item->pcs }}</td>
                                                                    @else
                                                                        <td>{{ $so_item->pcs }}</td>
                                                                    @endif
                                                                    <input type="text" name="pcs[]"
                                                                        style="display: none"
                                                                        value="{{ $so_item->pcs }}">
                                                                    @if ($so_item->uom_type == 'weight')
                                                                        <td style="background-color:  rgb(0, 255, 255)">
                                                                            {{ $so_item->weight }}</td>
                                                                    @else
                                                                        <td>{{ $so_item->weight }}</td>
                                                                    @endif
                                                                    <input type="text" name="weight[]"
                                                                        style="display: none"
                                                                        value="{{ $so_item->weight }}">
                                                                    @can('price')
                                                                        <td>{{ $so_item->price }}</td>
                                                                        <input type="text" name="price[]"
                                                                            style="display: none"
                                                                            value="{{ $so_item->qt_price }}">


                                                                        <td>{{ $so_item->gst_percent }}</td>
                                                                        <input type="text" name="gst_percent[]"
                                                                            style="display: none"
                                                                            value="{{ $so_item->gst_percent }}">

                                                                        @if ($so_item->igst == 0)
                                                                            <td>{{ $so_item->sgst }}</td>
                                                                            <input type="text" name="sgst[]"
                                                                                style="display: none"
                                                                                value="{{ $so_item->sgst }}">

                                                                            <td>{{ $so_item->cgst }}</td>
                                                                            <input type="text" name="cgst[]"
                                                                                style="display: none"
                                                                                value="{{ $so_item->cgst }}">
                                                                        @else
                                                                            <td>{{ $so_item->igst }}</td>
                                                                            <input type="text" name="igst[]"
                                                                                style="display: none"
                                                                                value="{{ $so_item->igst }}">
                                                                        @endif
                                                                        <td>{{ $so_item->amount }}</td>
                                                                        <input type="text" name="amount[]"
                                                                            style="display: none"
                                                                            value="{{ $so_item->amount }}">
                                                                        <td>{{ $so_item->rest_pcs }}</td>
                                                                    @endcan
                                                                </tr>
                                                            @endforeach

                                                        <tfoot>

                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>

                                                            <th>Total</th>

                                                            <td> <input type="text" class="form-control"
                                                                    name="total_pcs" id="overall_total_pcs"
                                                                    value="{{ $quotation->total_pcs }}" required readonly>
                                                            </td>
                                                            <td> <input type="text" class="form-control"
                                                                    name="total_weight" id="overall_total_weight"
                                                                    value="{{ $quotation->total_weight }}" required
                                                                    readonly></td>

                                                        </tfoot>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-5">
                                            <div class="col-lg-6">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label"><strong>Remarks
                                                    </strong></label>
                                                <textarea class="form-control" name="remarks" value ="{{ $sales_orders->remarks }}" placeholder="Remark"
                                                    id="floatingTextarea" style="height: 100px;">{{ $sales_orders->remarks }}</textarea>
                                            </div>
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-4 ">
                                                <div class="row">


                                                    {{-- <div class="col-lg-6 mb-2">
                                                            <label for="inputPassword3" class="  col-form-label"><strong>
                                                                    Total PCs</strong> </label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" name="total_pcs"
                                                                id="overall_total_pcs" value="{{ $sales_orders->total_pcs }}"
                                                                required readonly>
                                                        </div>
                                                
                                                   
                                                        <div class="col-lg-6 mb-2">
                                                            <label for="inputPassword3" class="  col-form-label"><strong>
                                                                    Total Weight</strong> </label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control"
                                                                name="total_weight" id="overall_total_weight"
                                                                value="{{ $sales_orders->total_weight }}" required readonly>
                                                        </div> --}}


                                                    @can('price')
                                                        <div class="col-lg-6 mb-2">
                                                            <label for="inputPassword3" class="  col-form-label"><strong>Total
                                                                    Amount</strong> </label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" name="sub_total"
                                                                id="material_value" value="{{ $quotation->sub_total }}"
                                                                required readonly>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <label for="inputPassword3"
                                                                class="  col-form-label"><strong>Loading/Cutting</strong><span
                                                                    class="required-classes">*</span></label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" name="loading_cutting"
                                                                id="loading" value="{{ $quotation->loading_cutting }}"
                                                                required readonly>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <label for="inputPassword3"
                                                                class="  col-form-label"><strong>Additional
                                                                    Charges</strong><span
                                                                    class="required-classes">*</span></label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control"
                                                                name="additional_charges"
                                                                value="{{ $quotation->additional_charges }}"
                                                                id="additional_charges" required readonly>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <label for="inputPassword3"
                                                                class="  col-form-label"><strong>Freight </strong><span
                                                                    class="required-classes">*</span></label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" value="{{ $quotation->freight_charges }}"
                                                                class="form-control" name="freight" id="freight" required
                                                                readonly>
                                                        </div>
                                                        @if ($quotation->total_igst == 0)
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="inputPassword3"
                                                                    class="  col-form-label"><strong>SGST</strong> </label>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input type="text" value="{{ $quotation->total_sgst }}"
                                                                    class="form-control" name="total_sgst" id="totalSGST"
                                                                    required readonly>
                                                            </div>

                                                            <div class="col-lg-6 mb-2">
                                                                <label for="inputPassword3"
                                                                    class="  col-form-label"><strong>CGST</strong> </label>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input type="text" value="{{ $quotation->total_cgst }}"
                                                                    class="form-control" name="total_cgst" id="totalCGST"
                                                                    required readonly>
                                                            </div>
                                                        @else
                                                            <div class="col-lg-6 mb-2">
                                                                <label for="inputPassword3"
                                                                    class="  col-form-label"><strong>IGST</strong> </label>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input type="text" value="{{ $quotation->total_igst }}"
                                                                    class="form-control" name="total_igst" id="totalIGST"
                                                                    required readonly>
                                                            </div>
                                                        @endif



                                                        <div class="col-lg-6 mb-2">
                                                            <label for="inputPassword3" class="  col-form-label"><strong>Grand
                                                                    Total
                                                                </strong> </label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" name="grand_total"
                                                                id="grandTotal" value="{{ $quotation->grand_total }}"
                                                                required readonly>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>


                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-3">
                                            @can('Sales-edit')
                                                @can('price')
                                                    @if ($sales_orders->status == 'pending')
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    @endif
                                                @endcan
                                            @endcan
                                            {{-- <a class="btn btn-secondary" href="{{ route('sales.index') }}">Back</a> --}}
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


@endsection
