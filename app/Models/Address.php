<?php
namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

/**
* 
*/
class Address extends Model
{
    
     protected $fillable =[
        'address1',
        'address2',
        'city', 
     ];
    
    // public function order()
    // {
    //     return $this->hasMany(Order::class); 
    // }
}