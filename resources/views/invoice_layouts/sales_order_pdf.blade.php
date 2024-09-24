<!DOCTYPE htmlSales Order Invoice
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sales Order Document</title>

    <style type="text/css">
        body {
            font-family: Arial, Helvetica;
        }

        .maintable {
            width: 100%;
            border-collapse: collapse;
        }

        p {
            margin: 5px;
            font-size: 12px;
        }

        li {
            font-size: 12px;
        }

        .item_heading {
            font-size: 12px;
            font-weight: bold
        }

        h3,
        h4,
        h5,
        h6 {
            margin: 5px;
            font-size: 12px;
        }

        th>p {
            font-size: 15px;
        }

        h3 {
            padding-left: 5px;
        }

        .title {
            font-size: 20px;
        }

        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }

        .table_td {
            border: solid 1px;
        }

        .item1 {
            grid-area: header;
            padding: 10px;
        }

        .item2 {
            grid-area: menu;
            position: absolute;
            top: 20px;
            left: 150px;
        }


        .heading_second {
            margin: 5px !important;
        }

        .heading_text {
            text-align: center;
        }

        .amount_text {
            text-align: right
        }

        .font_weight_bold {
            font-weight: bolder;
        }

        .inline-items p {
            display: inline;
            margin-right: 10px;
            /* Adjust spacing as needed */
        }
    </style>
</head>

