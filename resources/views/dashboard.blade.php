@extends('layouts.adminApp')
@section('content')
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column pt-4">
<!-- Main Content -->
<div id="content">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="mb-2 title-page">Customer List</h1>        
        <div class="card shadow mb-4">            
            <div class="card-body">
            <a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-approve-custom" data-target="#addcustomer" >Add Customer</a>'
                <div class="table-responsive">
                <div class="alert alert-success" id="showmsg" style="display:none"></div>
                <div class="alert alert-danger" id="showerrormsg" style="display:none"></div>
                <table id="customerlist" class="booking-table table display">
                <caption></caption>                
                <thead>
                    <tr>                 
                        <th scope="col"> First Name</th>                        
                        <th scope="col"> Last Name </th>                        
                        <th scope="col"> Email ID</th>                        
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
<!-- Modal -->
<div id="addcustomer" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
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
                    <label class="radio">First Name:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <input type="text" class="form-control form-control-dollar" id="first_name" name="first_name" aria-describedby="dollar" placeholder="First name">
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Last Name:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <input type="text" class="form-control form-control-dollar" id="last_name" name="last_name" aria-describedby="dollar" placeholder="Last name">
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Email:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <input type="email" class="form-control form-control-dollar" id="email" name="email" aria-describedby="dollar" placeholder="Email">
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Phone:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <input type="email" class="form-control form-control-dollar" id="phone" name="phone" aria-describedby="dollar" placeholder="Phone">
                    </div>                
                </div>
                <div class="modal-body">
                    <label class="radio">Status:</label>                    
                    <div class="input-group position-relative dollar-control">                    
                        <select name="status" id="status" class="form-control form-control-dollar">
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>                
                </div>
                <div class="modal-footer">
                    <button class="btn button-default-custom btn-deny-custom" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn button-default-custom btn-approve-custom" data-target="#propertyapprove" data-toggle="modal" onclick = "savecustomer(this)">Save</a>
                </div>
                </form>
            </div>
        </div>
</div>
<div id="delete" class="modal fade" role="dialog">
<div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Customer</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
                </div>
                <div class="modal-body">
                <div class="alert alert-danger" id="showerrormsg" style="display:none"></div>    
                <input type="hidden" id="customer_id" name="customer_id">Are you sure?</div>
                <div class="modal-footer">
                    <button class="btn button-default-custom btn-deny-custom" type="button" data-dismiss="modal">No</button>
                    <a class="btn button-default-custom btn-approve-custom" data-toggle="modal" onclick = "customerDelete(this)">Yes</a>
                </div>
            </div>
        </div>
</div>
<script>
    function editCustomer(id) {        
        $('#id').val(id);
       // save customer
       $.ajax({
                type: "POST",
                url: '{{route('getcustomer')}}',            
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
                    $('#first_name').val(data.customer.first_name);
                    $('#last_name').val(data.customer.last_name);
                    $('#email').val(data.customer.email);
                    $('#phone').val(data.customer.phone);
                    $('[name=status]').val( data.customer.status );                  
                }            
                },
                error: function(xhr, status, error) {
                  $( '#showerrormsg' ).text( data.message );
                }
            });
    }

    function customerDelete() {
        var id = $('#customer_id').val();
        $.ajax({
            type: "POST",
            url: '{{route('customerdelete')}}',            
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
                $("#customerlist").DataTable().ajax.reload(null, false );
               
            }            
            },
            error: function(xhr, status, error) {
                $( '#showerrormsg' ).text( data.message );
            }
        }); 
    }

    function deleteCustomer(id) {
        $('#customer_id').val(id);               
    }
    function savecustomer(obj) {
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();      
        var email = $("#email").val();      
        var phone = $("#phone").val();
        var status = $("#status").val();
        var id = $("#id").val(); 
        if (first_name == '') {
            $( '#showerrormsg' ).text( 'Please enter first name' ).show();
        } else if(last_name == '') {
            $( '#showerrormsg' ).text( 'Please enter last name' ).show();
        } else if(email == '') {
            $( '#showerrormsg' ).text( 'Please enter email' ).show();        
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
                url: '{{route('customersave')}}',            
                dataType: "JSON",
                headers: {
                "Authorization": "Bearer {{session()->get('token')}}",
                },
                data: {               
                    'first_name' : first_name,
                    'last_name': last_name,
                    'email' : email,
                    'phone' : phone,
                    'status': status,
                    'id':id                                        
                },
                success: function(data) {
                if(data.success = 'true')
                {   
                  $( '#showmsg' ).text( data.message ).show().delay(1000).fadeOut();                  
                  $("#addcustomer").modal("hide");
                  $("#customerlist").DataTable().ajax.reload(null, false );
                }            
                },
                error: function(xhr, status, error) {
                  $( '#showerrormsg' ).text( data.message );
                }
            });
        }           
    }
                    
    
        $(document).ready(function() {
            customerlist();
            var dataTable;
            function customerlist(){                
                $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                dataTable = $('#customerlist').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    aLengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    iDisplayLength: 10,
                    ajax: {
                        url: '{{route('customer-listing')}}',
                        method: 'POST'                        
                    },                   
                    columns: [                        
                        { "data": "first_name", "name": "First Name"},
                        { "data": "last_name", "name": "Last name"}, 
                        { "data": "email", "name": "Email"},                                            
                        { "data": "status",  "name": "status"},
                        { "data": "action",  "name": "action", "className":'action-control'},                       
                    ],
                    pageLength: 10,
                });
            }
            $("#searchbox").keyup(function() {
                $('#customerlist').dataTable().fnFilter(this.value);
            });
            

            
        });

    </script>
@endsection