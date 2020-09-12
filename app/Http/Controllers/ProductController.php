<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;

class ProductController extends Controller
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
                $productList = Product::getProductData();          
                           
                    return datatables()->of($productList)
                    ->editColumn('id', function ($productList) {
                        return $productList->id;
                    })    
                    
                    ->addColumn('product_name', function ($productList) {
                        return $productList->product_name;
                    })    
                    
                     ->addColumn('price', function ($productList) {
                        return $productList->price;
                   })
                   
                
                    ->editColumn('status', function ($productList) {
                        if($productList->status=='approve'){
                            $status = 'Approved';
                        } else if($productList->status=='pending'){
                            $status = 'Pending';
                        } else if($productList->status=='decline'){
                            $status = 'Denied';
                        } 
                        return $status;
                    })
                    ->addColumn('action', function () {                       
                            $status = '<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-approve-custom" data-target="#approve" >Edit</a>';
                            return $status .= '&nbsp;&nbsp;<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-deny-custom" data-target="#deny">Delete</a>';                        
                    })
                    ->rawColumns(['id','product_name', 'price','status','action'])
                    ->make(true);
                            } catch (\Throwable $th) {
                                return response()->json(['success'=>false,'error'=>$th->getMessage()]);
                            }
                        
                        
        }
        return view('productlist');
    
}
    
}
