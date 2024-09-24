@extends('layouts.main')
@section('title','Sales Order - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="  dashboard-header pagetitle">
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
                    <form method="POST" action="{{ route('sales_quotation.store') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>
                                <input type="hidden" id="overall_total_weight" readonly name="total_weight">
                                <input type="hidden" id="" value="{{ $quotation->gst_type }}" name="gst_type">


                                <!-- Horizontal Form -->

                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Bill
                                            To : - </strong></label>
                                    <div class="col-sm-4 mt-1">
                                        <p>{{ $company->company_name }}</p>
                                        <input type="hidden" class="form-control" name="company_name" id="inputText"
                                            value="{{ $company->company_name }}">
                                        <input type="hidden" value="{{ $company->id }}" name="company_id">
                                        <input type="hidden" value="{{ $so_type }}" name="so_type">


                                    </div>

                                    <div class="col-lg-6 pe-5 text-end">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label"><strong>Quotation Number :
                                            </strong></label>
                                        <label for="inputEmail3" class=" col-form-label">
                                            {{ $quotation->document_number }}</label>
                                        <input type="hidden" name="qt_id" value="{{ $quotation->id }}">
                                        {{-- <input type="hidden"  name="total_weight" value="{{$quotation->total_weight}}"> --}}

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
                                            {{ $next_document }}</label>
                                        <input type="hidden" name="document_number" value="{{ $next_document }}">

                                    </div>

                                </div>

                                <?php
                                $currentDate = date('Y-m-d');
                                ?>
                                <div class="row mb-8">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Date</strong><span
                                            class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" name="date"
                                            value="{{ $currentDate }}" id="inputPassword" required>
                                    </div>
                                </div><br>

                                <div class="row mb-8">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Payment
                                            Mandatory</strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input class="form-check-input" type="radio" name="payment_mandatory"
                                            id="gridRadios1" value="yes">
                                        <label class="form-check-label" for="gridRadios1">
                                            Yes
                                        </label><br>
                                        <input class="form-check-input" type="radio" name="payment_mandatory"
                                            id="gridRadios2" value="no">
                                        <label class="form-check-label" for="gridRadios2">
                                            No
                                        </label>
                                    </div>
                                </div><br>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Warehouse
                                        </strong><span class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        @livewire('warehouse')
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
                                                                @if (auth()->user()->roles->contains('name', 'Admin'))
                                                                    <th>Price(/kg)<span class="required-classes">*</span></th>
                                                                @endif
                                                                <th>Tax(%)<span class="required-classes">*</span></th>
                                                                @if ($quotation->total_igst == 0)
                                                                    <th>SGST<span class="required-classes">*</span></th>
                                                                    <th>CGST<span class="required-classes">*</span></th>
                                                                @else
                                                                    <th>IGST<span class="required-classes">*</span></th>
                                                                @endif



                                                                <th>Amount<span class="required-classes">*</span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($qt_item as $qt_items)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $qt_items->name }}</td>
                                                                    <input type="text" style="display: none"
                                                                        name="category_id[]"
                                                                        value="{{ $qt_items->item_category }}">
                                                                    <td>{{ $qt_items->sub_category }}</td>
                                                                    <input type="text" style="display: none"
                                                                        name="subcategory_id[]"
                                                                        value="{{ $qt_items->item_subcategory }}">
                                                                    <td>{{ $qt_items->length }}</td>
                                                                    <td>{{ $qt_items->uom_type }}</td>
                                                                    <input type="text" style="display: none"
                                                                        name="uom_type[]"
                                                                        value="{{ $qt_items->uom_type }}">
                                                                    <input type="text" style="display: none"
                                                                        name="qty[]" value="{{ $qt_items->qty }}">
                                                                    <input type="text" style="display: none"
                                                                        name="length[]" value="{{ $qt_items->length }}">
                                                                    <td>{{ $qt_items->pcs }}</td>
                                                                    <input type="text" style="display: none"
                                                                        name="pcs[]" value="{{ $qt_items->pcs }}">
                                                                    <td>{{ $qt_items->qt_weight }}</td>
                                                                    <input type="text" style="display: none"
                                                                        name="weight[]"
                                                                        value="{{ $qt_items->qt_weight }}">
                                                                    @if (auth()->user()->roles->contains('name', 'Admin'))
                                                                        <td>{{ $qt_items->qt_price }}</td>
                                                                        <input type="text" style="display: none"
                                                                            name="price[]"
                                                                            value="{{ $qt_items->qt_price }}">
                                                                    @endif

                                                                    <td>{{ $qt_items->gst_percent }}</td>
                                                                    <input type="text" style="display: none"
                                                                        name="gst_percent[]"
                                                                        value="{{ $qt_items->gst_percent }}">

                                                                    @if ($qt_items->igst == 0)
                                                                        <td>{{ $qt_items->sgst }}</td>
                                                                        <input type="text" style="display: none"
                                                                            name="sgst[]" value="{{ $qt_items->sgst }}">

                                                                        <td>{{ $qt_items->cgst }}</td>
                                                                        <input type="text" style="display: none"
                                                                            name="cgst[]" value="{{ $qt_items->cgst }}">
                                                                    @else
                                                                        <td>{{ $qt_items->igst }}</td>
                                                                        <input type="text" style="display: none"
                                                                            name="igst[]" value="{{ $qt_items->igst }}">
                                                                    @endif
                                                                    <td>{{ $qt_items->amount }}</td>
                                                                    <input type="text" style="display: none"
                                                                        name="amount[]" value="{{ $qt_items->amount }}">
                                                                </tr>
                                                            @endforeach
                                                        </tbody>

                                                        <tfoot>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            
                                                                <th>Total</th>

                                                                <td>   <input type="text" class="form-control" name="total_pcs"
                                                                    id="overall_total_pcs" value="{{ $quotation->total_pcs }}"
                                                                    required readonly></td>
                                                                <td> <input type="text" class="form-control" name="total_weight"
                                                                    id="overall_total_weight"
                                                                    value="{{ $quotation->total_weight }}" required readonly></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-5">
                                            <div class="col-lg-6">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label"><strong>Remarks
                                                    </strong></label>
                                                <textarea class="form-control" name="remarks" placeholder="Remark" id="floatingTextarea" style="height: 100px;"></textarea>
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
                                                            id="overall_total_pcs" value="{{ $quotation->total_pcs }}"
                                                            required readonly>
                                                    </div>


                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="  col-form-label"><strong>
                                                                Total Weight</strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="total_weight"
                                                            id="overall_total_weight"
                                                            value="{{ $quotation->total_weight }}" required readonly>
                                                    </div> --}}

                                                    <div class="col-lg-6 mb-2">
                                                        <label for="inputPassword3" class="  col-form-label"><strong>
                                                                Total Amount</strong> </label>
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
                                                        <label for="inputPassword3" class="  col-form-label"><strong>Total
                                                            </strong> </label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" name="grand_total"
                                                            id="grandTotal" value="{{ $quotation->grand_total }}"
                                                            required readonly>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>





                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-3">
                                            @can('price')
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            @endcan
                                            <a class="btn btn-secondary" href="{{ route('sales.index') }}">Back</a>
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
@endsection
