<?php
use Slim\Flash\Messages;
use Slim\Router;
use App\Middleware\AuthMiddleware;
use App\Auth\Auth;

$app->group('', function(){

$this->get('/admin/dashboard', ['App\Controllers\HomeController', 'dashboard'])->setName('admin.dashboard');

$this->get('/admin/product/add', ['App\Controllers\ProductController', 'getAddProduct'])->setName('product.add');
$this->post('/admin/product/add', ['App\Controllers\ProductController', 'postAddProduct']);
$this->get('/admin/product/view', ['App\Controllers\ProductController', 'getViewProduct'])->setName('product.view');

$this->get('/admin/product/{slug}', ['App\Controllers\ProductController', 'getupdateProduct'])->setName('product.update');
$this->post('/admin/product/{slug}', ['App\Controllers\ProductController', 'postupdateProduct'])->setName('product.update.post');

$this->get('/admin/order/view', ['App\Controllers\OrderController', 'getViewOrder'])->setName('order.view');
$this->post('/admin/order/view', ['App\Controllers\OrderController', 'deliver'])->setName('order.deliver');

$this->get('/admin/appointment/list', ['App\Controllers\AppointmentController', 'appointment'])->setName('admin.appointment');

$this->post('/admin/dashboard/password', ['App\Controllers\Auth\PasswordController', 'postPasswordChange'])->setName('password.change');

})->add(new AuthMiddleware($container->get(Auth::class),$container->get(Router::class),$container->get(Messages::class)));

$app->get('/', ['App\Controllers\HomeController', 'index'])->setName('home'); 

$app->get('/appointment', ['App\Controllers\AppointmentController', 'index'])->setName('appointment'); 
$app->post('/appointment', ['App\Controllers\AppointmentController', 'appointmentPost']); 

$app->get('/products/menu', ['App\Controllers\MenuController', 'menu'])->setName('menu');

$app->get('/products/{slug}', ['App\Controllers\ProductController', 'get'])->setName('product.get');

$app->get('/cart', ['App\Controllers\CartController', 'index'])->setName('cart.index');

$app->get('/order', ['App\Controllers\OrderController', 'index'])->setName('order.index');
$app->post('/order', ['App\Controllers\OrderController', 'create'])->setName('order.create');
$app->get('/order/{hash}', ['App\Controllers\OrderController', 'show'])->setName('order.show');

$app->get('/cart/add/{slug}/{quantity}', ['App\Controllers\CartController', 'add'])->setName('cart.add');
$app->post('/cart/add/{slug}', ['App\Controllers\CartController', 'update'])->setName('cart.update');


$app->get('/auth/login', ['App\Controllers\Auth\AuthController', 'login'])->setName('auth.login');
$app->post('/auth/login', ['App\Controllers\Auth\AuthController', 'postSignin']);
$app->get('/auth/logout', ['App\Controllers\Auth\AuthController', 'getSignOut'])->setName('auth.logout');

$app->get('/braintree/token', ['App\Controllers\BraintreeController', 'token'])->setName('braintree.token');

$app->get('/about_us', ['App\Controllers\HomeController', 'about_us'])->setName('about_us');
$app->get('/contact_us', ['App\Controllers\HomeController', 'contact_us'])->setName('contact_us');
$app->post('/contact_us', ['App\Controllers\HomeController', 'contact']);


