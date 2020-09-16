<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class Inventory extends Authenticatable {
    use Notifiable;
    
    protected $table = 'tbl_inventory';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id','product_id', 'qty','price', 'total', 'payment_mode', 'balance','status', '# id, customer_id, product_id, qty, price, total, payment_mode, balance, status, created_at, updated_at'
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */   
    
    public static function getInventoryData(){
        return DB::table('tbl_inventory')->orderBy('id', 'asc')->get();
       
    }

    public function customer()
    {        
        return $this->hasOne(Customer::class,'id', 'customer_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    
}
