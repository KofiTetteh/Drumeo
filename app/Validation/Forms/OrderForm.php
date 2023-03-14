<?php
namespace App\Validation\Forms;

use Respect\Validation\Validator as v;
/**
* 
*/
class OrderForm
{
    
    public static function rules()
    {
        return [
            'email' => v::notEmpty()->email(),
            'name' => v::notEmpty()->alpha(' '),
            'phone' => v::notEmpty()->intVal(),
            'address1' => v::notEmpty()->alnum(' -'),
            'address2' => v::optional(v::alnum(' -')),
            'city' => v::alnum(' '),

        ];
    }
}