<?php
namespace App\Models;

use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

/**
* 
*/
class Appointment extends Model
{
    
     protected $fillable =[
        'date',
        'time_rang',
        'waiting', 
        'hash', 
        'appoin_id', 
        'customer_id', 
     ];
    
    public function service(){
        return $this->hasMany(Service::class);
    }
     public function customer(){
        return $this->belongsTo(Customer::class);
    }
 
}