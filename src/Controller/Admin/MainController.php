<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{

    public function index()
    {
        return $this->render('admin/admin_index.html.twig');
    }

    public function productsView()
    {
        return $this->render('admin/admin_product.html.twig');
    }
}