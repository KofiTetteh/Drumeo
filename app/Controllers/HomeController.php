<?php

namespace App\Controllers;

use Slim\Views\Twig;
use App\Models\Product;
use Slim\Router;
use Slim\Flash\Messages;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Auth\Auth; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;

class HomeController 
{
    public function index(Request $request,Response $response, Twig $view, Product $product )
    {
        $products = $product->get();  
         
      return $view->render($response, 'home.twig',[
        'products' => $products
        ]);
    } 
    
     public function dashboard(Request $request,Response $response, Twig $view, Product $product, Auth $auth)
    { 
      $products = $product->count();
      $now = Carbon::now();
      $month = DB::table('orders')->select()->where('created_at', '>', DATE_SUB($now, date_interval_create_from_date_string('1 month') ))->count();

        // $thisweek = DB::getInstance()->query("SELECT COUNT(*) AS thisweek FROM sale WHERE WEEKOFYEAR(saledate)=WEEKOFYEAR(NOW())")->first()->thisweek;

      // $thisweek = DB::table('orders')->select()->where(WEEKOFYEAR(`cteated_at`), "=", WEEKOFYEAR($now))->count();
     
      // dd(count($month));
      // $num = count($products ) ;
      return $view->render($response, 'admin/dashboard.twig',[
        'user' => $auth->user(),
        'product' => $products,
        'month' => $month
        ]);
    } 
      public function about_us(Request $request,Response $response, Twig $view )
    { 
      return $view->render($response, 'about_us.twig',[ 
        ]);
    } 
    public function contact_us(Request $request,Response $response, Twig $view )
    { 
      return $view->render($response, 'contact.twig',[ 
        ]);
    } 
     public function contact(Request $request,Response $response, Twig $view,Messages $flash,Router $router)
    { 
       $flash->addMessage('info', 'Thank you for contacting us, we will get back to you as soon as possible');
      return $response->withRedirect($router->pathFor('contact_us')); 
    } 
  
}