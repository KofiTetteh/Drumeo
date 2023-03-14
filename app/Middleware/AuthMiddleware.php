<?php
namespace App\Middleware;

use Slim\Flash\Messages;
use Slim\Router;
use App\Auth\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
 
class AuthMiddleware 
{ 
     protected $router;

     protected $auth;

     protected $message;


    public function __construct(Auth $auth, Router $router, Messages $message)
    { 
        $this->auth = $auth;

       $this->router = $router;

       $this->message = $message;
    }
   public function __invoke(Request $request, Response $response, $next)
    { 
       if (!$this->auth->check()) {
            $this->message->addMessage('error', 'Please Sign in');
            return $response->withRedirect($this->router->pathFor('auth.login'));
       }
      $response = $next($request, $response);
      return $response;
    }
     
}