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
                    <h5 class="modal-title" id="exampleModalLabel">Add/Edit Inventory</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="alert alert-success" id="showmsg" style="display:none"></div>
                <div class="alert alert-danger" id="showerror" style="display:none"></div>
                <form class="user">
                @csrf
                <input type="hidden" id="inventory_id" name="inventory_id">
                <div class="modal-body">
                    <label class="radio">Customer Name:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <select name="customer_id" id="customer_id" class="form-control" style="width:350px">
                        <option value="">--- Select Customer ---</option>
                        @foreach ($customers as $key => $customer)
                            <option value="{{ $customer->id }}">{{ $customer->first_name .' ' .$customer->last_name }}</option>
                        @endforeach
                        </select>
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Product Name:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <select name="product_id" id="product_id" class="form-control" style="width:350px">
                        <option value="">--- Select Product ---</option>
                        @foreach ($products as $key => $product)
                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                        @endforeach
                        </select>
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Quantity:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                    <input type="text" class="form-control form-control-dollar" id="qty" name="qty" aria-describedby="dollar" placeholder="Quantity"> 
                    </div>                
                </div>                              
                <div class="modal-body">
                    <label class="radio">Payment Mode:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <select name="payment_mode" id="payment_mode" class="form-control form-control-dollar">
                            <option value="cash">Cash</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Payment:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <input type="text" class="form-control form-control-dollar" id="payment" name="payment" aria-describedby="dollar" placeholder="Payment">
                    </div>                
                </div>  
                <div class="modal-footer">
                    <button class="btn button-default-custom btn-deny-custom" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn button-default-custom btn-approve-custom" data-target="#propertyapprove" data-toggle="modal" onclick = "saveInventory(this)">Save</a>
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
        $('#inventory_id').val(id);
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
                    $('#product_id').val(data.inventory.product_id);
                    $('#qty').val(data.inventory.qty);
                    $('#customer_id').val(data.inventory.customer_id);
                    $('#payment').val(data.inventory.amount_paid);
                    $('#payment_mode').val(data.inventory.payment_mode);                   
                                 
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
    function saveInventory(obj) {
        var customer_id = $("#customer_id").val();
        var product_id = $("#product_id").val();
        var qty = $("#qty").val();
        var payment = $("#payment").val();
        var payment_mode = $("#payment_mode").val();
        var id = $("#inventory_id").val(); 
        if (customer_id == '') {
            $( '#showerror' ).text( 'Please select customer' ).show();
        } else if(product_id == '') {
            $( '#showerror' ).text( 'Please select product' ).show();
        } else if(qty == '') {
            $( '#showerror' ).text( 'Please enter quantity' ).show();              
        } else {
            $( '#showerror').hide();
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
                    'product_id' : product_id,
                    'customer_id': customer_id,
                    'qty' : qty,
                    'payment_mode': payment_mode,
                    'amount_paid': payment,
                    'id':id                                        
                },
                success: function(data) {
                if(data.success = 'true')
                {   
                    $('#showmsg').text( data.message ).show().delay(1000).fadeOut();
                    $("#customer_id").val('');
                    $("#product_id").val('');
                    $("#qty").val('');
                    $("#payment").val('');
                    $("#payment_mode").val('');
                    $("#inventory_id").val('');                  
                    $("#addinventory").modal("hide");
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