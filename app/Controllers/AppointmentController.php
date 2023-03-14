<?php

namespace App\Controllers;

use Slim\Views\Twig;
use Slim\Router;
use Slim\Flash\Messages;
use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Service;
use App\Validation\Contracts\ValidatorInterface;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Auth\Auth; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;

class AppointmentController 
{
    protected $view;

    protected $flash;

    protected $validator;

    protected $router;

    public function __construct(Twig $view, Messages $flash, ValidatorInterface $validator, Router $router)
    {
       
        $this->view = $view;

        $this->flash = $flash;

        $this->validator = $validator;

        $this->router = $router;
    }
    public function index(Request $request,Response $response)
    {
         
       
      return $this->view->render($response, 'appointment.twig',[
       
        ]);
    } 
    
     public function appointmentPost(Request $request,Response $response, Auth $auth)
    { 
           if ($request->getParam('serviceSelect') == '') {
            $this->flash->addMessage('error', 'Please select at least one service');
                return $response->withRedirect($this->router->pathFor('appointment'));
           }
        $validation = $this->validator->validate($request, [
                'name' => v::notEmpty(),
                'email' => v::notEmpty()->Email(),
                'telephone' => v::notEmpty()->intval()
            ]);
        if ($validation->fails()) {  
                return $response->withRedirect($this->router->pathFor('appointment'));
           }

       $customer = Customer::firstOrCreate([
            'name' => $request->getParam('name'),
            'email' => $request->getParam('email'),
            'telephone' => $request->getParam('telephone'),
        ]);

         $hash = bin2hex(random_bytes(32));
           $apoint_id = bin2hex(random_bytes(4));
        $apointment = Appointment::Create([
                    'date' => $request->getParam('date'),
                    'time_rang' => $request->getParam('time_rang'),
                    'waiting' => $request->getParam('waiting'), 
                    'hash' => $hash, 
                    'appoin_id' => $apoint_id, 
                    'customer_id' => $customer->id, 
            ]);

        foreach ($request->getParam('serviceSelect') as $item) {
            $service = Service::Create([
                    'name' => $item,
                    'appointment_id' => $apointment->id,
                ]);
        }
  
            $this->flash->addMessage('info', 'Booking was successful. Your Booking ID is '.$apoint_id);
           return  $response->withRedirect($this->router->pathFor('appointment'));

    }

     public function appointment(Request $request,Response $response)
    {
       
       $apoints = Appointment::with('service','customer')->get();
       
      
      return $this->view->render($response, 'admin/appointment/appointment_view.twig',[
            'appointments' => $apoints 
        ]);
    } 
}