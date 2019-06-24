<?php


namespace App\Controller;


use App\Mappers\Specification;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\Common\Collections\ArrayCollection;
use function GuzzleHttp\Promise\queue;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{

    public function index($slug, ProductRepository $productRepository, Request $request, ProductService $productService)
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        $admin = false;
        if(empty($product))
            return new Response('Product not found', 404);
        if(!$product->getIsVisible()){
            if(!empty($this->getUser()) && in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
                $admin = true;
            }
            else
                return new Response('', 403);
        }


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
        $response = new Response($this->renderView('product.html.twig', ['product' => $product, 'specifications' => $specifications, 'brand' => $brand, 'admin' => $admin]));
        $response->headers->setCookie($cookie);

        return $response;
    }

    public function searchProduct(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator, ProductService $productService)
    {
        $text = $request->query->get('search_text');
        $entities = $productRepository->searchProducts($text);
        $products = new ArrayCollection();
        foreach ($entities as $entity) {
            $products->add($productService->getProductPrice($entity));
        }
        $products = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );



        return $this->render('search.html.twig', ['products' => $products]);

    }

    public function getRecentlyViewed(Request $request, ProductService $productService, ProductRepository $productRepository)
    {
        $viewed = json_decode($request->cookies->get('viewed_products'));
        if(empty($viewed))
            return new Response(null, 200);
        $products = new ArrayCollection();
        foreach ($viewed as $item) {
            $prod = $productRepository->findOneBy(['slug' => $item]);
            if(!empty($prod) && $prod->getIsVisible())
                $products->add($productService->getProductPrice($prod));
        }
        return $this->render('recently_viewed.html.twig',
            ['products' => $products]);
    }
}