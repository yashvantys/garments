@extends('layouts.adminApp')
@section('content')
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column pt-4">
<!-- Main Content -->
<div id="content">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="mb-2 title-page">Inventory List</h1>        
        <div class="card shadow mb-4">            
            <div class="card-body">
            <a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-approve-custom" data-target="#addinventory" >Add Inventory</a>'
                <div class="table-responsive">
                <div class="alert alert-success" id="showmsg" style="display:none"></div>
                <div class="alert alert-danger" id="showerrormsg" style="display:none"></div>
                <table id="inventorylist" class="booking-table table display">
                <caption></caption>                
                <thead>
                    <tr>
                        <th scope="col"> Customer Name</th>                 
                        <th scope="col"> Product Name</th>
                        <th scope="col"> Quantity</th>                        
                        <th scope="col"> Price</th>
                        <th scope="col"> Total</th>
                        <th scope="col"> Balance</th>
                        <th scope="col"> Payment Mode</th>
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
<!-- Modal -->
<div id="addinventory" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Inventory</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="alert alert-success" id="showmsg" style="display:none"></div>
                <div class="alert alert-danger" id="showerrormsg" style="display:none"></div>
                <form class="user">
                @csrf
                <input type="hidden" id="id" name="id">
                <div class="modal-body">
                    <label class="radio">Customer Name:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Product Name:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Quantity:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                    <input type="text" class="form-control form-control-dollar" id="price" name="price" aria-describedby="dollar" placeholder="Quantity"> 
                    </div>                
                </div>                              
                <div class="modal-body">
                    <label class="radio">Payment Mode:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <select name="status" id="status" class="form-control form-control-dollar">
                            <option value="approve">Approved</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Payment:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <input type="text" class="form-control form-control-dollar" id="price" name="price" aria-describedby="dollar" placeholder="Price">
                    </div>                
                </div>  
                <div class="modal-footer">
                    <button class="btn button-default-custom btn-deny-custom" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn button-default-custom btn-approve-custom" data-target="#propertyapprove" data-toggle="modal" onclick = "saveproduct(this)">Save</a>
                </div>
                </form>
            </div>
        </div>
</div>
<div id="delete" class="modal fade" role="dialog">
<div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
                </div>
                <div class="modal-body">
                <div class="alert alert-danger" id="showerrormsg" style="display:none"></div>    
                <input type="hidden" id="product_id" name="product_id">Are you sure?</div>
                <div class="modal-footer">
                    <button class="btn button-default-custom btn-deny-custom" type="button" data-dismiss="modal">No</button>
                    <a class="btn button-default-custom btn-approve-custom" data-toggle="modal" onclick = "productDelete(this)">Yes</a>
                </div>
            </div>
        </div>
</div>
<script>
    function editInventory(id) {        
        $('#id').val(id);
       // save customer
       $.ajax({
                type: "POST",
                url: '{{route('getinventory')}}',            
                dataType: "JSON",
                headers: {
                "Authorization": "Bearer {{session()->get('token')}}",
                },
                data: {               
                    'id' : id                                                 
                },
                success: function(data) {
                if(data.success = 'true')
                {   
                    $('#product_name').val(data.product.product_name);
                    $('#price').val(data.product.price);                   
                    $('[name=status]').val( data.product.status );                  
                }            
                },
                error: function(xhr, status, error) {
                  $( '#showerrormsg' ).text( data.message );
                }
            });
    }

    function inventoryDelete() {
        var id = $('#product_id').val();
        $.ajax({
            type: "POST",
            url: '{{route('inventorydelete')}}',            
            dataType: "JSON",
            headers: {
            "Authorization": "Bearer {{session()->get('token')}}",
            },
            data: {               
                'id' : id
            },
            success: function(data) {
            if(data.success = 'true')
            {
                $('#showmsg' ).text( data.message ).show().delay(1000).fadeOut();                  
                $("#delete").modal("hide");
                $("#inventorylist").DataTable().ajax.reload(null, false );
               
            }            
            },
            error: function(xhr, status, error) {
                $( '#showerrormsg' ).text( data.message );
            }
        }); 
    }

    function deleteInventory(id) {
        $('#product_id').val(id);               
    }
    function saveinventory(obj) {
        var product_name = $("#product_name").val();
        var price = $("#price").val();
        var status = $("#status").val();
        var id = $("#id").val(); 
        if (product_name == '') {
            $( '#showerrormsg' ).text( 'Please enter product name' ).show();
        } else if(price == '') {
            $( '#showerrormsg' ).text( 'Please enter price' ).show();              
        } else {
            $( '#showerrormsg').hide();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // save customer
            $.ajax({
                type: "POST",
                url: '{{route('inventorysave')}}',            
                dataType: "JSON",
                headers: {
                "Authorization": "Bearer {{session()->get('token')}}",
                },
                data: {               
                    'product_name' : product_name,
                    'price': price,                    
                    'status': status,
                    'id':id                                        
                },
                success: function(data) {
                if(data.success = 'true')
                {   
                  $('#showmsg').text( data.message ).show().delay(1000).fadeOut();                  
                  $("#addproduct").modal("hide");
                  $("#inventorylist").DataTable().ajax.reload(null, false );
                }            
                },
                error: function(xhr, status, error) {
                  $( '#showerrormsg' ).text( data.message );
                }
            });
        }           
    }
    $(document).ready(function() {
        inventorylist();
        var dataTable;
        function inventorylist(){                
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            dataTable = $('#inventorylist').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                aLengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                iDisplayLength: 10,
                ajax: {
                    url: '{{route('inventory-listing')}}',
                    method: 'POST'                        
                },                   
                columns: [
                    { "data": "customer_name", "name": "Customer Name"},                        
                    { "data": "product_name", "name": "Product Name"},
                    { "data": "qty", "name": "Quantity"}, 
                    { "data": "price", "name": "Price"},
                    { "data": "total", "name": "Total"},
                    { "data": "balance", "name": "Balance"},                                                                   
                    { "data": "payment_mode",  "name": "Payment Mode"},
                    { "data": "action",  "name": "action", "className":'action-control'},                       
                ],
                pageLength: 10,
            });
        }
        $("#searchbox").keyup(function() {
            $('#inventorylist').dataTable().fnFilter(this.value);
        });
    });
</script>
@endsection