<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\AddCategoryForm;
use App\Repository\CategoryRepository;
use App\Service\UploadFileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    public function categoriesView()
    {
        return $this->render('admin/admin_categories.html.twig');
    }


    public function add_sub_category($id, CategoryRepository $categoryRepository, Request $request, UploadFileService $fileService, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AddCategoryForm::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $category = new Category();
            $category->setParent($categoryRepository->findOneBy(['id' => $id]))
                ->setName($data->getName())
                ->setDescription($data->getDescription())
                ->setUsdValue($data->getUsd())
                ->setEurValue($data->getEur());
            if(!empty($data->getImage()))
                $category->setImage($fileService->upload($data->getImage()));
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('categories');
        }

        return $this->render('admin/edit_subcategory.html.twig', ['form' => $form->createView()]);
    }
}