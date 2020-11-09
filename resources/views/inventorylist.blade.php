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
                        <th scope="col"> SrNo</th>
                        <th scope="col"> Customer Name</th>                 
                        <th scope="col"> Product Name</th>
                        <th scope="col"> Quantity</th>                        
                        <th scope="col"> Price</th>
                        <th scope="col"> Total</th>
                        <th scope="col"> Credit Balance</th>
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
                <input type="hidden" id="status_return" name="status_return">
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
                        <select name="productId" id="productId" onchange="getprice()" class="form-control" style="width:350px">
                        <option value="">--- Select Product ---</option>
                        @foreach ($products as $key => $product)
                            <option value="{!! $product->id.','.$product->price!!}">{{ $product->product_name }}</option>
                           
                        @endforeach
                        </select>
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Date:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                    <input type="text" class="form-control" name="transactiondate" id="transactiondate">                    
                    </div>                
                </div> 
                <div class="modal-body">
                    <label class="radio">Quantity:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                    <select id="qty" name="qty" onchange="getQty()" class="form-control "> 
                        @for ($i = 0; $i <= 500; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Rate:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                    <input type="text" readonly class="form-control form-control-dollar" id="rate" name="rate" aria-describedby="dollar" placeholder="Rate"> 
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Total Amount:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                    <input type="text" readonly class="form-control form-control-dollar" id="totalAmount" name="totalAmount" aria-describedby="dollar" placeholder="Total Amount"> 
                    </div>                
                </div>                               
                <div class="modal-body">
                    <label class="radio">Payment Mode:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <select name="payment_mode" id="payment_mode" class="form-control form-control-dollar">                           
                            <option value="credit">Credit</option>
                            <option value="cash">Cash</option>                          
                        </select>
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
<div id="returninventory" class="modal fade" role="dialog">
<div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Return Product</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
                </div>
                <div class="modal-body">
                <div class="alert alert-danger" id="showerrormsg" style="display:none"></div>    
                <input type="hidden" id="inventory_id" name="inventory_id">Are you sure?
                <input type="hidden" id="totalAmount" name="totalAmount">
                </div>
                <div class="modal-footer">
                    <button class="btn button-default-custom btn-deny-custom" type="button" data-dismiss="modal">No</button>
                    <a class="btn button-default-custom btn-approve-custom" data-toggle="modal" onclick = "inventoryDelete(this)">Yes</a>
                </div>
            </div>
        </div>
</div>

<!-- Modal -->
<div id="creditinventory" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cash Payment</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="alert alert-success" id="showmsg" style="display:none"></div>
                <div class="alert alert-danger" id="showerrortxt" style="display:none"></div>
                <form class="user">
                @csrf
                <input type="hidden" id="inventory_id" name="inventory_id">
                <input type="hidden" id="status_return" name="status_return">               
                
                <div class="modal-body">
                    <label class="radio">CID: <span id="cid"></span></label>                        
                    <label class="radio"> Customer Name: <span id="name"></span></label>
                </div>                
               
                <div class="modal-body">
                    <label class="radio">Amount Paid:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                    <input type="number" class="form-control form-control-dollar" id="totalAmountpaid" name="totalAmountpaid" aria-describedby="dollar" placeholder="Amount Paid"> 
                    </div>                
                </div>                               
                          
                <div class="modal-footer">
                    <button class="btn button-default-custom btn-deny-custom" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn button-default-custom btn-approve-custom" onclick = "AddInventoryPayment(this)">Save</a>
                </div>
                </form>
            </div>
        </div>
</div>

<script>
$(document).ready(function () {
    var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            $('#transactiondate').datepicker({
                format: "dd/mm/yyyy",
                autoclose: true,
                orientation: 'bottom'  
            });
            $('#transactiondate').datepicker('setDate', today);
        }); 
    function getprice() {
        var productval = $('#productId').val();
        var productPrice = productval.split(",");
        //alert('value' + productPrice[1]);
        $('#rate').val(productPrice[1]);
    }

    function getQty(){
        var qty = $('#qty').val();  
        var rate = $('#rate').val();
        var total = qty * rate;
        $('#totalAmount').val(total);
    }

    function CreditInventory(id) {        
        $('#inventory_id').val(id);
    }
    function editInventory(id, status) {        
        $('#inventory_id').val(id);
        $('#status_return').val(status);
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
                    $('#productId').val(data.inventory.product_id+","+data.inventory.price);
                    $('#qty').val(data.inventory.qty);
                    $('#customer_id').val(data.inventory.customer_id);
                    $('#payment').val(data.inventory.amount_paid);
                    if(status) {
                        $('#payment_mode').val('debit');
                    } else {
                        $('#payment_mode').val(data.inventory.payment_mode);
                    }                    
                    $('#rate').val(data.inventory.rate);
                    $('#totalAmount').val(data.inventory.total);                   
                                 
                }            
                },
                error: function(xhr, status, error) {
                  $( '#showerrormsg' ).text( data.message );
                }
            });
    }

    function addInventory(id,status) {        
        $('#inventory_id').val(id);
        $('#status_return').val(status);
       // save customer
       $.ajax({
                type: "POST",
                url: '{{route('getinventory')}}',            
                dataType: "JSON",
                headers: {
                "Authorization": "Bearer {{session()->get('token')}}",
                },
                data: {               
                    'id' : id,
                    'status':status                                                
                },
                success: function(data) {
                if(data.success = 'true')
                {                       
                    $('#productId').val(data.inventory.product_id+","+data.inventory.price);
                    $('#qty').val(data.inventory.qty);
                    $('#customer_id').val(data.inventory.customer_id);
                    $('#payment').val(data.inventory.amount_paid);
                    $('#payment_mode').val(data.inventory.payment_mode);
                    $('#rate').val(data.inventory.rate);
                    $('#totalAmount').val(data.inventory.total);                   
                                 
                }            
                },
                error: function(xhr, status, error) {
                  $( '#showerrormsg' ).text( data.message );
                }
            });
    }

    function inventoryDelete() {
        var id = $('#inventory_id').val();
        var totalAmount = $('#totalAmount').val();
        $.ajax({
            type: "POST",
            url: '{{route('inventorydelete')}}',            
            dataType: "JSON",
            headers: {
            "Authorization": "Bearer {{session()->get('token')}}",
            },
            data: {               
                'id' : id,
                'totalAmount':totalAmount
            },
            success: function(data) {
            if(data.success = 'true')
            {
                $('#showmsg' ).text( data.message ).show().delay(1000).fadeOut();                  
                $("#returninventory").modal("hide");
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
        var product_id = $("#productId").val();
        var qty = $("#qty").val();
        var payment = $("#payment").val();
        var payment_mode = $("#payment_mode").val();
        var transactionDate = $('#transactiondate').val();
        var rate = $('#rate').val();
        var id = $("#inventory_id").val();
        var status_return = $('#status_return').val(); 
        if (customer_id == '') {
            $( '#showerror' ).text( 'Please select customer' ).show();
        } else if(product_id == '') {
            $( '#showerror' ).text( 'Please select product' ).show();
        } else if(qty == 0) {
            $( '#showerror' ).text( 'Please select quantity' ).show();              
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
                    'id':id,
                    'transactiondate':transactionDate,
                    'rate':rate,
                    'status_return':status_return                                        
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
    function AddInventoryPayment(obj) {
       
        var id = $("#inventory_id").val();
        var totalAmount = $('#totalAmountpaid').val(); 
        alert(totalAmount);       
        if (totalAmount == '') {
            $( '#showerrortxt' ).text( 'Please enter amount' ).show();                      
        } else {
            $( '#showerrortxt').hide();
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
                    'id':id,
                    'amount':totalAmount                                                          
                },
                success: function(data) {
                if(data.success = 'true')
                {   
                    $('#showmsg').text( data.message ).show().delay(1000).fadeOut();                    
                    $("#totalAmount").val('');
                    $("#inventory_id").val('');                  
                    $("#creditinventory").modal("hide");
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
                serverSide: false,
                order: [],
                aLengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                iDisplayLength: 10,
                ajax: {
                    url: '{{route('inventory-listing')}}',
                    method: 'POST'                        
                },                   
                columns: [
                    { "data": "id", "name": "Sr No"},
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

        $('#inventorylist').on('click', '.createinventory', function (e) {
            e.preventDefault();
            let cid = $(this).data("id");
            let name = $(this).attr('data-name');       
            $( '#cid' ).text( cid );
            $( '#name' ).text( name );
            $('#creditinventory').modal('show');
        });
    });
</script>
@endsection