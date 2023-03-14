<?php

namespace App\Controllers;


use Slim\Router;
use Slim\Views\Twig; 
use Slim\Flash\Messages;
use App\Models\Product;
use App\Validation\Contracts\ValidatorInterface;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


/**
* 
*/
class ProductController  
{

    protected $view;

    protected $flash;

    protected $validator;

    public function __construct(Twig $view, Messages $flash, ValidatorInterface $validator)
    {
       
        $this->view = $view;

        $this->flash = $flash;

        $this->validator = $validator;
    }
    
   public function get($slug, Request $request,Response $response, Product $product, Router $router)
    {
        $product = $product->where('slug', $slug)->first(); 

        if (!$product) {
           return $response->withRedirect($router->pathFor('menu'));
        }

        return $this->view->render($response, 'products/product.twig',[
            'product' => $product,
            'hasLowStock'  => $product->hasLowStock(),
            'outOfStock'  => $product->outOfStock(),
            'inStock'  => $product->inStock(),
            ]);


    }

    public function getupdateProduct($slug, Request $request,Response $response, Product $product, Router $router)
    {
        $product = $product->where('slug', $slug)->first();  

        if (!$product) {
           return $response->withRedirect($router->pathFor('product.view'));
        }

        return $this->view->render($response, 'admin/product/update.twig',[
            'products' => $product
            ]);


    }
    public function postupdateProduct($slug, Request $request,Response $response, Product $product, Router $router)
    {
        // $product = $product->where('slug', $slug)->first(); 

        $validation = $this->validator->validate($request,[
                'name' => v::notEmpty()->alnum(),
                'slug' => v::notEmpty()->alnum(),
                'description' => v::notEmpty()->alpha(), 
                'price' => v::notEmpty()->intVal(),
                'stock' => v::notEmpty()->intVal(), 
            ]);

        if ($validation->fails()) {
            return $response->withRedirect($router->pathFor('product.update',['slug'=> $slug]));
        }

        $update = $product->where('slug', $slug)->update([
              'title' => $request->getParam('name'),
              'slug' => $request->getParam('slug'),
              'price' => $request->getParam('price'),
              'stock' => $request->getParam('stock'),
              'stock' => $request->getParam('stock'),
          ]);

          $this->flash->addMessage('info', 'Update successful');
           return $response->withRedirect($router->pathFor('product.view')); 

    }

    public function getAddProduct(Request $request,Response $response)
    {
        return $this->view->render($response, 'admin/product/add.twig');
    }

    public function postAddProduct(Request $request,Response $response, Product $product, Router $router)
    {
        $files = $_FILES['bimage'];
        $file = $this->image($files); 

        if ($file == 1) {
             $this->flash->addMessage('error', 'Please select an image less than 1mb');
            return $response->withRedirect($router->pathFor('product.add'));
        }elseif ($file == 2) {
           $this->flash->addMessage('error', 'Only jpg, png and gif image format are support');
            return $response->withRedirect($router->pathFor('product.add'));
        }
 
        $validation = $this->validator->validate($request,[
                'name' => v::notEmpty()->alnum(),
                'slug' => v::notEmpty()->alnum(),
                'description' => v::notEmpty()->alpha(),
                'description' => v::notEmpty()->alpha(),
                'price' => v::notEmpty()->intVal(),
                'stock' => v::notEmpty()->intVal(), 
            ]);
        if ($validation->fails()) {
            unlink('upload/'. $file);
            return $response->withRedirect($router->pathFor('product.add'));
        }

        $product = $product->firstOrCreate([
                'title' => $request->getParam('name'),
                'slug' => $request->getParam('slug'),
                'description' => $request->getParam('description'),
                'price' => $request->getParam('price'),
                'stock' => $request->getParam('stock'),
                'image' =>  $file,
            ]);
         $this->flash->addMessage('info', 'Product Added Successfully');
        return $response->withRedirect($router->pathFor('product.add'));
    }

    public function getViewProduct(Request $request,Response $response, Product $product)
    {
        $product = $product->all();
        return $this->view->render($response, 'admin/product/view.twig',[
            'products' => $product
            ]);
    }

    public function image($file = array() )
    { 
              $file_name = $file['name'];
              $file_tmp = $file['tmp_name'];
              $file_size = $file['size'];
              $file_error = $file['error'];
              $file_ext = explode('.', $file_name);
              $file_ext = strtolower(end($file_ext));
              $allowed = array('jpg','png','gif');

              if (in_array($file_ext, $allowed)) {
                if ($file_error === 0) {
                    if ($file_size <= 1000000) {
                      $file_name_new = uniqid('', true) . '.'. $file_ext;
                      $file_destination = 'upload/'. $file_name_new;
                        if (move_uploaded_file($file_tmp, $file_destination)) {
                            return $file_name_new;  
                        }
                     } 
                     //if file is bigger than 1mb
                  return 1;
                }
              }else{

              // not required format 
                return 2;
              } 
    }
}