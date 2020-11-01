<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\Customer;
use App\Product;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
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
        if($request->ajax())
        {
            try {
                $customerId = '';
                $postDataArray = json_decode($request->data,true);
                $fromDate = Carbon::parse($postDataArray['fromDate'])->format('Y-m-d');
                $toDate = Carbon::parse($postDataArray['toDate'])->format('Y-m-d');
                $customerId = $postDataArray['customer_id'];

                $inventoryList = DB::table('tbl_inventory')
                ->join('tbl_customer as customer','customer.id','=','tbl_inventory.customer_id')
                ->join('tbl_product as product','product.id', '=','tbl_inventory.product_id')
                ->select('tbl_inventory.*','customer.first_name','customer.last_name','product.product_name')
                ->when (!empty($customerId) , function ($query) use($customerId){
                    return $query->where('tbl_inventory.customer_id',$customerId);
                    })
                ->whereBetween('transaction_date', [$fromDate, $toDate])
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
                    return $inventoryList->payment_mode;
                })
                ->editColumn('total', function ($inventoryList) {
                    return $inventoryList->total;
                })

                ->editColumn('balance', function ($inventoryList) {
                    return ($inventoryList->total - $inventoryList->balance);
                })
                ->rawColumns(['id', 'customer_name', 'product_name', 'qty', 'total', 'balance', 'price','payment_mode'])
                ->make(true);
            } catch (\Throwable $th) {
                return response()->json(['success'=>false,'error'=>$th->getMessage()]);
            }
        }
        return view('report', compact('customers'));
    }

    public function history(Request $request)
    {
        $customers = Customer::select('id', 'first_name', 'last_name')->where('status', 'approved')->get();
        if($request->ajax())
        {
            try {
                $customerId = '';
                $postDataArray = json_decode($request->data,true);
                $fromDate = Carbon::parse($postDataArray['fromDate'])->format('Y-m-d');
                $toDate = Carbon::parse($postDataArray['toDate'])->format('Y-m-d');
                $customerId = $postDataArray['customer_id'];

                $inventoryList = DB::table('tbl_inventory')
                ->leftJoin('tbl_transaction', 'tbl_transaction.inventory_id', '=', 'tbl_inventory.id','right outer')
                ->join('tbl_customer as customer','customer.id','=','tbl_inventory.customer_id')
                ->join('tbl_product as product','product.id', '=','tbl_inventory.product_id')
                ->select('tbl_inventory.*','customer.first_name','customer.last_name','product.product_name')
                ->when (!empty($customerId) , function ($query) use($customerId){
                    return $query->where('tbl_inventory.customer_id',$customerId);
                    })
                ->whereBetween('transaction_date', [$fromDate, $toDate])
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
                    return $inventoryList->payment_mode;
                })
                ->editColumn('total', function ($inventoryList) {
                    return $inventoryList->total;
                })

                ->editColumn('balance', function ($inventoryList) {
                    return ($inventoryList->total - $inventoryList->balance);
                })
                ->rawColumns(['id', 'customer_name', 'product_name', 'qty', 'total', 'balance', 'price','payment_mode'])
                ->make(true);
            } catch (\Throwable $th) {
                return response()->json(['success'=>false,'error'=>$th->getMessage()]);
            }
        }
        return view('history', compact('customers'));
    }  



}