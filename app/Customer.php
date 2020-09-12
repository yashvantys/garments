<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class Customer extends Authenticatable {
    use Notifiable;
    
    protected $table = 'tbl_customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'status', '# id, first_name, last_name, email, status, created_at, updated_at'
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    public static function getCustomerData(){
        return DB::table('tbl_customer')->orderBy('id', 'asc')->get();
       
    }
    

    
}
