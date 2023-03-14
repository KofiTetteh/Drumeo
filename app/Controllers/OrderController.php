<?php

namespace App\Controllers;

use Slim\Views\Twig;
use Slim\Router;
use Slim\Flash\Messages;
use App\Basket\Basket;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Address;
use App\Validation\Contracts\ValidatorInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request; 
use App\Validation\Forms\OrderForm;
use Braintree_Transaction;

class OrderController 
{
    protected $basket;

    protected $router;

    protected $validator;

    protected $flash;

    public function __construct(Basket $basket, Router $router, ValidatorInterface $validator, Messages $flash)
    {
        $this->basket = $basket;
        $this->router = $router;
        $this->validator = $validator;
        $this->flash = $flash;
    }

    public function index(Request $request,Response $response, Twig $view, Product $product )
    {
         $this->basket->refresh();

         if (!$this->basket->subTotal()) {
             return  $response->withRedirect($this->router->pathFor('cart.index'));
         }
      return $view->render($response, 'order/index.twig');
    } 

    public function getViewOrder(Request $request,Response $response, Twig $view, Order $order)
    {

        $order = $order->with('customer')->get();
        return $view->render($response, 'admin/order/view.twig',[
            'orders' => $order, 
            ]);
    }

    public function deliver(Request $request,Response $response, Twig $view, Order $order)
    {
        $order = $order->where('id', $request->getParam('id'))->update([
                'delivered' => $request->getParam('yes')
            ]);

            $this->flash->addMessage('info', 'Product delivered successfully');
           return  $response->withRedirect($this->router->pathFor('order.view'));

    }
    
    public function show($hash, Request $request,Response $response, Twig $view, Order $order)
    {
        $order = $order->with('address', 'products')->where('hash', $hash)->first();

        if (!$order) {
            return $response->withRedirect($this->router->pathFor('menu'));
        }

        return $view->render($response, 'order/show.twig',[
            'order' => $order
            ]);
    }

     public function create(Request $request, Response $response, Customer $customer, Address $address)
     { 
                 $this->basket->refresh();

         if (!$this->basket->subTotal()) {
             return  $response->withRedirect($this->router->pathFor('cart.index'));
         }

         if (!$request->getParam('payment_method_nonce')) {
             return $request->withRedirect($this->router->pathFor('order.index'));
         }
           $validation = $this->validator->validate($request, OrderForm::rules());

           if ($validation->fails()) {
            
                return $response->withRedirect($this->router->pathFor('order.index'));
           }

           $hash = bin2hex(random_bytes(32));

           $customer = $customer->firstOrCreate([
                'email' => $request->getParam('email'),
                'name' => $request->getParam('name'),
                'telephone' => $request->getParam('phone'),
            ]);
         
         $address = $address->firstOrCreate([
            'address1' => $request->getParam('address1'),
            'address2' => $request->getParam('address2'),
            'city' => $request->getParam('city'),
            ]);

         $order = $customer->orders()->create([
            'hash' => $hash,
            'paid' => false,
            'total' => $this->basket->subTotal() + 5,
            'address_id' => $address->id,
            ]);


            $allitem = $this->basket->all();
         $order->products()->saveMany(
            $allitem,
            $this->getQuantities($allitem)
            );


         $result = Braintree_Transaction::sale([
                'amount' => $this->basket->subTotal() + 5,
                'paymentMethodNonce' =>$request->getParam('payment_method_nonce'),
                'options' =>[
                    'submitForSettlement' => True,
                ]
            ]);

         $event = new \App\Events\OrderWasCreated($order, $this->basket);

         if (!$result->success) {
           $event->attach(new \App\Handlers\RecordFailedPayment); 
           $event->dispatch();

           return $response->withRedirect($this->router->pathFor('order.index'));
         }

         $event->attach([
            new \App\Handlers\MarkOrderPaid,
            new \App\Handlers\RecordSuccessfulPayment($result->transaction->id),
            new \App\Handlers\UpdateStock,
            new \App\Handlers\EmptyBasket,
            ]);

         $event->dispatch();

        return $response->withRedirect($this->router->pathFor('order.show',[
                'hash' => $hash,
            ]));
     }

     public function getQuantities($items)
     {
        $quantities = [];

        foreach ($items as $item) {
            $quantities[] = ['quantity' => $item->quantity];
        }

        return $quantities;
     }
  
}