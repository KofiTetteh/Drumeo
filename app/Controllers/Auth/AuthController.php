<?php
namespace App\Controllers\Auth;

use App\Models\User;
use Slim\Views\Twig;
use Slim\Router;
use Slim\Flash\Messages;
use App\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Auth\Auth;
/**
* 
*/
class AuthController
{
    protected $auth;

    protected $router;

    protected $flash;

    public function __construct(Auth $auth,Router $router, Messages $flash)
    {
      $this->auth = $auth;
      $this->router = $router;
      $this->flash = $flash;
    }

      public function getSignOut(Request $request,Response $response)
      {
        $this->auth->logOut();

        return $response->withRedirect($this->router->pathFor('home'));
      }
       public function login(Request $request,Response $response, Twig $view)
        {  
             
          return $view->render($response, 'auth/login.twig',[
            
            ]);
        } 

     public function postSignin(Request $request, Response $response, Twig $view)
     { 
        $auth = $this->auth->attempt(
            $request->getParam('email'), 
            $request->getParam('password')
            );

        if (!$auth) { 
            $this->flash->addMessage('error', 'Your Email or password is wrong');
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }
        
            return $response->withRedirect($this->router->pathFor('admin.dashboard'));

     }



}