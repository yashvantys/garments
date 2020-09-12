<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class Product extends Authenticatable {
    use Notifiable;
    
    protected $table = 'tbl_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name','price', 'status', '# id, product_name, price, status, created_at, updated_at'
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    public static function getProductData(){
        return DB::table('tbl_product')->orderBy('id', 'asc')->get();
       
    }
    

    
}
