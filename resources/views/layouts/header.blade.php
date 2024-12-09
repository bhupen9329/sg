<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">


    <!-- Favicons -->
    <link href="{{ asset('assets/img/') }}" rel="icon">
    <link href="{{ asset('assets/img/') }}" rel="apple-touch-icon">



    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    {{-- select2 --}}
    {{-- <script src="{{ asset('select2/jquery-3.6.0.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('DataTables/datatables.css') }}" />
    <script src="{{ asset('DataTables/datatables.js') }}"></script> --}}

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="...">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- Select2 CSS File -->
    <link href="{{ asset('select2/select2.min.css') }}" rel="stylesheet" />

    <!-- summernote CSS File -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css" rel="stylesheet" />
    {{-- select2 --}}
    <script src="{{ asset('select2/jquery-3.6.0.min.js') }}"></script>





    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <!-- DataTables Buttons JS -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.flash.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js">
    </script>



    @livewireStyles
</head>

<body>

    <style>
        .loader_container {
            position: fixed;
            z-index: 2000;
            height: 100%;
            width: 100%;
        }

        .loader {
            position: absolute;
            left: 50%;
            top: 40%;
            width: 50px;
            padding: 2px;
            aspect-ratio: 1;
            border-radius: 50%;
            background: #012970;
            --_m: conic-gradient(#0000 10%, #000),
                linear-gradient(#000 0 0) content-box;
            -webkit-mask: var(--_m);
            mask: var(--_m);
            -webkit-mask-composite: source-out;
            mask-composite: subtract;
            animation: l3 1s infinite linear;
            animation-fill-mode: forwards;
        }


        @keyframes l3 {
            to {
                transform: rotate(1turn)
            }
        }
    </style>
    <div class="container-fluid loader_container">
        <div class="loader" id="select_loader"></div>
    </div>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ url('/dashboard') }}" class="logo d-flex align-items-center">
                {{-- <img src="{{ asset('assets/img/logo.png') }}" alt=""> --}}
                <span class="d-none d-lg-block">Saraswati Globals</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->



        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        @if (Auth::user()->profile)
                            <img src="{{ asset('uploads/user_profile/' . Auth::user()->id . '/' . Auth::user()->profile) }}"
                                alt="Profile">
                        @else
                            <img src="{{ asset('assets/img/profile-img.png') }}" alt="Profile">
                        @endif
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ Auth::user()->name }}</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="{{ url('users_profile', ['user_id' => Auth::user()->id]) }}">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ url('logout') }}">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>


            {{-- @can('Quotation-index')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('quotation.index') }}">
                        <i class="fa-solid fa-quote-left"></i>
                        <span>Quotation</span>
                    </a>
                </li>
            @endcan --}}


            @if ((auth()->check() && auth()->user()->can('Sales-index')) || auth()->user()->can('Purchase-index'))
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#sales-nav" data-bs-toggle="collapse" href="#">
                        <i class="fa fa-shopping-cart"></i>
                        </i><span>Sales & Purchase</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="sales-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li>
                            @can('Sales-index')
                                <a href="{{ route('sales.index') }}">
                                    <i class="bi bi-circle"></i><span>Sales Order</span>
                                </a>
                            @endcan
                        </li>
                        <li>
                            @can('Purchase-index')
                                <a href="{{ route('purchase.index') }}">
                                    <i class="bi bi-circle"></i><span>Purchase Order</span>
                                </a>
                            @endcan
                        </li>
                    </ul>
                </li><!-- End Icons Nav -->
            @endif


            {{-- @can('Inward-index')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('inward.index') }}">
                        <i class="fa-solid fa-down-left-and-up-right-to-center"></i>
                        <span>Inward</span>
                    </a>
                </li>
            @endcan

            @can('Outward-index')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('outward.index') }}">
                        <i class="fa-solid fa-angles-right"></i>
                        <span>Outward</span>
                    </a>
                </li>
            @endcan



            @can('Stock-index')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('stock.index') }}">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                        <span>Virtual Store</span>
                    </a>
                </li>
            @endcan --}}
            {{-- @can('Stock-Adjustment-index')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('adjustment.index') }}">
                        <i class="fa-solid fa-plus-minus"></i>
                        <span>Stocks Adjustment</span>
                    </a>
                </li>
            @endcan --}}
            
            {{-- @can('stock-transaction')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('stock_transaction.index') }}">
                        <i class="fa-solid fa-exchange-alt"></i>

                        <span>Stocks Transaction</span>
                    </a>
                </li>

               @endcan --}}


    

            {{-- @can('Warehouse-index')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('warehouse.index') }}">
                        <i class="fa-solid fa-warehouse"></i>
                        <span>Warehouse</span>
                    </a>
                </li>
            @endcan --}}

            @can('Company-index')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('buyers.index') }}">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                        <span>Buyers & Suppliers</span>
                    </a>
                </li>
            @endcan

            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('manual.matching')}}">
                    <i class="fa-solid fas fa-pencil-alt"></i>
                    <span>Manual Matching</span>
                </a>
            </li> --}}

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('dispatch.index') }}">
                    <i class="fa-solid fas fa fa-paper-plane"></i>
                    <span>Dispatch</span>
                </a>
            </li>

            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('inventory_valuation.index')}}">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                    <span>Inventory Valuation</span>
                </a>
            </li> --}}


            @if (
                (auth()->check() && auth()->user()->can('PO-Report')) ||
                    auth()->user()->can('SO-Report') ||
                    auth()->user()->can('Quotation-Report') ||
                    auth()->user()->can('Inward-Report') ||
                    auth()->user()->can('Outward-Report') ||
                    auth()->user()->can('Stock-Report') ||
                    auth()->user()->can('Stock-Transaction-Report') ||
                    auth()->user()->can('Ageing-Report') ||
                    auth()->user()->can('Top-Selling-Report'))
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#report-nav" data-bs-toggle="collapse"
                        href="#">
                        <i class="bi bi-file-text"></i>

                        </i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="report-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        {{-- <li>
                            @can('PO-Report')
                                <a href="{{ route('po_report') }}">
                                    <i class="bi bi-circle"></i><span>Purchase Order Report</span>
                                </a>
                            @endcan
                        </li>
                        <li>
                            @can('SO-Report')
                                <a href="{{ route('so_report') }}">
                                    <i class="bi bi-circle"></i><span>Sales Order Report </span>
                                </a>
                            @endcan
                        </li>
                        <li>
                            @can('Inward-Report')
                                <a href="{{ route('inward_report') }}">
                                    <i class="bi bi-circle"></i><span>Inward Report</span>
                                </a>
                            @endcan
                        </li>

                        <li>
                            @can('Outward-Report')
                                <a href="{{ route('outward_report') }}">
                                    <i class="bi bi-circle"></i><span> Outward Report</span>
                                </a>
                            @endcan
                        </li>


                        <li>
                            @can('Stock-Report')
                                <a href="{{ route('stock_report') }}">
                                    <i class="bi bi-circle"></i><span>Virtual Store Report</span>
                                </a>
                            @endcan
                        </li> --}}
 

                        

                        <li>
                        
                                <a href="{{ route('position.report') }}">
                                    <i class="bi bi-circle"></i><span>Position Report </span>
                                </a>
                          
                        </li>

                        <li>
                        
                            <a href="{{ route('po_report') }}">
                                <i class="bi bi-circle"></i><span>Purchase Report </span>
                            </a>
                      
                    </li>
 

                        <li>
        
                                <a href="{{ route('so_report') }}">
                                    <i class="bi bi-circle"></i><span>Sales Report</span>
                                </a>
                    
                        </li>
                        <li>
        
                                <a href="{{ route('dispatch_report') }}">
                                    <i class="bi bi-circle"></i><span>Dispatch Report</span>
                                </a>
                    
                        </li>
 
                        {{-- <li>
                            @can('Quotation-Execution-Report')
                                <a href="{{ route('quotation_execution_report') }}">
                                    <i class="bi bi-circle"></i><span> Quotation Execution Report</span>
                                </a>
                            @endcan
                        </li> --}}

                    </ul>
                </li><!-- End Icons Nav -->
            @endif



            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#report-aggregate-nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-file-text"></i>

                    </i><span>Additional Reports</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="report-aggregate-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                    <li>
                    
                            <a href="{{ route('company_wise.report') }}">
                                <i class="bi bi-circle"></i><span>Company Wise Report </span>
                            </a>
                      
                    </li>

                    <li>
                    
                        <a href="{{ route('due_so_report') }}">
                            <i class="bi bi-circle"></i><span>Due SO Report </span>
                        </a>
                  
                </li>

                <li>
                    
                    <a href="{{ route('due_po_report') }}">
                        <i class="bi bi-circle"></i><span>Due PO Report </span>
                    </a>



              
            </li>

                  

                    {{-- <li>
                        @can('Quotation-Execution-Report')
                            <a href="{{ route('quotation_execution_report') }}">
                                <i class="bi bi-circle"></i><span> Quotation Execution Report</span>
                            </a>
                        @endcan
                    </li> --}}

                </ul>
            </li><!-- End Icons Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('inventory.report') }}">
                    <i class="fa-solid fa-box"></i>

                    <span>Stocks</span>
                </a>
            </li>

            @if ((auth()->check() && auth()->user()->can('Category-index')) || auth()->user()->can('Sub-Category-index'))
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#category-nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-archive-fill"></i>
                    </i><span>Item Master</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="category-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        @can('Category-index')
                            <a href="{{ route('category.index') }}">
                                <i class="bi bi-circle"></i><span>Base Item</span>
                            </a>
                        @endcan
                    </li>


                    <li>
                        @can('Sub-Category-index')
                            <a href="{{ route('subcategory.index') }}">
                                <i class="bi bi-circle"></i><span>Conversion Item</span>
                            </a>
                        @endcan
                    </li>

                    <li>
                        @can('Sub-Category-index')
                            <a href="{{ route('rate.index')}}">
                                <i class="bi bi-circle"></i><span>Conversion Rate</span>
                            </a>
                        @endcan
                    </li>

                    <li>
               
                            <a href="{{ route('freight_rate.index')}}">
                                <i class="bi bi-circle"></i><span>Freight Rate</span>
                            </a>
                           

                      
                    </li>
                </ul>
            </li><!-- End Icons Nav -->
        @endif



            @if ((auth()->check() && auth()->user()->can('User-index')) || auth()->user()->can('Role-index'))
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse"
                        href="#">
                        <i class="fa-solid fa-users"></i><span>Users Control</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li>
                            @can('User-index')
                                <a href="{{ route('users.index') }}">
                                    <i class="bi bi-circle"></i><span>User Management</span>
                                </a>
                            @endcan
                        </li>
                        <li>
                            @can('Role-index')
                                <a href="{{ route('roles.index') }}">
                                    <i class="bi bi-circle"></i><span>Access Management</span>
                                </a>
                            @endcan
                        </li>
                    </ul>
                </li><!-- End Icons Nav -->
            @endif


            @if (
                (auth()->check() && auth()->user()->can('Setting-company')) ||
                    auth()->user()->can('Setting-gst') ||
                    auth()->user()->can('Setting-email'))
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#setting-nav" data-bs-toggle="collapse"
                        href="#">
                        <i class="bi bi-gear"></i>
                        </i><span>Setting</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="setting-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        {{-- @can('Setting-email')
                            <li>
                                <a href="{{ route('email.create') }}">
                                    <i class="bi bi-circle"></i><span>Email</span>
                                </a>
                            </li>
                        @endcan --}}
                        @can('Setting-company')
                            <li>
                                <a href="{{ route('setting.company_create') }}">
                                    <i class="bi bi-circle"></i><span>Company</span>
                                </a>
                            </li>
                        @endcan
                        {{-- @can('GST-index')
                            <li>
                                <a href="{{ route('setting.gst') }}">
                                    <i class="bi bi-circle"></i><span>GST</span>
                                </a>
                            </li>
                        @endcan --}}


                    </ul>
                </li><!-- End Icons Nav -->
            @endif
        </ul>
    </aside><!-- End Sidebar-->
