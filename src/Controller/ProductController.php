<?php


namespace App\Controller;


use App\Repository\ProductRepository;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{

    public function index($slug, ProductRepository $productRepository, Request $request, ProductService $productService)
    {
        $product = $productService->getProductPrice($productRepository->findOneBy(['slug' => $slug]));
        $viewed = json_decode($request->cookies->get('viewed_products'));
        if(empty($viewed))
            $viewed = [];
        if(!(in_array($product->getSlug(), $viewed)))
            array_unshift($viewed, $product->getSlug());
        if(sizeof($viewed) == 11){
            unset($viewed[10]);
        }
            
        $cookie = new Cookie('viewed_products', json_encode($viewed));
        $response = new Response(var_dump($viewed));
        $response->headers->setCookie($cookie);

        return $response;
    }
}