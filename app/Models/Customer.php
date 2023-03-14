<?php
namespace App\Models;

use App\Models\Product; 
use App\Models\Order;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
/**
* 
*/
class Customer extends Model
{
    
     protected $fillable =[
        'email',
        'name',
        'telephone', 
     ];

     public function orders()
     {
        return $this->hasMany(Order::class);
     }
      public function appointment(){
        return $this->hasMany(Appointment::class);
    }
    
}