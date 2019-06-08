<?php


namespace App\Controller;


use App\Mappers\Specification;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{

    public function index($slug, ProductRepository $productRepository, Request $request, ProductService $productService)
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        if(empty($product))
            return new Response('Product not found', 404);
        $specifications = $productService->getSpecifications($product);
        $brand = $product->getBrand();
        $product = $productService->getProductPrice($product);
        $viewed = json_decode($request->cookies->get('viewed_products'));
        if(empty($viewed))
            $viewed = [];
        if(!(in_array($product->getSlug(), $viewed)))
            array_unshift($viewed, $product->getSlug());
        if(sizeof($viewed) == 11){
            unset($viewed[10]);
        }
            
        $cookie = new Cookie('viewed_products', json_encode($viewed));
        $response = new Response($this->renderView('product.html.twig', ['product' => $product, 'specifications' => $specifications, 'brand' => $brand]));
        $response->headers->setCookie($cookie);

        return $response;
    }

    public function searchProduct($text, ProductRepository $productRepository)
    {
        $products = $productRepository->searchProducts($text);
        $array = [];
        foreach ($products as $key => $product) {
            $array[$key]['name'] = $product->getName();
            $array[$key]['slug'] = $product->getSlug();
            $array[$key]['category'] = $product->getCategory()->getName();
        }
        return new Response(json_encode($array));
    }

    public function getRecentlyViewed(Request $request, ProductService $productService, ProductRepository $productRepository)
    {
        $viewed = json_decode($request->cookies->get('viewed_products'));
        if(empty($viewed))
            return new Response(null, 200);
        $products = new ArrayCollection();
        foreach ($viewed as $item) {
            $prod = $productRepository->findOneBy(['slug' => $item]);
            if(!empty($prod))
                $products->add($productService->getProductPrice($prod));
        }
        return $this->render('recently_viewed.html.twig',
            ['products' => $products]);
    }
}