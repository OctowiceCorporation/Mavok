<?php


namespace App\Controller\Admin;


use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\CategoryService;
use App\Service\ProductService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{


    public function getProducts($id, CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request, ProductService $productService)
    {
        $category = $categoryRepository->findOneBy(['id' => $id]);
        if(empty($category))
            return new Response('Category not found', 404);

        $products = $productService->getProductsFromCategory($category);

        $products = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 30)
        );

        return $this->render('admin/admin_product.html.twig', ['products' => $products]);
    }
}