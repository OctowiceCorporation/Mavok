<?php


namespace App\Controller\Admin;


use App\Entity\Brand;
use App\Form\AddBrandForm;
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

    public function editBrand($id, Request $request, BrandRepository $brandRepository, EntityManagerInterface $entityManager)
    {
        $brand = $brandRepository->findOneBy(['id' => $id]);
        if(empty($brand))
            return new Response('Brand not found', 404);

        $form = $this->createForm(AddBrandForm::class, $brand);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $brand = $form->getData();
            $entityManager->persist($brand);
            $entityManager->flush();
            return $this->redirectToRoute('brands');
        }

        return $this->render('admin/add_brand.html.twig', ['form' => $form->createView()]);

    }

    public function index(BrandRepository $brandRepository)
    {
        $brands = $brandRepository->findAll();
        return $this->render('admin/admin_brands.html.twig', ['brands' => $brands]);
    }

    public function addBrand(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AddBrandForm::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $brand = $form->getData();
            $entityManager->persist($brand);
            $entityManager->flush();
            return $this->redirectToRoute('brands');
        }

        return $this->render('admin/add_brand.html.twig', ['form' => $form->createView()]);

    }
}