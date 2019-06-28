<?php


namespace App\Controller;


use App\Mappers\Category;
use App\Mappers\Specification;
use App\Repository\ProductRepository;
use App\Service\CategoryService;
use App\Service\ProductService;
use App\Service\SortService;
use Doctrine\Common\Collections\ArrayCollection;
use function GuzzleHttp\Promise\queue;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductController extends AbstractController
{

    public function index($slug, ProductRepository $productRepository, Request $request, ProductService $productService, CategoryService $categoryService, SessionInterface $session)
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


        $recommended_products = $product->getRecommendProduct();
        $recommended = new ArrayCollection();
        foreach ($recommended_products as $recommended_product) {
            $recommended->add($productService->getProductPrice($recommended_product));
        }
        $specifications = $productService->getSpecifications($product);
        $brand = $product->getBrand();
        $crumbs = $productService->getParentCategories($product);
        foreach ($crumbs as $index => $crumb) {
            $crumbs[$index] = Category::entityToDTO($crumb, substr($categoryService->generateUrlFromCategory($crumb), 1));
        }
        $basket = $session->get('basket');
        $amount = 0;
        if(isset($basket[$product->getId()]))
            $amount = $basket[$product->getId()];
        $product = $productService->getProductPrice($product, null, $amount);
        $viewed = json_decode($request->cookies->get('viewed_products'));
        if(empty($viewed))
            $viewed = [];
        if(!(in_array($product->getSlug(), $viewed)))
            array_unshift($viewed, $product->getSlug());
        if(sizeof($viewed) == 11){
            unset($viewed[10]);
        }
            
        $cookie = new Cookie('viewed_products', json_encode($viewed));
        $response = new Response($this->renderView('product.html.twig', ['product' => $product, 'specifications' => $specifications, 'brand' => $brand, 'admin' => $admin, 'crumbs' => $crumbs, 'recommend' => $recommended
        ]));
        $response->headers->setCookie($cookie);

        return $response;
    }

    public function searchProduct(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator, ProductService $productService, SortService $sortService)
    {
        $sort = $request->get('sort_by');
        $text = $request->query->get('search_text');
        $entities = $productRepository->searchProducts($text);
        $products = new ArrayCollection();
        foreach ($entities as $entity) {
            $products->add($productService->getProductPrice($entity));
        }
        $products = $products->toArray();
        if(!empty($sort))
            $sortService->sort($sort, $products);
        else{
            usort($products, function($a, $b)
            {
                return $a->isIsAvailable() < $b->isIsAvailable();
            });
        }
        $length = sizeof($products);
        $products = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );

        return $this->render('search.html.twig', ['products' => $products, 'length' => $length, 'search_text' => $text]);

    }

    public function getRecentlyViewed(Request $request, ProductService $productService, ProductRepository $productRepository, SessionInterface $session)
    {
        $viewed = json_decode($request->cookies->get('viewed_products'));
        if(empty($viewed))
            return new Response(null, 200);
        $products = new ArrayCollection();
        $basket = $session->get('basket');
        foreach ($viewed as $item) {
            $prod = $productRepository->findOneBy(['slug' => $item]);
            if(!empty($prod) && $prod->getIsVisible()){
                $amount = 0;
                if(isset($basket[$prod->getId()]))
                    $amount = $basket[$prod->getId()];
                $products->add($productService->getProductPrice($prod, null, $amount));
            }}
        return $this->render('recently_viewed.html.twig',
            ['products' => $products]);
    }

    public function ajaxSearch($text, ProductRepository $productRepository)
    {
        if(empty($text))
            return new Response(json_encode([]));

        $products = $productRepository->searchProductsWithLimit($text, 5);

        $array = [];
        foreach ($products as $key => $product) {
            $array[$key]['name'] = $product->getName();
            $array[$key]['slug'] = $product->getSlug();
        }

        return new Response(json_encode($array));
    }
}