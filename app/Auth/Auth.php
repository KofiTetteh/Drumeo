<?php
namespace App\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;
use App\Models\Product;
use App\Models\order_product;
use App\Models\Order;
use App\Models\Address;
/**
* 
*/
class Auth   
{
     public function attempt($email, $password){
            $user = User::where('email', $email)->first();

            if (!$user) {
                return false;
            }

            if (password_verify($password, $user->password)) {
                $_SESSION['user'] = $user->id; 
                return true;
            }
        }

    public function user()
    {
        if (isset($_SESSION['user'])) {
             return $user = User::find($_SESSION['user']);
        }
    }  

    public function check()
    {
    return isset($_SESSION['user']);
    }
    
    public function logOut()
    {
     unset($_SESSION['user']);
    } 
    public function orderAddress($id)
    {
     $op = Order::find($id);
    return $opp = Address::find($op->address_id);

    }

    public function getproduct($id)
    {
      
        $op = order_product::where('order_id', $id)->get(); 
        $items = [];
        foreach ($op as $item) {
             $products = Product::where('id', $item->product_id)->get();
             foreach ($products as $product) {
                  $items[] = [
                    'id' => $product->id,
                    'name' => $product->title,
                    'price' => $product->price,
                    'quantity' => $item->quantity
                  ];
             }
        } 
        return $items;
    }
}