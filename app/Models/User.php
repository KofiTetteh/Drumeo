<?php
namespace App\Models;

use illuminate\Database\Eloquent\Model;

/**
* 
*/
class User extends Model
{
 protected $fillable = [ 
            'password' 
    ];
    public function setPassword($password){
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
    } 
}