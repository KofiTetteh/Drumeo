<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

/**
* 
*/
class Payment extends Model
{
    
     protected $fillable =[
        'failed',
        'transaction_id'  
     ];
    
    // public function order()
    // {
    //     return $this->hasMany(Order::class); 
    // }
}