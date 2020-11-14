@extends('layouts.adminApp')
@section('content')
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column pt-4">
<!-- Main Content -->
<div id="content">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="mb-2 title-page">Report</h1>        
        <div class="card shadow mb-4">            
            <div class="card-body">
                <form id="report-search-form" name="report-search-form" class="" style="margin-bottom:20px;">
                
  <div class="row">
    <div class="col-md-3">
    <label class="radio">Customer:</label>
    <select name="customer_id" id="customer_id" class="form-control">
                        <option value="">--- Select Customer ---</option>
                        @foreach ($customers as $key => $customer)
                            <option value="{{ $customer->id }}">{{ $customer->id .':-'.$customer->first_name .' ' .$customer->last_name }}</option>
                        @endforeach
                        </select>
    </div>
    <div class="col-md-2">
    <label class="radio">From Date:</label>    
    <input type="text" class="form-control" name="fromDate" id="fromDate">
       
</div>
    <div class="col-md-2">
    <label class="radio">To Date:</label>  
   <input type="text" class="form-control" name="toDate" id="toDate"> 
    </div>
    <div class="col-md-2">
    <label class="radio"></label> 
        <input type="button" class="btn btn-primary" id="search" name="search" value="Search" style="margin-top:25px">
    </div>

</div>
                               
                  
                </form>
                <div class="row"></div>            
                <div class="table-responsive">
                <div class="alert alert-success" id="showmsg" style="display:none"></div>
                <div class="alert alert-danger" id="showerrormsg" style="display:none"></div>
                <table id="report" class="booking-table table display">
                <caption></caption>                
                <thead>
                    <tr>
                        <th scope="col"> Customer ID</th>
                        <th scope="col"> Customer Name</th>                 
                        <th scope="col"> Product Name</th>
                        <th scope="col"> Quantity</th>                        
                        <th scope="col"> Price</th>
                        <th scope="col"> Total</th>
                        <th scope="col"> Balance</th>
                        <th scope="col"> Payment Mode</th>                                             
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th scope="col" colspan="4" style="text-align:right">Total:</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </tfoot>
                </table>
                </div>
            </div>
        </div>     
        
    </div>
</div>
</div>

<script>
$(document).ready(function () {
    var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        var pastMonth = new Date(date.getFullYear(), date.getMonth() -1, date.getDate());
            $('#fromDate').datepicker({
                format: "yyyy/mm/dd",
                autoclose: true,
                orientation: 'bottom'  
            });
            $('#fromDate').datepicker('setDate', pastMonth);
            $('#toDate').datepicker({
                format: "yyyy/mm/dd",
                autoclose: true,
                orientation: 'bottom'  
            });
            $('#toDate').datepicker('setDate', today);
        }); 

                
    $(document).ready(function() {
        $.fn.serializeObject = function()
			{
			    var o = {};
			    var a = this.serializeArray();
			    $.each(a, function() {
			        if (o[this.name] !== undefined) {
			            if (!o[this.name].push) {
			                o[this.name] = [o[this.name]];
			            }
			            o[this.name].push(this.value || '');
			        } else {
			            o[this.name] = this.value || '';
			        }
			    });
			    return o;
            };
            inventorylist();// default list
        $('#search').click(function( event ) {
            inventorylist();
        });
        
        var dataTable;
        function inventorylist(){                
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            dataTable = $('#report').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                destroy: true,
                aLengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                iDisplayLength: 10,
                "bLengthChange": false,
                "bFilter": false,
                "language": {                
                    "info": "Showing page _PAGE_ of _PAGES_",
                },
                ajax: {
                    url: '{{route('report-listing')}}',
                    method: 'POST',
                    data: {
                        data:  JSON.stringify($('#report-search-form').serializeObject())
                    },                        
                },
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
        
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    }; 
                    // Total over all pages
                    total = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
        
                    // Total over this page
                    pageTotal = api
                        .column( 4, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
        
                    // Update footer
                    $( api.column( 4 ).footer() ).html(
                        'Rs:'+pageTotal 
                    );
                },                   
                columns: [
                    { "data": "custid", "name": "Customer ID"},
                    { "data": "customer_name", "name": "Customer Name"},                        
                    { "data": "product_name", "name": "Product Name"},
                    { "data": "qty", "name": "Quantity"}, 
                    { "data": "price", "name": "Price"},
                    { "data": "total", "name": "Total"},
                    { "data": "balance", "name": "Balance"},                                                                   
                    { "data": "payment_mode",  "name": "Payment Mode"}                                          
                ],
                pageLength: 10,
                
            });
        }
        $("#searchbox").keyup(function() {
            $('#report').dataTable().fnFilter(this.value);
        });
    });
</script>
@endsection