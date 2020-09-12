@extends('layouts.adminApp')
@section('content')
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column pt-4">
<!-- Main Content -->
<div id="content">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="mb-2 title-page">Customer List</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vestibulum, dui eget sagittis euismod, metus nulla volutpat ipsum, sit amet eleifend augue metus id nisl. Nulla luctus metus id nibh mollis maximus.</p>
        <div class="card shadow mb-4">            
            <div class="card-body">
                <div class="table-responsive">
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
<script>
                    
    
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