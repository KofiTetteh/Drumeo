<?php
namespace App\Models;

use App\Models\Product; 
use App\Models\Address; 
use App\Models\Payment; 
use App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
/**
* 
*/
class Order extends Model
{ 
     protected $fillable =[
        'hash',
        'total',
        'paid',
        'address_id',
     ];

     public function address()
     {
        return $this->belongsTo(Address::class);
     }  
     public function customer()
     {
        return $this->belongsTo(Customer::class);
     } 
     public function products()
     {
        return $this->belongsToMany(Product::class, 'orders_products')->withPivot('quantity');
     }

     public function payment()
     {
        return $this->hasOne(Payment::class);
     }
    
}