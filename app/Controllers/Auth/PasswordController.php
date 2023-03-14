<?php

namespace App\Controllers\Auth;

use Slim\Views\Twig;
use Slim\Router;
use App\Models\Product;
use App\Auth\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Validation\Contracts\ValidatorInterface;
use Respect\Validation\Validator  as v;
use Slim\Flash\Messages;


class PasswordController  
{ 
    protected $view;

    protected $validator;

    protected $router;

    protected $flash;

    protected $auth;

    public function __construct(Twig $view, ValidatorInterface $validator, Messages $flash, Router $router, Auth $auth)
    {
      $this->view = $view;

      $this->auth = $auth;

      $this->validator = $validator;

      $this->flash = $flash;

      $this->router = $router;
    }
    public function getPasswordChange(Request $request, Response $response){
          
     

  }

  public function postPasswordChange(request $request,Response $response){

          // if ($request->getParam('email')) {
          //    Teacher::where('id',$this->auth->user()->id)->update([
          //         'name' => $request->getParam('name'),
          //         'phone' => $request->getParam('phone'),
          //         'address' => $request->getParam('address'),
          //     ]);
          //   $this->flash->addMessage('info', 'Details Updated Successfully');
          // return $response->withRedirect($this->router->pathFor('auth.password.change'));
          // }

        $validation = $this->validator->validate($request, [
                'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
                'password' => v::noWhitespace()->notEmpty(),
            ]);

        if ($validation->fails()) {
            return $response->withRedirect($this->router->pathFor('admin.dashboard'));
        }
       $this->auth->user()->setPassword($request->getParam('password'));

       $this->flash->addMessage('info', 'Your Password was changed');
       return $response->withRedirect($this->router->pathFor('admin.dashboard'));
  }
}

 