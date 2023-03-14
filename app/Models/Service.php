<?php
namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

/**
* 
*/
class Service extends Model
{
    
     protected $fillable =[
        'name',
        'appointment_id', 
     ];
    
 
}