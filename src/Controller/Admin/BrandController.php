<?php


namespace App\Controller\Admin;


use App\Entity\Brand;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends AbstractController
{


    public function addBrandAjax(Request $request, EntityManagerInterface $entityManager)
    {
        $name = $request->get('name');
        $country = $request->get('country');
        $usd = $request->get('usd');
        $euro = $request->get('euro');
        $brand = new Brand();
        $brand->setName($name);
        $brand->setCountry($country);
        if(!empty($usd))
            $brand->setUsdValue(floatval($usd));
        if(!empty($euro))
            $brand->setEurValue(floatval($euro));
        $entityManager->persist($brand);
        $entityManager->flush();

        return new Response($brand->getId());

    }

    public function index(BrandRepository $brandRepository)
    {
        $brands = $brandRepository->findAll();
        return $this->render('admin/admin_brands.html.twig', ['brands' => $brands]);
    }
}