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
                $inventoryList = Inventory::with(['customer','product'])                
                ->orderBy('id','desc')
                ->get();
                return datatables()->of($inventoryList)
                ->editColumn('id', function ($inventoryList) {
                    return $inventoryList->id;
                })                
                ->addColumn('customer_name', function ($inventoryList) {
                    return $inventoryList->customer->first_name . ' '. $inventoryList->customer->last_name;
                })                
                ->addColumn('product_name', function ($inventoryList) {
                    return $inventoryList->product->product_name;
                })                
                ->addColumn('qty', function ($inventoryList) {
                    return $inventoryList->qty;
                })                
                ->addColumn('price', function ($inventoryList) {
                    return $inventoryList->price;
                })
                ->editColumn('payment_mode', function ($inventoryList) {                       
                    return $inventoryList->payment_mode;
                })
                ->editColumn('total', function ($inventoryList) {                       
                    return $inventoryList->total;
                })

                ->editColumn('balance', function ($inventoryList) {                       
                    return $inventoryList->balance;
                })
                ->addColumn('action', function ($inventoryList) {                       
                    $action = '<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-approve-custom" data-target="#addinventory" onclick ="editInventory('.$inventoryList->id.')" >Edit</a>';
                    return $action .= '&nbsp;&nbsp;<a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom btn-deny-custom" data-target="#delete" onclick = "deleteInventory('.$inventoryList->id.')">Delete</a>';                        
                })
                ->rawColumns(['id', 'customer_name', 'product_name', 'qty', 'total', 'balance', 'price','payment_mode','action'])
                ->make(true);
            } catch (\Throwable $th) {
                return response()->json(['success'=>false,'error'=>$th->getMessage()]);
            }           
        }
        return view('inventorylist');    
    }

    public function saveInventory(Request $request) {
        try {            
            if ($request->id) {
                $inventory = [
                    'customer_id' => $request->customer_id,
                    'product_id' => $request->product_id,
                    'qty' =>$request->qty,                    
                    'price' => $request->price
                ];              
                Inventory::where('id',$request->id)->update($inventory);                
            } else {
                $inventory = new Inventory([
                    'customer_id' => $request->customer_id,
                    'product_id' => $request->product_id,
                    'qty' =>$request->qty,                    
                    'price' => $request->price
                ]);
                $inventory->save();
            }           
           
            return response()->json(['success'=>true,'message'=>'Product saved successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'error'=>$th->getMessage()]);
        } 
    }

    public function getproduct(Request $request) {
        $id = $request->id;
        try {
            $findInventory = Inventory::where('id', $id)->first();           
            return response()->json(['success'=>true,'product'=>$findInventory]);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'error'=>$th->getMessage()]);
        }
    }

    public function deleteInventory(Request $request) {
        $id = $request->id;
        try {           
            $inventory = Inventory::find($id);
            $inventory->delete();           
            return response()->json(['success'=>true]);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'error'=>$th->getMessage()]);
        }
    }
    
}
