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
                    ->addColumn('action', function ($productList) {                       
                            $status = '<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-approve-custom" data-target="#addproduct" onclick ="editProduct('.$productList->id.')" >Edit</a>';
                            return $status .= '&nbsp;&nbsp;<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-deny-custom" data-target="#delete" onclick = "deleteProduct('.$productList->id.')">Delete</a>';                        
                    })
                    ->rawColumns(['id','product_name', 'price','status','action'])
                    ->make(true);
                            } catch (\Throwable $th) {
                                return response()->json(['success'=>false,'error'=>$th->getMessage()]);
                            }
                        
                        
        }
        return view('productlist');    
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
