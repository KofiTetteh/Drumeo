<?php
namespace App\Middleware;

use Slim\Views\Twig; 
use Slim\Csrf\Guard;
use App\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CsrfViewMiddleware  
{
  protected $csrf;

  protected $view;

  public function __construct(Guard $csrf,Twig $view)
  {
    $this->csrf = $csrf;
    $this->view = $view;
  }
   public function __invoke(Request $request, Response $response, $next)
    { 
      $this->view->getEnvironment()->addGlobal('csrf', [
          'field' => '
            <input type="hidden" name="'.$this->csrf->getTokenNameKey().'" value="'. $this->csrf->getTokenName().'"> 
            <input type="hidden" name="'.$this->csrf->getTokenValueKey().'" value="'. $this->csrf->getTokenValue().'"> 
          ',
        ]);
      
      $response = $next($request, $response);
      return $response;
    }
     
}