<?php
namespace App\Middleware;

use Slim\Views\Twig;

/**
* 
*/
class OldInputMiddleware
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }
     
     public function __invoke($request, $response, $next)
     {
        if (isset($_SESSION['old'])) {
            $this->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
            // unset($_SESSION['errors']);
        }

        $_SESSION['old'] = $request->getParams();

        $response = $next($request, $response);
        return $response;
     }
}