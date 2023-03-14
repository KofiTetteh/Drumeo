<?php

use function DI\get;
use Slim\Views\Twig;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use App\Basket\Basket;
use App\Models\Product;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use Slim\Views\TwigExtension;
use Interop\Container\ContainerInterface;
use App\Support\Storage\SessionStorage;
use App\Support\Storage\Contracts\StorageInterface;
use App\Validation\Contracts\ValidatorInterface;
use App\Validation\Validator;
use App\Auth\Auth;


return[
    'router' => get(Slim\Router::class),
     Guard::class => function (ContainerInterface $c){
        return new  Guard('csrf');
    },  
    Messages::class => function (ContainerInterface $c){
        return new  Messages;
    },  
    ValidatorInterface::class => function (ContainerInterface $c){
        return new  Validator;
    }, 
    StorageInterface::class => function (ContainerInterface $c){
        return new  SessionStorage('cart');
    }, 
 
    Twig::class => function (ContainerInterface $c){
        $twig = new Twig(__DIR__ . '/../resources/views',[
            'cache' => false
            ]);

        $twig->addExtension(new TwigExtension(
            $c->get('router'),
            $c->get('request')->getUri()
            ));

        $twig->getEnvironment()->addGlobal('basket', $c->get(Basket::class));
        $twig->getEnvironment()->addGlobal('auth', $c->get(Auth::class));
        $twig->getEnvironment()->addGlobal('flash', $c->get(Messages::class));

        return $twig;
    },
    
    Product::class => function(ContainerInterface $c){
        return new Product;
    },
    User::class => function(ContainerInterface $c){
        return new User;
    },
    Order::class => function(ContainerInterface $c){
        return new Order;
    },
    Customer::class => function(ContainerInterface $c){
        return new Customer;
    }, 
    Address::class => function(ContainerInterface $c){
        return new Address;
    },
    Payment::class => function(ContainerInterface $c){
        return new Payment;
    },
    Auth::class => function(ContainerInterface $c){
        return new Auth;
    },

    Basket::class => function(ContainerInterface $c){
        return new Basket(
            $c->get(SessionStorage::class),
            $c->get(Product::class)
            );
    },

];