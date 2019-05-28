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

    public function getRecentlyViewed(Request $request, ProductService $productService, ProductRepository $productRepository)
    {
        $viewed = json_decode($request->cookies->get('viewed_products'));
        $products = new ArrayCollection();
        foreach ($viewed as $item) {
            $products->add($productService->getProductPrice($productRepository->findOneBy(['slug' => $item])));
        }
        return $this->render('recently_viewed.html.twig',
            ['products' => $products]);
    }
}