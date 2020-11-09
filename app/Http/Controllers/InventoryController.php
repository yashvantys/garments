<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\Customer;
use App\Product;
use App\Transaction;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use DB;

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
        $customers = Customer::select('id', 'first_name', 'last_name')->where('status', 'approved')->get();
        $products = Product::select('id', 'product_name','price')->where('status', 'approve')->get();

        if($request->ajax())
        {
            try {

                $inventoryList = DB::table('tbl_inventory')
                ->join('tbl_transaction', 'tbl_transaction.inventory_id', '=', 'tbl_inventory.id')
                ->join('tbl_customer as customer','customer.id','=','tbl_inventory.customer_id')
                ->join('tbl_product as product','product.id', '=','tbl_inventory.product_id')
                ->select('tbl_transaction.amount as paid','tbl_transaction.status as tstatus','tbl_inventory.*','customer.first_name','customer.last_name','product.product_name','customer.id as cid')
                ->orderBy('id','desc')
                ->get();

               
                return datatables()->of($inventoryList)
                ->editColumn('id', function ($inventoryList) {
                    return $inventoryList->id;
                })
                ->addColumn('customer_name', function ($inventoryList) {
                    return $inventoryList->first_name . ' '. $inventoryList->last_name;
                })
                ->addColumn('product_name', function ($inventoryList) {
                    return $inventoryList->product_name;
                })
                ->addColumn('qty', function ($inventoryList) {
                    return $inventoryList->qty;
                })
                ->addColumn('price', function ($inventoryList) {
                    return $inventoryList->price;
                })
                ->editColumn('payment_mode', function ($inventoryList) {                    
                    return $inventoryList->tstatus;
                })
                ->editColumn('total', function ($inventoryList) {
                    return $inventoryList->total;
                })

                ->editColumn('balance', function ($inventoryList) {
                    if ($inventoryList->tstatus == 'payment') {
                        $totalAmount = ($inventoryList->total - $inventoryList->paid);
                    } else {
                        $totalAmount =  ($inventoryList->total - $inventoryList->balance);
                    }
                    return $totalAmount;
                })
                ->addColumn('action', function ($inventoryList) {
                    if ($inventoryList->status !='return') {
                            if ($inventoryList->tstatus != 'payment') {
                                $status = '<div class="action-tool"><a href="javascript:void(0)" data-toggle="modal" class="btn button-default-custom  btn-deny-custom" data-target="#returninventory" onclick ="editInventory('.$inventoryList->id.')">Return</a>';                  
                                $status .= '&nbsp;<a id="view_profile" href="javascript:void(0)" data-toggle="modal" data-id="'.$inventoryList->cid.'"  data-name="'.$inventoryList->first_name . ' '. $inventoryList->last_name.'" class="btn button-default-custom btn-approve-custom createinventory" onclick ="CreditInventory('.$inventoryList->id.')">Cash Payment</a></div>';                   
                            } else {
                                $status =''; 
                            }
                        
                        return $status;  
                    } else if ($inventoryList->status =='return') {
                        return 'Returned';
                    }
                })
                ->rawColumns(['id', 'customer_name', 'product_name', 'qty', 'total', 'balance', 'price','payment_mode','action'])
                ->make(true);
            } catch (\Throwable $th) {
                return response()->json(['success'=>false,'error'=>$th->getMessage()]);
            }
        }
        return view('inventorylist', compact('customers', 'products'));
    }

    public function saveInventory(Request $request) {
        try {
            if ($request->id) {
                $transaction = new Transaction([
                    'inventory_id' => $request->id,                    
                    'amount' => $request->amount,                    
                    'trasaction_date'=>date('Y-m-d'),
                    'status'=>'payment'                    
                ]);
                $transaction->save();
            } else {
                $product = Product::select('price')->where('id', $request->product_id)->first();
                $productId = explode(',', $request->product_id);
                $inventory = new Inventory([
                    'customer_id' => $request->customer_id,
                    'product_id' => $productId[0],
                    'qty' => $request->qty,
                    'price'=> $product->price,
                    'total' => ($product->price * $request->qty),
                    'amount_paid' => $request->amount_paid,
                    'payment_mode' => $request->payment_mode,
                    'transaction_date'=>Carbon::createFromFormat('d/m/Y', $request->transactiondate)->format('Y-m-d'),
                    'rate'=>$request->rate
                ]);
                $inventory->save();
                $transaction = new Transaction([
                    'inventory_id' => $inventory->id,                    
                    'amount' => ($product->price * $request->qty),
                    'status' => $request->payment_mode,
                    'trasaction_date'=>Carbon::createFromFormat('d/m/Y', $request->transactiondate)->format('Y-m-d')                    
                ]);
                $transaction->save();
            }

            return response()->json(['success'=>true,'message'=>'Inventory saved successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'error'=>$th->getMessage()]);
        }
    }

    public function getinventory(Request $request) {
        $id = $request->id;
        try {
            $findInventory = Inventory::where('id', $id)->first();
            return response()->json(['success'=>true,'inventory'=>$findInventory]);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'error'=>$th->getMessage()]);
        }
    }

    public function deleteInventory(Request $request) {
        $id = $request->id;
        try {
            $inventory = Inventory::find($id);
            $inventory->status = 'return';
            $inventory->balance = $request->totalAmount;            
            
            $transaction = new Transaction([
                'inventory_id' => $request->id,                    
                'amount' => $request->totalAmount,
                'status' => 'return',
                'trasaction_date'=> date('Y-m-d')                    
            ]);
            $transaction->save();   
            $inventory->save();         
            return response()->json(['success'=>true,'message' =>'Inventory retured successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'error'=>$th->getMessage()]);
        }
    }

}