<body>
    <div>
        <div class="row d-flex">
            <table class="maintable" cellspacing="0">
                <tr>
                    <td class="table_td" colspan="10">
                        <div class="grid_container">
                            <div class="item1">
                                <img src="assets/img/logo.png" alt="">
                            </div>
                            <div class="item2">
                                <h2 class="heading_second heading_text">Saraswati Globals</h2>
                                <h6 class="heading_second">Plot No. B12, Near Hirapur Chowk, Loha Bazaar, Kabir Nagar,
                                    <br>
                                    <span style="margin-left: 70px">Raipur-492001, Chhattisgarh, India Email:  {{ $company_setting->email }}</span><span></span>
                                </h6>
                                <h6 class="heading_second heading_text">Phone :8048371583, Mobile :8048371583</h6>
                                <h6 class="heading_second">  <br> GST No<span>:</span>{{ $company_setting->gst_no }} <span><span></span><span></span></span>|<span>PAN NO</span><span><span></span>:</span><span>{{ $company_setting->pan }}</span><span></span>|<span>TAN NO</span><span>:</span><span<span>{{ $company_setting->tan }}</span></span>
                                    <br>
                                    <span style="margin-left: 70px"> </span>
                                </h6>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="" class="table_td font_weight_bold" style="text-align: center">
                    <td colspan="10">
                        <h3><p>Sales Order</p></h3>
                    </td>
                </tr>
                {{-- <tr class="table_td" style="text-align: center">
                    <td colspan="10">
                        <p>Website :-</p>
                        <p>Email : {{ $company_setting->email }}</p>
                    </td>
                </tr> --}}
                {{-- <tr class="table_td">
                    <td class="p-1" colspan="3">
                        <p class="text-center">GST No. </p>
                        <p class="text-center">PAN NO. </p>
                        <p class="text-center">TAN NO. </p>

                    </td>
                    <td class="p-1" colspan="1">
                        <p class="text-center">:</p>
                        <p class="text-center">:</p>
                        <p class="text-center">:</p>
                    </td>
                    <td class="p-1" colspan="1">
                        <p class="text-center font_weight_bold">{{ $company_setting->gst_no }}</p>
                        <p class="text-center font_weight_bold">{{ $company_setting->pan }}</p>
                        <p class="text-center font_weight_bold">{{ $company_setting->tan }}</p>
                    </td>
                    <td class="p-1" colspan="2" style="border-left: 1px solid black;">
                        <p class="text-center">State</p>
                        <p class="text-center">City</p>
                    </td>
                    <td class="p-1" colspan="1">
                        <p class="text-center">:</p>
                        <p class="text-center">:</p>

                    </td>
                    <td class="p-1" colspan="2">
                        <p class="text-center  font_weight_bold">{{ $company_setting->state }}</p>
                        <p class="text-center  font_weight_bold">{{ $company_setting->city }}</p>

                    </td>
                </tr> --}}
                {{-- <tr class="table_td">
                    <td class="p-1" colspan="3">
                        <p class="text-center"><strong>Sales Order No.</strong></p>
                        <p class="text-center"><strong>Date.</strong></p>
                        <p class="text-center"><strong>Sales Order type</strong></p>
                    </td>

                    <td class="p-1" colspan="1">
                        <p class="text-center">:</p>
                        <p class="text-center">:</p>
                        <p class="text-center">:</p>
                    </td>

                    <td class="p-1" colspan="1">
                        <p class="text-center font_weight_bold">{{ $soItem->so_number }}</p>
                        <p class="text-center font_weight_bold">{{ $soItem->date }}</p>
                        <p class="text-center font_weight_bold">{{ $soItem->so_type }}</p>
                    </td> 
                      <td class="p-1" colspan="2" style="border-left: 1px solid black;">
                    </td>
                    <td class="p-1" colspan="1">
                    </td>
                    <td class="p-1" colspan="2">
                    </td>
                </tr> --}}
                <tr class="table_td">
                    <td colspan="2">
                        <p class="font_weight_bold ">Sales Order No.</p>
                        <p class=" font_weight_bold">Date.</p>

                    </td>

                    <td colspan="1">
                        <p class=" p-1">:</p>
                        <p class=" p-1">:</p>

                    </td>

                    <td colspan="2">
                        <p class=" ">{{$soItem->so_number }}</p>
                        <p class=" ">{{ $soItem->date }}</p>

                    </td>

                    <td colspan="2">
                        <p class="font_weight_bold">Sales Order type.</p>
                        @if($soItem->document_number)
                            <p class="font_weight_bold">Quotation No</p>
                        @endif
                    </td>

                    <td colspan="1">
                        <p class=" ">:</p>
                        {{-- <p class=" ">:</p> --}}
                        @if($soItem->document_number)
                        <p class=" ">:</p>
                    @endif

                    </td>

                    <td colspan="2">
                        <p>{{ $soItem->so_type }}</p>
                        @if($soItem->so_type)
                            <p>{{ $soItem->document_number }}</p>
                        @else
                            <p>.</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-1 table_td" colspan="5">
                        <p class="text-center" style="text-align: center"><strong>Details of Buyers</strong></p>
                    </td>
                    <td class="p-1 table_td" colspan="5">
                        <p class="text-center" style="text-align: center"><strong>Details of Consignee</strong></p>
                    </td>
                </tr>
                <tr>
                    <td class="p-1 table_td" colspan="5">

                        <p class="font_weight_bold">{{ $company->company_name }}</p>
                        <p> {{ $company->address }} </p>
                        <p class=" ">{{ $company->mobile }}</p>
                    </td>
                    <td class="p-1 table_td" colspan="5">

                        <p class="font_weight_bold">{{ $company->company_name }}</p>
                        <p> {{ $company->address }} </p>
                        <p class=" ">{{ $company->mobile }}</p>
                    </td>
                </tr>
                <tr style="height:17px">
                    <th class="table_td" colspan="10">
                        <p class="" style="font-size: 12px;">List of Items</p>
                    </th>
                </tr>
                <tr>
                    <th class="p-2 table_td"  colspan="1">
                        <p class="item_heading">#</p>
                    </th>
                    <th class="table_td" colspan="4">
                        <p class="item_heading">Item Description</p>
                    </th>

                    <th class="table_td"  colspan="1">
                        <p class="item_heading">PCs</p>
                    </th>
                    <th class="table_td"  colspan="1">
                        <p class="item_heading">Weight (kg)</p>
                    </th>

                    <th class=" table_td"  colspan="1" >
                        <p class="item_heading">Price (/kg)</p>
                    </th>

                    <th class=" table_td"  colspan="1">
                        <p class="item_heading">Tax (%)</p>
                    </th>
                    <th class=" table_td"  colspan="1">
                        <p class="item_heading">Amount</p>
                    </th>

                </tr>
                @foreach ($so_items as $so_item)
                    <tr>
                        <td class="p-2 table_td" colspan="1">
                            <p class="">{{ $loop->iteration }}</p>
                        </td>
                        <td class="table_td" colspan="4">
                            <p class="item_heading">{{ $so_item->name }} <span style="margin-left: 10px;">{{ $so_item->sub_category }}</span></p>
                        </td>

                        <td class="table_td" colspan="1">
                            <p class="">{{ $so_item->pcs }}</p>
                        </td>

                        <td class="table_td" colspan="1">
                            <p class="text-center">{{ $so_item->so_weight }}</p>
                        </td>

                        <td class=" table_td" colspan="1">
                            <p class="text-center">{{ $so_item->so_price }}</p>
                        </td>

                        <td class=" table_td" colspan="1">
                            <p class="text-center">{{ $so_item->gst_percent }} </p>
                        </td>
                        <td class=" table_td" colspan="1">
                            <p class="text-center">{{ $so_item->amount }}</p>
                        </td>

                    </tr>
                @endforeach
                <tr>
                    <td class=" table_td" colspan="1"></td>
                    <td class="item_heading text-center" colspan="4">Total</td>
                    <td class="table_td" colspan="1">
                        <p class="text-center item_heading">{{ $soItem->total_pcs }}</p>
                    </td>

                    <td class="table_td" colspan="1">
                        <p class="text-center item_heading">{{ $soItem->total_weight }}</p>
                    </td>

                    <td class=" table_td" colspan="1">
                        <p class="text-center"></p>
                    </td>

                    <td class=" table_td" colspan="1">
                    </td>
                    <td class=" table_td" colspan="1">
                        <p class="text-center item_heading">{{ $soItem->sub_total }}</p>
                    </td>
                </tr>
                <tr class="table_td">
                    <td style="font-size: 12px" class=" table_td" colspan="7"><strong>Payment Terms :</strong>
                        {{ $soItem->payment_term }}</td>
                    <td class=" table_td" colspan="2">
                        {{-- <p class="font_weight_bold"><strong>Material Value</strong><span></span></p> --}}
                        <p class="font_weight_bold">Loading<span></span></p>

                        <p class="font_weight_bold">Freight<span></span></p>

                        <p class="font_weight_bold">Additional Charges<span></span></p>
                        <p class="font_weight_bold">Sub Total<span></span></p>

                        @if ($soItem->total_igst == 0)
                            <p class="font_weight_bold">CGST <span></span></p>
                            <p class="font_weight_bold">SGST <span></span></p>
                        @else
                            <p class="font_weight_bold">IGST <span></span></p>
                        @endif
                    </td>
                    <td class=" table_td" colspan="1">
                        {{-- <p class="text-center">{{ $quotation->sub_total }}</p> --}}
                        <p class=" text-center">{{ $soItem->loading_charges }}</p>
                        <p class="text-center">{{ $soItem->freight }}</p>
                        <p class=" text-centert">{{ $soItem->additional_charges }}</p>
                        <?php
                        $sub_toal = $soItem->loading_cutting + $soItem->freight_charges + $soItem->additional_charges + $soItem->sub_total;
                        ?>
                        <p class="text-center">{{ $sub_toal }}</p>
                        @if ($soItem->total_igst == 0)
                            <p class=" text-center">{{ $soItem->total_cgst }}</p>
                            <p class=" text-center">{{ $soItem->total_sgst }}</p>
                        @else
                            <p class="text-center">{{ $soItem->total_igst }}</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class=" table_td" colspan="7"></td>
                    <td style="font-size: 12px" class=" table_td" colspan="2"><strong> Grand Total</strong></td>
                    <td style="font-size: 12px" class=" table_td text-center font_weight_bold" colspan="1">
                        {{ $soItem->grand_total }}</td>
                </tr>
               
               



                <tr class="table_td">
                    <td colspan="2">
                        <p class="font_weight_bold ">BANK</p>
                        <p class=" font_weight_bold">BRANCH</p>

                    </td>

                    <td colspan="1">
                        <p class=" p-1">:</p>
                        <p class=" p-1">:</p>

                    </td>

                    <td colspan="2">
                        <p class=" ">{{ $company_setting->bank_name }}</p>
                        <p class=" ">{{ $company_setting->branch }}</p>

                    </td>

                    <td colspan="2">
                        <p class="font_weight_bold">A/C NO.</p>
                        <p class="font_weight_bold">IFSC CODE</p>

                    </td>

                    <td colspan="1">
                        <p class=" ">:</p>
                        <p class=" ">:</p>

                    </td>

                    <td colspan="2">
                        <p class=" ">{{ $company_setting->ac_number }}</p>
                        <p class=" ">{{ $company_setting->ifsc_code }}</p>

                    </td>
                </tr>
                <tr>
                    <td class="table_td" colspan="10">
                        <p><strong>soItem Amount In Words :</strong></p>
                        <p class="font_weight_bold">{{ $totalAmountInWords }}</p>
                    </td>
                </tr>

                <tr>
                    <td class="table_td" colspan="10">
                        <p>
                            Certified that the particulars given above are true and correct and the amount indicated
                            represents the price
                            actually charged and that there is no flow additional consideration directly or indirectly
                            from
                            the buyer.
                        </p>
                        <p>
                            <strong>
                                Terms & Conditions:
                            </strong>
                        </p>
                        {{-- <ol class="mb-0">
                            <li>Payment on Tax invoice .</li>
                            <li>Booking amount 20% .</li>
                            <li>Prices are not valid before booking.</li>
                            <li>Below 5 Ton in each size 500 extra will be charge on per ton.</li>
                            <li>Below 5 Ton the loading charge will be 765 per ton</li>
                            <li>For the customized size , the rate will be extra</li>
                            <li>Subject to Raipur Jurisdiction.</li>
                        </ol> --}}
                        @if ($soItem->remarks)
                            <p>
                                {{ $soItem->remarks }}
                            </p>
                        @else
                            <p>
                                {{ $company_setting->remarks }}
                            </p>
                        @endif
                        <p style="position:relative; left:550px"><b>Authorized Signatory</b></p>
                    </td>
                </tr>




            </table>

        </div>
    </div>
</body>

</html>
