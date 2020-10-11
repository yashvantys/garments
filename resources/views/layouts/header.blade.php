<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Garments</title>
    <!-- Custom fonts for this template-->
    <link href="{{URL::asset('css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.typekit.net/ffx5zmj.css" rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{URL::asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/admin.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap core JavaScript-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> 
    <!-- <script src="{{URL::asset('css/jquery/jquery.min.js')}}"></script> -->
    <script src="{{URL::asset('css/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{URL::asset('css/jquery-easing/jquery.easing.min.js')}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{URL::asset('js/sb-admin-2.min.js')}}"></script>
    <!-- Page level plugins -->
    <script src="{{URL::asset('css/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('css/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Page level custom scripts -->
    <script src="{{URL::asset('js/datatables-demo.js')}}"></script>    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">     
     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> </head>

</head>
<body id="page-top">
    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('transaction-logs')}}">
            
            <div class="sidebar-brand-text mx-3">Garments <span></span></div>
        </a>
        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
      <span class="fa fa-bars"></span>
    </button>

        <ul class="navbar-nav ml-auto">
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="img-profile rounded-circle" src="{{URL::asset('media/images/admin.png')}}" alt="">
                    <span class="ml-2 d-none d-lg-inline text-grey">{{ Auth::user()->name }}</span>
                    <span class="fas fa-fw fa-caret-down"></span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <span class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></span> Profile
                    </a>                   
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        <span class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></span> {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>                        
                    </a>
                </div>
            </li>

        </ul>

    </nav>
    <!-- End of Topbar -->
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar accordion pt-4" id="accordionSidebar">            
            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('dashboard')}}">
                    <span class="fas fa-fw fa-tachometer-alt"></span>Customer List
                </a>
            </li>
            <li class="nav-item {{ (request()->is('product')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('product')}}">
                    <span class="fas fa-fw fa-tachometer-alt"></span>Product List
                </a>
            </li>
            <li class="nav-item {{ (request()->is('inventory')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('inventory')}}">
                    <span class="fas fa-fw fa-tachometer-alt"></span>Inventory
                </a>
            </li>
            <li class="nav-item {{ (request()->is('report')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('report')}}">
                    <span class="fas fa-fw fa-tachometer-alt"></span>Report
                </a>
            </li>
            
        </ul>
        <!-- End of Sidebar -->