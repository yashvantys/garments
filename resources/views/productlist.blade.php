@extends('layouts.adminApp')
@section('content')
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column pt-4">
<!-- Main Content -->
<div id="content">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="mb-2 title-page">Product List</h1>        
        <div class="card shadow mb-4">            
            <div class="card-body">
            <a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-approve-custom" data-target="#addproduct" >Add Product</a>'
                <div class="table-responsive">
                <table id="productlist" class="booking-table table display">
                <caption></caption>                
                <thead>
                    <tr>                 
                        <th scope="col"> Product Name</th>                        
                        <th scope="col"> Price</th>
                        <th scope="col"> Status</th>
                        <th scope="col"> Action</th>                        
                    </tr>
                    </thead>
                </table>
                </div>
            </div>
        </div>     
        
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        productlist();
        var dataTable;
        function productlist(){                
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            dataTable = $('#productlist').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                aLengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                iDisplayLength: 10,
                ajax: {
                    url: '{{route('product-listing')}}',
                    method: 'POST'                        
                },                   
                columns: [                        
                    { "data": "product_name", "name": "Product Name"},
                    { "data": "price", "name": "Price"},                                                                  
                    { "data": "status",  "name": "status"},
                    { "data": "action",  "name": "action", "className":'action-control'},                       
                ],
                pageLength: 10,
            });
        }
        $("#searchbox").keyup(function() {
            $('#productlist').dataTable().fnFilter(this.value);
        });
    });
</script>
@endsection