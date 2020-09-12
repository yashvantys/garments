<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {             
            try {            
                $customerList = Customer::getCustomerData();          
                           
                    return datatables()->of($customerList)
                    ->editColumn('id', function ($customerList) {
                        return $customerList->id;
                    })    
                    
                    ->addColumn('first_name', function ($customerList) {
                        return $customerList->first_name;
                    })    
                    ->addColumn('last_name', function ($customerList) {
                          return $customerList->last_name;
                     })
                     ->addColumn('email', function ($customerList) {
                        return $customerList->email;
                   })
                   
                
                    ->editColumn('status', function ($customerList) {
                        if($customerList->status=='approved'){
                            $status = 'Approved';
                        } else if($customerList->status=='pending'){
                            $status = 'Pending';
                        } else if($customerList->status=='decline'){
                            $status = 'Denied';
                        } 
                        return $status;
                    })
                    ->addColumn('action', function () {                       
                            $status = '<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-approve-custom" data-target="#approve" >Edit</a>';
                            return $status .= '&nbsp;&nbsp;<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-deny-custom" data-target="#deny">Delete</a>';                        
                    })
                    ->rawColumns(['id','first_name', 'last_name','email', 'status','action'])
                    ->make(true);
                            } catch (\Throwable $th) {
                                return response()->json(['success'=>false,'error'=>$th->getMessage()]);
                            }
                        
                        
        }
        return view('dashboard');
    
}
    
}
