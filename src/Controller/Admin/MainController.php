<?php


namespace App\Controller\Admin;


use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{

    public function index()
    {
        return $this->render('admin/admin_index.html.twig');
    }

    public function productsView(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request)
    {
        $products  = $paginator->paginate(
            $productRepository->getProductsQuery(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 30)
        );

        return $this->render('admin/admin_product.html.twig', ['products' => $products]);
    }
}