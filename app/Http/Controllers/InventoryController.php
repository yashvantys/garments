<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;

class InventoryController extends Controller
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
                $inventoryList = Inventory::getInventoryData();    
                           
                    return datatables()->of($inventoryList)
                    ->editColumn('id', function ($inventoryList) {
                        return $inventoryList->id;
                    })    
                    
                    ->addColumn('product_name', function ($inventoryList) {
                        return $inventoryList->product_name;
                    })    
                    
                     ->addColumn('price', function ($inventoryList) {
                        return $inventoryList->price;
                   })
                   
                
                    ->editColumn('status', function ($inventoryList) {
                        if($inventoryList->status=='approve'){
                            $status = 'Approved';
                        } else if($inventoryList->status=='pending'){
                            $status = 'Pending';
                        } else if($inventoryList->status=='decline'){
                            $status = 'Denied';
                        } 
                        return $status;
                    })
                    ->addColumn('action', function ($inventoryList) {                       
                            $status = '<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-approve-custom" data-target="#addproduct" onclick ="editProduct('.$inventoryList->id.')" >Edit</a>';
                            return $status .= '&nbsp;&nbsp;<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-deny-custom" data-target="#delete" onclick = "deleteProduct('.$inventoryList->id.')">Delete</a>';                        
                    })
                    ->rawColumns(['id','product_name', 'price','status','action'])
                    ->make(true);
                            } catch (\Throwable $th) {
                                return response()->json(['success'=>false,'error'=>$th->getMessage()]);
                            }
                        
                        
        }
        return view('inventoryList');    
    }

    public function saveProduct(Request $request) {
        try {            
            if ($request->id) {
                $product = [
                    'product_name' => $request->product_name,
                    'price' => $request->price,                   
                    'status' => $request->status
                ];              
                Product::where('id',$request->id)->update($product);                
            } else {
                $product = new Product([
                    'product_name' => $request->product_name,
                    'price' => $request->price,                   
                    'status' => $request->status
                ]);
                $product->save();
            }           
           
            return response()->json(['success'=>true,'message'=>'Product saved successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'error'=>$th->getMessage()]);
        } 
    }

    public function getproduct(Request $request) {
        $id = $request->id;
        try {
            $findProduct = Product::where('id', $id)->first();           
            return response()->json(['success'=>true,'product'=>$findProduct]);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'error'=>$th->getMessage()]);
        }
    }

    public function deleteProduct(Request $request) {
        $id = $request->id;
        try {           
            $customer = Product::find($id);
            $customer->delete();           
            return response()->json(['success'=>true]);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'error'=>$th->getMessage()]);
        }
    }
    
}
